<h1>Confirmar</h1>
<form method="POST" action="<?= base_url() ?>index.php/cobranza/restablecer_periodo">
<?= csrf_field() ?>
<div align="center"> <h3> <p>¿Est&aacute;s seguro(a) de restablecer el Periodo de Pago?</h3> </p>
</div>
<p>
  <div align="center"> <input type="submit" name="si" id="si" value="Si"/> </div>
</p>

<input name="id_periodo" type="hidden" id="id_periodo" value="<?= esc($id_periodo) ?>"/>
</form>

<form action="<?= base_url() ?>index.php/cobranza" method ="POST">
<?= csrf_field() ?>
  <div align="center"> <input type="submit" name="no" id="no" value="No" /> </div>
</form>
