<?php
if (service('request')->isAJAX() && service('request')->getHeaderLine('X-Sistta-Modal') === '1') {
    service('response')->setHeader('X-CSRF-Name', csrf_token());
    service('response')->setHeader('X-CSRF-Hash', csrf_hash());
    echo '<div class="alert alert-success" role="status" data-form-success>Propietario Eliminado</div>';
    return;
}
?>
<?php
	echo ("<script LANGUAGE='JavaScript'>
		window.alert('Propietario Eliminado');
		window.location.href='index';
		</script>");
?>
