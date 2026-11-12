/* ==========================================================================
   SistTa — DataTables
   Activa buscador, orden, filtros y paginación en las tablas de registros.

   Las vistas marcan explícitamente qué tablas son listados:

     <table class="table js-datatable" data-dt-buscar="Buscar por nombre...">
       <thead>
         <tr>
           <th>Nombre</th>
           <th data-dt-filtro>Estado</th>          ← agrega un filtro desplegable
           <th class="dt-acciones">Acciones</th>   ← sin orden ni búsqueda
         </tr>
       </thead>
       <tbody>…</tbody>
     </table>

   Atributos opcionales en <table>:
     data-dt-buscar   texto de ayuda del buscador
     data-dt-orden    columna inicial de orden, p. ej. "0:asc" (por defecto se
                      respeta el orden de la consulta SQL)
   Las fichas de detalle (.table-ficha) no usan DataTables.
   ========================================================================== */
(function ($) {
  'use strict';
  if (!$ || !$.fn || !$.fn.DataTable) { return; }

  var POR_PAGINA = 10;

  var IDIOMA = {
    decimal: '.',      // los montos se imprimen con number_format(): 1,234.50
    thousands: ',',
    emptyTable: 'No hay registros',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Sin registros para mostrar',
    infoFiltered: '(filtrado de _MAX_ en total)',
    lengthMenu: 'Mostrar _MENU_',
    loadingRecords: 'Cargando...',
    processing: 'Procesando...',
    search: '',
    searchPlaceholder: 'Buscar...',
    zeroRecords: 'No se encontraron coincidencias',
    paginate: { first: 'Primero', last: '&Uacute;ltimo', next: 'Siguiente', previous: 'Anterior' },
    aria: {
      sortAscending: ': activar para ordenar de forma ascendente',
      sortDescending: ': activar para ordenar de forma descendente'
    }
  };

  /* --- Búsqueda sin tildes ni mayúsculas ("jose" encuentra "José") ------ */
  // Rango U+0300-U+036F (marcas diacríticas); se arma por código para mantener el archivo ASCII
  var RE_DIACRITICOS = new RegExp('[' + String.fromCharCode(0x300) + '-' + String.fromCharCode(0x36f) + ']', 'g');
  function normalizar(texto) {
    texto = (texto === null || texto === undefined) ? '' : String(texto);
    if (texto.normalize) { texto = texto.normalize('NFD').replace(RE_DIACRITICOS, ''); }
    return $.trim(texto.toLowerCase().replace(/\s+/g, ' '));
  }
  function sinHtml(texto) {
    return $('<div/>').html(String(texto || '')).text();
  }
  function escaparRegex(texto) {
    return texto.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
  $.fn.dataTable.ext.type.search.string = function (d) { return normalizar(d); };
  $.fn.dataTable.ext.type.search.html = function (d) { return normalizar(sinHtml(d)); };

  /* --- Orden correcto de fechas dd-mm-aaaa / dd/mm/aaaa ------------------ */
  var RE_FECHA = /^\s*(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})(?:\s+(\d{1,2}):(\d{2})(?::(\d{2}))?)?\s*$/;
  $.fn.dataTable.ext.type.detect.unshift(function (d) {
    var txt = sinHtml(d);
    return (txt === '' || RE_FECHA.test(txt)) ? 'fecha-dma' : null;
  });
  $.fn.dataTable.ext.type.order['fecha-dma-pre'] = function (d) {
    var m = RE_FECHA.exec(sinHtml(d));
    if (!m) { return 0; }
    return Number(m[3] + ('0' + m[2]).slice(-2) + ('0' + m[1]).slice(-2) +
                  ('0' + (m[4] || 0)).slice(-2) + (m[5] || '00') + (m[6] || '00'));
  };
  $.fn.dataTable.ext.type.search['fecha-dma'] = function (d) { return normalizar(sinHtml(d)); };

  /* --- Buscador amigable: icono, botón para limpiar y atajo Esc ---------- */
  function mejorarBuscador($t, api) {
    var $wrap  = $t.closest('.dataTables_wrapper');
    var $input = $wrap.find('.dataTables_filter input');
    if (!$input.length) { return; }

    var ayuda = $t.attr('data-dt-buscar') || IDIOMA.searchPlaceholder;
    $input.attr({ placeholder: ayuda, 'aria-label': ayuda, autocomplete: 'off' }).addClass('form-control');
    $wrap.find('.dataTables_filter label').prepend('<i class="fa fa-search dt-search-icon" aria-hidden="true"></i>');

    var $limpiar = $('<button type="button" class="dt-search-clear" aria-label="Limpiar b&uacute;squeda" title="Limpiar">&times;</button>')
      .insertAfter($input).hide();
    var buscar = function (valor) {
      var termino = normalizar(valor);
      if (termino !== api.search()) { api.search(termino).draw(); }
      $limpiar.toggle(valor !== '');
    };
    // Reemplaza el manejador nativo para que el término también ignore tildes/mayúsculas
    $input.off('.DT').on('input.sistta keyup.sistta search.sistta paste.sistta cut.sistta', function (e) {
      if (e.key === 'Escape' || e.keyCode === 27) { $input.val(''); }
      buscar($input.val());
    }).on('keypress.sistta', function (e) { if (e.keyCode === 13) { e.preventDefault(); } });
    $limpiar.on('click', function () { $input.val(''); buscar(''); $input.focus(); });
  }

  /* --- Filtros desplegables por columna (th[data-dt-filtro]) ------------- */
  function agregarFiltros($t, api) {
    var $destino = $t.closest('.dataTables_wrapper').find('.dt-toolbar__filtros');
    $t.find('> thead > tr > th[data-dt-filtro]').each(function () {
      var indice = $(this).index();
      var titulo = $.trim($(this).text());
      var valores = {};
      api.column(indice).nodes().each(function (celda) {
        var texto = $.trim($(celda).attr('data-search') || $(celda).text());
        if (texto !== '') { valores[texto] = true; }
      });
      var lista = Object.keys(valores).sort(function (a, b) { return a.localeCompare(b, 'es'); });
      if (lista.length < 2) { return; }   // un solo valor: el filtro no aporta nada

      var $select = $('<select class="form-control dt-filtro"/>')
        .attr({ 'aria-label': 'Filtrar por ' + titulo, title: 'Filtrar por ' + titulo })
        .append($('<option value=""/>').text(titulo + ': todos'));
      $.each(lista, function (_, valor) { $select.append($('<option/>').val(valor).text(valor)); });

      $select.on('change', function () {
        var valor = $(this).val();
        $(this).toggleClass('dt-filtro--activo', valor !== '');
        api.column(indice)
          .search(valor ? '^' + escaparRegex(normalizar(valor)) + '$' : '', true, false)
          .draw();
      });
      $destino.append($select);
    });
  }

  function iniciar($t) {
    if ($t.hasClass('dataTable') || !$t.children('thead').length) { return; }

    var total   = $t.find('> tbody > tr').length;
    var paginar = total > POR_PAGINA;
    var orden   = [];
    var cfgOrden = ($t.attr('data-dt-orden') || '').split(':');
    if (cfgOrden[0] !== '') { orden = [[parseInt(cfgOrden[0], 10), cfgOrden[1] === 'desc' ? 'desc' : 'asc']]; }

    $t.addClass('sistta-dt');
    var api = $t.DataTable({
      language: IDIOMA,
      pageLength: POR_PAGINA,
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
      paging: paginar,
      lengthChange: paginar,
      searching: true,
      info: true,
      order: orden,
      autoWidth: false,
      pagingType: 'simple_numbers',
      columnDefs: [{ targets: 'dt-acciones', orderable: false, searchable: false }],
      dom: "<'dt-toolbar'<'dt-toolbar__search'f><'dt-toolbar__filtros'><'dt-toolbar__length'l>>" +
           "<'dt-scroll't>" +
           "<'dt-footer'<'dt-footer__info'i><'dt-footer__pages'p>>"
    });

    mejorarBuscador($t, api);
    agregarFiltros($t, api);
    $t.closest('.dataTables_wrapper').find('.dataTables_length select').addClass('form-control');
  }

  $(function () {
    $('table.js-datatable').each(function () { iniciar($(this)); });
  });
  $(document).on('sistta:content', function (_, root) {
    $(root).find('table.js-datatable').each(function () { iniciar($(this)); });
  });
})(window.jQuery);
