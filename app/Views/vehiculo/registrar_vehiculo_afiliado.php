<h1>Registrar Nuevo Veh&iacute;culo</h1>
<br>
  <script language="javascript">
    function Copiar_CV()
    {
      document.getElementById("cod_vehiculo_BA").value = document.getElementById("cod_vehiculo").value;
      document.getElementById("cod_vehiculo_BC").value = document.getElementById("cod_vehiculo").value;
      document.getElementById("cod_vehiculo_G").value = document.getElementById("cod_vehiculo").value;
    }
    function Copiar_DA()
    {
      document.getElementById("data_afiliado_BA").value = document.getElementById("data_afiliado").value;
      document.getElementById("data_afiliado_BC").value = document.getElementById("data_afiliado").value;
      document.getElementById("data_afiliado_G").value = document.getElementById("data_afiliado").value;
    }
    function Copiar_IP_A()
    {
      document.getElementById("id_person_afiliado_BA").value = document.getElementById("id_person_afiliado").value;
      document.getElementById("id_person_afiliado_BC").value = document.getElementById("id_person_afiliado").value;
      document.getElementById("id_person_afiliado_G").value = document.getElementById("id_person_afiliado").value;
    }
    function Copiar_Placa()
    {
      document.getElementById("placa_BA").value = document.getElementById("placa").value;
      document.getElementById("placa_BC").value = document.getElementById("placa").value;
      document.getElementById("placa_G").value = document.getElementById("placa").value;
    }
    function Copiar_Estado()
    {
      if(document.seleccionar_estado.estado[0].checked)
      {
        document.getElementById("estado_BA").value = document.seleccionar_estado.estado[0].value;
        document.getElementById("estado_BC").value = document.seleccionar_estado.estado[0].value;
        document.getElementById("estado_G").value = document.seleccionar_estado.estado[0].value;
      }
      if(document.seleccionar_estado.estado[1].checked)
      {
        document.getElementById("estado_BA").value = document.seleccionar_estado.estado[1].value;
        document.getElementById("estado_BC").value = document.seleccionar_estado.estado[1].value;
        document.getElementById("estado_G").value = document.seleccionar_estado.estado[1].value;
      }
    }
    function Copiar_DC()
    {
      document.getElementById("data_conductor_BA").value = document.getElementById("data_conductor").value;
      document.getElementById("data_conductor_BC").value = document.getElementById("data_conductor").value;
      document.getElementById("data_conductor_G").value = document.getElementById("data_conductor").value;
    }
    function Copiar_DNI_C()
    {
      document.getElementById("id_person_conductor_BA").value = document.getElementById("id_person_conductor").value;
      document.getElementById("id_person_conductor_BC").value = document.getElementById("id_person_conductor").value;
      document.getElementById("id_person_conductor_G").value = document.getElementById("id_person_conductor").value;
    }
  </script>
<p>
<?php
  if (!isset($cod_vehiculo))
  {
      $cod_vehiculo = "";
  }
  if (!isset($doc_person_afiliado))
  {
      $doc_person_afiliado = '';
  }
  if (!isset($doc_person_conductor))
  {
      $doc_person_conductor = '';
  }
  if (!isset($data_afiliado))
  {
      $data_afiliado = "";
  }
  if (!isset($id_person_afiliado))
  {
      $id_person_afiliado = "";
  }
  if (!isset($placa))
  {
      $placa = "";
  }
  if (!isset($data_conductor))
  {
      $data_conductor = "";
  }
  if (!isset($id_person_conductor))
  {
      $id_person_conductor = "";
  }
  if (isset($id_person_afiliado))
  {
    $doc_person_afiliado = $ci->getDocumento($id_person_afiliado);
  }
  if (isset($id_person_conductor))
  {
    $doc_person_conductor = $ci->getDocumento($id_person_conductor);
  }
