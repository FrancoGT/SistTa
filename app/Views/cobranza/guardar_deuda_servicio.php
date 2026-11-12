<?php
if (service('request')->isAJAX() && service('request')->getHeaderLine('X-Sistta-Modal') === '1') {
    service('response')->setHeader('X-CSRF-Name', csrf_token());
    service('response')->setHeader('X-CSRF-Hash', csrf_hash());
    echo '<div class="alert alert-success" role="status" data-form-success>Deuda de Servicio Guardada Exitosamente</div>';
    return;
}
?>
<?php
	echo ("<script LANGUAGE='JavaScript'>
		window.alert('Deuda de Servicio Guardada Exitosamente');
		window.location.href='index';
		</script>");
?>
