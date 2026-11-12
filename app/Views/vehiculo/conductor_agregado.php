<?php
if (service('request')->isAJAX() && service('request')->getHeaderLine('X-Sistta-Modal') === '1') {
    service('response')->setHeader('X-CSRF-Name', csrf_token());
    service('response')->setHeader('X-CSRF-Hash', csrf_hash());
    echo '<div class="alert alert-success" role="status" data-form-success>Conductor Agregado</div>';
    return;
}
?>
<?php
	echo ("<script LANGUAGE='JavaScript'>
		window.alert('Conductor Agregado');
		window.location.href='index';
		</script>");
?>
