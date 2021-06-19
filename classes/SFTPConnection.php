<?php


namespace HGPOIClasses;


use phpseclib3\Net\SFTP;

class SFTPConnection extends FTPConnection {


	public function __construct() {
		parent::__construct();
	}


	public function connect() {
		$this->connection = new SFTP( $this->settings['hgpoi_ftp_host'] );
	}

	public function login() {
		if(!empty($this->settings['hgpoi_ftp_pass'])){

			$key = \phpseclib3\Crypt\PublicKeyLoader::load($this->settings['hgpoi_ftp_ssh_key'], $this->settings['hgpoi_ftp_pass']);
		}else{
			$key = $this->settings['hgpoi_ftp_ssh_key'];
		}
		return $this->connection->login( $this->settings['hgpoi_ftp_user'], $key );
	}

}