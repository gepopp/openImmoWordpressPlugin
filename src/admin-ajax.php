<?php

add_action( 'wp_ajax_test_ftp_connection', function () {

	if ( wp_verify_nonce( $_POST['nonce'], 'test_ftp_connection' ) !== 1 ) {
		wp_die( 'Hackerschutz fehlgeschlagen, bitte die Seite neu laden.', 404 );
	}

	$data = [
		'hgpoi_ftp_connection_type' => sanitize_text_field( $_POST['data']['type'] ),
		'hgpoi_ftp_host'            => sanitize_text_field( $_POST['data']['host'] ),
		'hgpoi_ftp_user'            => sanitize_text_field( $_POST['data']['user'] ),
		'hgpoi_ftp_pass'            => sanitize_text_field( $_POST['data']['pass'] ),
		'hgpoi_ftp_ssh_key'         => sanitize_textarea_field( $_POST['data']['key'] ),
		'hgpoi_ftp_directory'       => '',
	];

	update_option( 'hgpoi_ftp_settings', $data, true);

	try {
		$ftp = new \HGPOIClasses\FTPConnection();
		wp_die( 'Verbindung erfolgreich.' );
	} catch ( \Exception $e ) {
		wp_die( $e->getMessage(), 400 );
	}


} );

add_action( 'wp_ajax_listFtpDir', function () {

	$data = get_option('hgpoi_ftp_settings');
	$classname = 'HGPOIClasses\\' . strtoupper($data['hgpoi_ftp_connection_type']) . 'Connection';
	$ftp = new $classname();
	wp_die(var_dump($ftp->vagrant (sanitize_text_field($_POST['path']))));



});