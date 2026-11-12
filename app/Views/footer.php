      </div><!-- /.page-card -->
    </section>
  </div><!-- /.content-wrapper -->

  <footer class="main-footer">
    <span>&copy; 2019 <a href="http://www.vitplanet.com/" target="_blank" rel="noopener">Vitplanet SRL</a>. Todos los derechos reservados.</span>
    <span>Versi&oacute;n 1.0</span>
  </footer>
</div><!-- /.wrapper -->

<?php $assets = base_url('assets') . '/'; ?>
<script src="<?= $assets ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= $assets ?>dist/js/adminlte.min.js"></script>
<script src="<?= $assets ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $assets ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?= $assets ?>js/sistta-datatables.js"></script>
<div class="modal fade" id="sistta-form-modal" tabindex="-1" role="dialog" aria-labelledby="sistta-form-title">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="sistta-form-title">Formulario</h2>
      </div>
      <div class="modal-body" aria-live="polite"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
(function ($) {
  'use strict';
  var $modal = $('#sistta-form-modal');
  var $body = $modal.find('.modal-body');
  var busy = false;
  var trigger = null;
  var refreshSource = {url: window.location.href, method: 'GET'};
  var returnSource = null;
  var csrf = {};
  var headers = {'X-Sistta-Modal': '1'};

  function tokens(xhr) {
    if (!xhr || !xhr.getResponseHeader) { return; }
    var name = xhr.getResponseHeader('X-CSRF-Name');
    var hash = xhr.getResponseHeader('X-CSRF-Hash');
    if (!name || !hash) { return; }
    csrf[name] = hash;
    $('input[type="hidden"]').filter(function () { return this.name === name; }).val(hash);
  }

  function error(message) {
    $body.children('.sistta-request-error').remove();
    $('<div class="alert alert-danger sistta-request-error" role="alert" tabindex="-1"/>')
      .text(message).prependTo($body).focus();
  }

  // DOMParser no ejecuta los scripts del documento recibido. Solo se inserta su contenido.
  function content(html) {
    var doc = new DOMParser().parseFromString(html, 'text/html');
    var card = doc.querySelector('.page-card');
    var success = doc.querySelector('[data-form-success]');
    var messages = [];
    doc.querySelectorAll('script').forEach(function (script) {
      var match = /(?:window\.)?alert\s*\(\s*(['"])([\s\S]*?)\1\s*\)/.exec(script.textContent);
      if (match) { messages.push(match[2]); }
    });
    return {html: card ? card.innerHTML : (success ? success.outerHTML : null),
      success: success, error: messages.join(' ')};
  }

  function request(source) {
    var data = source.data;
    if (data instanceof FormData) {
      $.each(csrf, function (name, hash) { data.set(name, hash); });
    } else if (source.method === 'POST') {
      data = $.extend({}, data, csrf);
    }
    return $.ajax({url: source.url, type: source.method, data: data,
      processData: !(data instanceof FormData),
      contentType: data instanceof FormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
      headers: headers, dataType: 'html'}).always(function (_, __, xhr) {
        tokens(xhr && xhr.getResponseHeader ? xhr : _);
      });
  }

  function render(html) {
    $body.html(html);
    var $heading = $body.find('h1,h2').first();
    var title = $heading.text();
    $heading.addClass('sistta-modal-heading');
    $('#sistta-form-title').text(title || 'Formulario');
    $body.find('input:not([type="hidden"]):not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]),select,textarea').addClass('form-control');
    $body.find(':submit').not('.btn').addClass('btn btn-primary');
    $body.find('table').not('.js-datatable').each(function () {
      if (!$(this).parent().hasClass('table-responsive')) { $(this).wrap('<div class="table-responsive"/>'); }
    });
    $(document).trigger('sistta:content', [$body]);
    $body.find('input:not([type="hidden"]),select,textarea,button').filter(':visible').first().focus();
  }

  function refresh(message) {
    request(refreshSource).done(function (html) {
      var result = content(html);
      if (result.html === null || result.error) {
        error('Se guardaron los datos, pero no se pudo actualizar el listado. Actualiza la pantalla.');
        return;
      }
      $('.page-card').html(result.html);
      $('<div class="alert alert-success" role="status"/>').text(message).prependTo('.page-card');
      $(document).trigger('sistta:content', [$('.page-card')]);
    }).fail(function () {
      error('Se guardaron los datos, pero no se pudo actualizar el listado. Actualiza la pantalla.');
    });
  }

  function load(source) {
    if (busy) { return; }
    busy = true;
    $body.attr('aria-busy', 'true');
    var $buttons = $body.find(':submit').filter(':enabled').prop('disabled', true);
    $modal.find('[data-dismiss="modal"]').prop('disabled', true);
    request(source).done(function (html) {
      var result = content(html);
      if (result.error) {
        error(result.error);
        // Algunas busquedas heredadas devuelven solo una alerta, sin un campo CSRF nuevo.
        request({url: window.location.href, method: 'GET'});
        return;
      }
      if (result.html === null) {
        error('No se pudo cargar el formulario. Comprueba que tu sesion siga activa e intenta de nuevo.');
        return;
      }
      if (result.success) {
        render(result.html);
        $('#sistta-form-title').text('Operacion completada');
        refresh(result.success.textContent.trim());
      } else {
        render(result.html);
        if (source.data instanceof FormData) {
          $body.find('form').filter(function () { return this.action === source.url; })
            .find('input[type="radio"],input[type="checkbox"],select').each(function () {
              var values = source.data.getAll(this.name);
              if (this.tagName === 'SELECT') { $(this).val(values); }
              else { this.checked = values.indexOf(this.value) !== -1; }
            });
        }
        // La siguiente operacion puede volver al detalle que se estaba consultando.
        returnSource = source;
      }
    }).fail(function (xhr) {
      error(xhr.status === 403 ? 'La solicitud fue rechazada. Vuelve a abrir el formulario para renovar la sesion.' :
        'No se pudo completar la solicitud. Tus datos siguen en el formulario; intenta de nuevo.');
    }).always(function () {
      busy = false;
      $buttons.prop('disabled', false);
      $modal.find('[data-dismiss="modal"]').prop('disabled', false);
      $body.removeAttr('aria-busy');
    });
  }

  $(document).on('click.sisttaForms', 'a[data-form-modal]', function (event) {
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.which > 1) { return; }
    event.preventDefault();
    if (busy) { return; }
    if (!$(this).closest($modal).length) {
      trigger = this;
      returnSource = null;
      render('<p>Cargando formulario...</p>');
    } else if (returnSource && /buscar_cobranza_detalle_/.test(returnSource.url)) {
      refreshSource = returnSource;
    }
    $modal.modal({backdrop: 'static', keyboard: false});
    load({url: this.href, method: 'GET'});
  });

  $(document).on('submit.sisttaForms', 'form[data-async-form]', function (event) {
    event.preventDefault();
    if (busy) { return; }
    var button = event.originalEvent && event.originalEvent.submitter;
    if (!button) { button = $(this).data('sistta-submitter'); }
    $(this).removeData('sistta-submitter');
    if (!(button && button.formNoValidate) && !this.reportValidity()) { return; }
    if (this.hasAttribute('data-vehicle-form')) {
      var vehicleForm = this;
      $(this).find('[data-vehicle-field]').each(function () {
        if (this.type === 'radio' && !this.checked) { return; }
        var field = this;
        ['BA', 'BC', 'G'].forEach(function (suffix) {
          var target = vehicleForm.elements.namedItem(field.name + '_' + suffix);
          if (target) { target.value = field.value; }
        });
      });
    }
    var data = new FormData(this);
    if (button && button.name) { data.append(button.name, button.value); }
    var source = {url: button && button.getAttribute('formaction') ? button.formAction : this.action,
      method: (this.method || 'POST').toUpperCase(), data: data};
    if (!$(this).closest($modal).length) {
      trigger = this;
      returnSource = null;
      render('<p>Procesando formulario...</p>');
    }
    $modal.modal({backdrop: 'static', keyboard: false});
    load(source);
  });
  $(document).on('click.sisttaForms', 'form[data-async-form] :submit', function () {
    $(this.form).data('sistta-submitter', this);
  });
  $modal.on('hide.bs.modal', function (event) { if (busy) { event.preventDefault(); } });
  $modal.on('hidden.bs.modal', function () {
    $body.empty();
    returnSource = null;
    refreshSource = {url: window.location.href, method: 'GET'};
    if (trigger && document.contains(trigger)) { $(trigger).focus(); }
  });
})(window.jQuery);
</script>
</body>
</html>
