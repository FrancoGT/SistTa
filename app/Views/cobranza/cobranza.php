<h1>Cobranzas</h1>
<br>
<?php
	use Carbon\Carbon;
	if (!isset($value))
	{
		$value = '';
	}
?>
<form data-async-form action="<?= base_url() ?>index.php/cobranza/buscar_cobranza_detalle_afiliado" method ="POST">
<?= csrf_field() ?>
    <table class='table table-bordered table-hover'>
			<tr>
				<th>Entidad</th>
				<th></th>
				<th></th>
				<th>Buscar</th>
			</tr>
			<tr>
		    <td width="68" height="59"> Afiliado</td>
		    <td width="149"><label for="bus_dni_afi"></label>
		    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required name="bus_dni_afi" id="bus_dni_afi" value='<?= esc($value) ?>' placeholder="Buscar por dni"/></td>
		    <td width="281">
		    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required name="bus_cod_afi" id="bus_cod_afi" value='<?= esc($value) ?>' placeholder="Buscar por codigo"/></td>
		    <td width="205">
		    <input name="info_afiliado" id="info_afiliado" type="hidden" value="" />
		<button name="bus_afiliado" id="bus_afiliado" type="submit" class="btn btn-primary">
		  <i class="fa fa-search"></i>
		</button>
	</td>
	</div>
    </div>
  </tr>
  </form>

<form data-async-form action="<?= base_url() ?>index.php/cobranza/buscar_cobranza_detalle_conductor" method ="POST">
<?= csrf_field() ?>
  <tr>
    <td>Conductor</td>
    <div align="center">
    <td><input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required="Llena al menos un campo antes de buscar" name="bus_dni_con" id="bus_dni_con" value='<?= esc($value) ?>' placeholder="Buscar por dni"/></td>
    <td><input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required="Llena al menos un campo antes de buscar" name="bus_cod_con" id="bus_cod_con" value='<?= esc($value) ?>' placeholder="Buscar por codigo"/></td>
		<td width="205">
    <input name="info_conductor" id="info_conductor" type="hidden" value="" />
			<button name="bus_conductor" id="bus_conductor" type="submit" class="btn btn-primary">
			  <i class="fa fa-search"></i>
			</button>
		</td>
    </div>
  </tr>
</form>
<form data-async-form action="<?= base_url() ?>index.php/cobranza/buscar_cobranza_detalle_vehiculo" method ="POST">
<?= csrf_field() ?>
  <tr>
		<tr>
    <td>Veh&iacute;culo</td>
    <div align="center">
    <td><input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required="Llena al menos un campo antes de buscar" name="bus_placa_vehi" id="bus_placa_vehi" value='<?= esc($value) ?>' placeholder="Buscar por placa"/></td>
    <td><input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required="Llena al menos un campo antes de buscar" name="bus_cod_vehi" id="bus_cod_vehi" value='<?= esc($value) ?>' placeholder="Buscar por codigo"/></td>
		<td width="205">
    <input name="info_vehiculo" id="info_vehiculo" type="hidden" value="" />
			<button name="bus_vehiculo" id="bus_vehiculo" type="submit" class="btn btn-primary">
			  <i class="fa fa-search"></i>
			</button>
		</td>
    </div>
  </tr>
</table>
</form>

<script>
  function updateInput(inputElem, criterion, info, disabledElems) 
  {
    info = info.trim();
    // Solo se bloquean los otros campos/botones; el campo en uso debe seguir editable
    disabledElems.forEach(function(disabledElem) {
      disabledElem.disabled = info != "";
    });
    // El hidden de cada formulario es info_afiliado / info_conductor / info_vehiculo
    var hidden = inputElem.form.querySelector('input[type="hidden"][name^="info_"]');
    hidden.value = info != "" ? info.concat(criterion) : "";
  }

  var inputElems = [  {elem: document.getElementById("bus_dni_afi"), criterion: "~documento", disabledElems: [document.getElementById("bus_cod_afi"), document.getElementById("bus_dni_con"), document.getElementById("bus_cod_con"), document.getElementById("bus_placa_vehi"), document.getElementById("bus_cod_vehi"), document.getElementById("bus_conductor"), document.getElementById("bus_vehiculo")]},
    {elem: document.getElementById("bus_cod_afi"), criterion: "~cod_afiliado", disabledElems: [document.getElementById("bus_dni_afi"), document.getElementById("bus_dni_con"), document.getElementById("bus_cod_con"), document.getElementById("bus_placa_vehi"), document.getElementById("bus_cod_vehi"), document.getElementById("bus_conductor"), document.getElementById("bus_vehiculo")]},
    {elem: document.getElementById("bus_dni_con"), criterion: "~documento", disabledElems: [document.getElementById("bus_dni_afi"), document.getElementById("bus_cod_afi"), document.getElementById("bus_cod_con"), document.getElementById("bus_placa_vehi"), document.getElementById("bus_cod_vehi"), document.getElementById("bus_afiliado"), document.getElementById("bus_vehiculo")]},
    {elem: document.getElementById("bus_cod_con"), criterion: "~cod_conductor", disabledElems: [document.getElementById("bus_dni_afi"), document.getElementById("bus_cod_afi"), document.getElementById("bus_dni_con"), document.getElementById("bus_placa_vehi"), document.getElementById("bus_cod_vehi"), document.getElementById("bus_afiliado"), document.getElementById("bus_vehiculo")]},
    {elem: document.getElementById("bus_placa_vehi"), criterion: "~placa", disabledElems: [document.getElementById("bus_dni_afi"), document.getElementById("bus_cod_afi"), document.getElementById("bus_dni_con"), document.getElementById("bus_cod_con"), document.getElementById("bus_cod_vehi"), document.getElementById("bus_afiliado"), document.getElementById("bus_conductor")]},
    {elem: document.getElementById("bus_cod_vehi"), criterion: "~cod_vehiculo", disabledElems: [document.getElementById("bus_dni_afi"), document.getElementById("bus_cod_afi"), document.getElementById("bus_dni_con"), document.getElementById("bus_cod_con"), document.getElementById("bus_placa_vehi"), document.getElementById("bus_afiliado"), document.getElementById("bus_conductor")]}
  ];

  inputElems.forEach(function(inputObj) 
  {
    inputObj.elem.oninput = function() 
    {
      updateInput(inputObj.elem, inputObj.criterion, inputObj.elem.value, inputObj.disabledElems);
    }
  });

</script>
