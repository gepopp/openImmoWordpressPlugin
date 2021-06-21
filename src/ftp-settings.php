<?php

namespace hgp_open_immo;

add_action( 'admin_init', 'hgp_open_immo\hgpoi_ftp_settings_section' );
function hgpoi_ftp_settings_section() {

	if ( false == get_option( 'hgpoi_ftp_settings' ) ) {
		add_option( 'hgpoi_ftp_settings' );
	} // end if

	add_settings_section(
		'hgpoi_openimmo_ftp_settings_section',         // ID used to identify this section and with which to register options
		__( 'FTP Verbindung', 'hgp-openimmo-importer' ),                  // Title to be displayed on the administration page
		'hgp_open_immo\hgpoi_render_ftp_settings_section_description', // Callback used to render the description of the section
		'hgpoi_ftp_settings'                           // Page on which to add this section of options
	);
}

function hgpoi_render_ftp_settings_section_description() {

	echo '<p>';
	_e( 'Bitte geben Sie die Verbindungdaten zum dem FTP Server ein von dem Ihre Objekte Importiert werden sollen. Die gleichen Daten müssen in Ihrer Maklersoftware Hinterleget werden.' );
	echo '</p>';

}

add_action( 'admin_init', 'hgp_open_immo\hgpoi_add_settings_to_ftp_section' );
function hgpoi_add_settings_to_ftp_section() {

	add_settings_field(
		'hgpoi_ftp_connection_type',
		'Verbindungsart',
		'hgp_open_immo\hgpoi_ftp_connection_type_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Bitte wählen Sie welche Art der FTP Verbindung Sie verwenden möchten.' ),
		]
	);

	add_settings_field(
		'hgpoi_ftp_host',
		'Host',
		'hgp_open_immo\hgpoi_ftp_host_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Bitte geben Sie die IP Adresse oder URL des FTP-Servers ein.' ),
		]
	);

	add_settings_field(
		'hgpoi_ftp_user',
		'FTP Username',
		'hgp_open_immo\hgpoi_ftp_user_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Bitte geben Sie den FTP Usernamen ein.' ),
		]
	);

	add_settings_field(
		'hgpoi_ftp_user_pass',
		'FTP Passwort oder Passphrase',
		'hgp_open_immo\hgpoi_ftp_pass_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Bitte geben Sie das FTP-Passwort oder die Passphrase für den SSH Schlüssel ein.' ),
		]
	);


	add_settings_field(
		'hgpoi_ftp_ssh_key',
		'FTP Schlüssel',
		'hgp_open_immo\hgpoi_ftp_ssh_key_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Geben Sie den öffetnlichen SSH Schlüssel der SFTP Verbindung ein.' ),
			__( 'Wird nur für SFTP Verbindungen gebraucht.' ),
		]
	);


	add_settings_field(
		'hgpoi_ftp_test',
		'Verbindung testen:',
		'hgp_open_immo\hgpoi_ftp_test_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
	);


	add_settings_field(
		'hgpoi_ftp_directory',
		'FTP Pfad zu den OpenImmo Dateien',
		'hgp_open_immo\hgpoi_ftp_directory_callback',
		'hgpoi_ftp_settings',
		'hgpoi_openimmo_ftp_settings_section',
		[
			__( 'Bitte geben Sie den Pfad zu dem Verzeichnis der OpenImmo Dateien ein.' ),
		]
	);


	register_setting(
		'hgpoi_ftp_settings',
		'hgpoi_ftp_settings'
	);

}


function hgpoi_ftp_connection_type_callback( $args ) {


	$html = '<select id="hgpoi_ftp_connection_type" name="hgpoi_ftp_settings[hgpoi_ftp_connection_type]" x-model="connetcionValues.type"/>';
	$html .= '<option value="sftp">SFTP mit SSL Schlüssel</option>';
	$html .= '<option value="ftp">FTP</option>';
	$html .= '</select>';
	$html .= '<label for="hgpoi_ftp_connection_type"> ' . $args[0] . '</label>';

	echo $html;

}

function hgpoi_ftp_host_callback( $args ) {

	$html = '<input type="text" name="hgpoi_ftp_settings[hgpoi_ftp_host]" x-model.debounce.500="connetcionValues.host">';
	$html .= '<label for="hgpoi_ftp_connection_type"> ' . $args[0] . '</label>';

	echo $html;
}

function hgpoi_ftp_user_callback( $args ) {

	$html = '<input id="hgpoi_ftp_user" type="text" name="hgpoi_ftp_settings[hgpoi_ftp_user]" x-model.debounce.500="connetcionValues.user">';
	$html .= '<label for="hgpoi_ftp_user"> ' . $args[0] . '</label>';

	echo $html;
}

function hgpoi_ftp_pass_callback( $args ) {

	$html = '<input id="hgpoi_ftp_pass" type="text" name="hgpoi_ftp_settings[hgpoi_ftp_pass]" x-model.debounce.500="connetcionValues.pass">';
	$html .= '<label for="hgpoi_ftp_pass"> ' . $args[0] . '</label>';

	echo $html;
}

function hgpoi_ftp_ssh_key_callback( $args ) {


	$html = '<textarea id="hgpoi_ftp_ssh_keyy" type="text" name="hgpoi_ftp_settings[hgpoi_ftp_ssh_key]" x-model="connetcionValues.key" x-show="connetcionValues.type == \'sftp\'"></textarea>';
	$html .= '<label for="hgpoi_ftp_ssh_key" x-text="connetcionValues.type == \'ftp\' ? \'' . $args[1] . '\' : \'' . $args[0] . '\'"></label>';
	echo $html;
}

function hgpoi_ftp_test_callback() {
	ob_start();
	?>

    <div class="" style="margin: 15px 0">
        <h3 style="color: #286B2B" x-show="connected && !connection_error" x-html="connected"></h3>
        <h3 style="color: #AB3A38" x-show="connected && connection_error" x-html="connected"></h3>
    </div>
    <button @click.prevent="testFtp()" :disabled="!connectable" x-show="!connected">Verbindung testen</button>

	<?php
	$html = ob_get_clean();
	echo $html;
}

function hgpoi_ftp_directory_callback( $args ) {

	ob_start();
	?>
    <label for="hgpoi_ftp_directory" x-text="!connection_error ? 'Bitte testen Sie zuerst erfolgreich die FTP Verbindung ' : '<?php echo $args[0] ?>'" x-show="!connected"></label>
    <div x-show="connected">
        <h5>Bitte einen Pfad wählen:</h5>
        <ul>
            <template x-for="dir in dirs">
                <li x-text="dir.name" @click="selectDir(dir.name)" style="cursor: pointer"></li>
            </template>
        </ul>

    </div>
    <h5>Gewählter Pfad:</h5>
    <p>
        <strong x-text="path"></strong>
        <small @click="clearPath()" style="cursor: pointer" x-show="path != ''">löschen</small>
    </p>
    <input id="hgpoi_ftp_directory" type="hidden" x-model="path" name="hgpoi_ftp_settings[hgpoi_ftp_directory]">

	<?php
	echo ob_get_clean();
}