?>
  <p>C&oacute;digo</p>
    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="cod_vehiculo" name="cod_vehiculo" placeholder="C&oacute;digo de Vehiculo" value="<?= esc($cod_vehiculo) ?>" onkeyup="Copiar_CV();">
  <br>
  <p>Afiliado</p>
  <?php
    echo view('tablas/ficha_afiliado', ['filas' => $ci->ficha_afiliado($id_person_afiliado)]);
  ?>
      <input autocomplete="off" type="hidden" id="cod_vehiculo_BA" name="cod_vehiculo_BA" value="<?= esc($cod_vehiculo) ?>">
      <input autocomplete="off" type="hidden" id="data_afiliado_BA" name="data_afiliado_BA" value="<?= esc($data_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="id_person_afiliado_BA" name="id_person_afiliado_BA" value="<?= esc($id_person_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="placa_BA" name="placa_BA" value="<?= esc($placa) ?>">
      <input autocomplete="off" type="hidden" id="estado_BA" name="estado_BA" value="<?php if (isset($estado_vehiculo)) echo esc($estado_vehiculo); ?>">
      <input autocomplete="off" type="hidden" id="data_conductor_BA" name="data_conductor_BA" value="<?= esc($data_conductor) ?>">
      <input autocomplete="off" type="hidden" id="id_person_conductor_BA" name="id_person_conductor_BA" value="<?= esc($id_person_conductor) ?>">
  <form name="seleccionar_estado" action="<?= base_url() ?>index.php/vehiculo/encontrar_conductor" method="POST">
<?= csrf_field() ?>
  <h3>
    <?php
      if (isset($mensaje))
      {
        if ($mensaje != '')
          echo " ".$mensaje." ";
      }
    ?>
  </h3>
<p>Placa:</p>
<br>
  <input autocomplete="off" onkeypress="return AlphaNumeric(event)" type="text" name="placa" id="placa" placeholder="Placa del Veh&iacute;culo" value="<?= esc($placa) ?>" required onkeyup="Copiar_Placa();" />
  <br>
</p>
<p>Estado
    <p>
      <?php
        if (isset($estado_vehiculo))
        {
          if ($estado_vehiculo == '1')
          {
              echo 'Por precauci&oacute;n vuelva asignar el Estado del Veh&iacute;culo <br>';
              echo '&Uacute;ltimo Estado Seleccionado: Activo <br>';
          }
          if ($estado_vehiculo == '0')
          {
              echo 'Por precauci&oacute;n vuelva asignar el Estado del Veh&iacute;culo <br>';
              echo 'Ultimo Estado Seleccionado: Inactivo <br>';
          }
        }
      ?>
      <input autocomplete="off" type="radio" required name="estado" value="1"
        <?php
          if (!isset($estado_vehiculo))
          {
            $estado_vehiculo = '';
          }
          if ($estado_vehiculo == '1')
            echo 'ckecked';
        ?>
        onClick="Copiar_Estado();"
        />
        Activo</p>
    <p>
      <input autocomplete="off" type="radio" required name="estado" value="0"
        <?php
          if (!isset($estado_vehiculo))
          {
            $estado_vehiculo = '';
          }
          if ($estado_vehiculo == '0')
            echo 'ckecked';
        ?>
        onClick="Copiar_Estado();"
       />
      Inactivo</p>
      <p>Conductor</p>
        <?php
            if ($doc_person_conductor == '')
             echo "<p class='alert alert-danger'>No has asignado un Conductor a&uacute;n</p>";
            else
            {
              echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]);
            }
        ?>
        <p>Para Buscar/Asignar un conductor Ingrese el DNI, Brevete o C&oacute;digo y de clic en: Buscar Conductor</p>
      <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" id="data_conductor_BC" name="data_conductor_BC" value="<?= esc($data_conductor) ?>">
      <input autocomplete="off" type="hidden" id="cod_vehiculo_BC" name="cod_vehiculo_BC" value="<?= esc($cod_vehiculo) ?>">
      <input autocomplete="off" type="hidden" id="data_afiliado_BC" name="data_afiliado_BC" value="<?= esc($data_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="id_person_afiliado_BC" name="id_person_afiliado_BC" value="<?= esc($id_person_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="id_person_conductor_BC" name="id_person_conductor_BC" value="<?= esc($id_person_conductor) ?>">
      <input autocomplete="off" type="hidden" id="placa_BC" name="placa_BC" value="<?= esc($placa) ?>">
      <input autocomplete="off" type="hidden" id="estado_BC" name="estado_BC" value="<?php if (isset($estado_vehiculo)) echo esc($estado_vehiculo); ?>" />
      <input autocomplete="off" type="submit" name="buscar_conductor" id="buscar_conductor" value="Buscar Conductor" />

  <br>
<p>
  <?php
      if (isset($mensaje2))
      {
        if ($mensaje2 != '')
          echo " ".$mensaje2." ";
      }
    ?>
  <br>
</p>
</form>
<form action="<?= base_url() ?>index.php/vehiculo/recepcionar_vehiculo" method="POST">
<?= csrf_field() ?>
      <input autocomplete="off" type="hidden" id="cod_vehiculo_G" name="cod_vehiculo_G" value="<?= esc($cod_vehiculo) ?>">
      <input autocomplete="off" type="hidden" id="data_afiliado_G" name="data_afiliado_G" value="<?= esc($data_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="id_person_afiliado_G" name="id_person_afiliado_G" value="<?= esc($id_person_afiliado) ?>">
      <input autocomplete="off" type="hidden" id="placa_G" name="placa_G" value="<?= esc($placa) ?>">
      <input autocomplete="off" type="hidden" id="estado_G" name="estado_G" value="<?php if (isset($estado_vehiculo)) echo esc($estado_vehiculo); ?>">
      <input autocomplete="off" type="hidden" id="data_conductor_G" name="data_conductor_G" value="<?= esc($data_conductor) ?>">
      <input autocomplete="off" type="hidden" id="id_person_conductor_G" name="id_person_conductor_G" value="<?= esc($id_person_conductor) ?>">
      <input autocomplete="off" type="submit" name="guardar_vehiculo" id="guardar_vehiculo" value="Guardar" />
</form>
