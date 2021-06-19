<?php


namespace HGPOIClasses;


class FTPConnection {

	protected $settings;
	protected $connection;


	public function __construct() {

		$this->settings = get_option( 'hgpoi_ftp_settings' );

		$this->connect();

		if ( ! $this->connection || ! $this->login() ) {
			throw new \Exception( 'Fehler beim FTP Verbindungsaufbau' );
		}
	}

	public function connect() {

		$this->connection = ftp_connect( $this->settings['hgpoi_ftp_host'] );
	}

	public function login() {

		$login = ftp_login( $this->connection, $this->settings['hgpoi_ftp_user'], $this->settings['hgpoi_ftp_pass'] );
		ftp_pasv( $this->connection, true );

		return $login;
	}


	public function listDirs( $basedir = '/' ) {

		$dir = ftp_mlsd($this->connection, $basedir );

		$directories = array_filter($dir, function ($element){
			if($element['type'] == 'dir'){
				return $element['name'];
			}
		});

		return $directories;
	}


}