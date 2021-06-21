<?php

namespace hgp_open_immo;

add_action( 'admin_menu', 'hgp_open_immo\hgpoi_add_ftp_settings_page' );
function hgpoi_add_ftp_settings_page() {

	add_menu_page(
		__( 'OpenImmo Import Einstellungen', 'hgp-openimmo-importer' ),
		__( 'OpenImmo', 'hgp-openimmo-importer' ),
		'administrator',
		'openimmo',
		'hgp_open_immo\hgpoi_display_main_settings_page',
		''
	);

	add_submenu_page(
		'openimmo',
		__( 'FTP Verbindung', 'hgp-openimmo-importer' ),
		__( 'FTP Verbindung', 'hgp-openimmo-importer' ),
		'administrator',
		'hgpoi_ftp_settings',                  // The unique ID - that is, the slug - for this menu item
		'hgp_open_immo\hgpoi_ftp_settings_page_display',// The name of the function to call when rendering the menu for this page
	);
}

function hgpoi_display_main_settings_page() {
}

function hgpoi_ftp_settings_page_display() {

	$value = get_option( 'hgpoi_ftp_settings' );

	?>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>

        var key = `<?php echo $value['hgpoi_ftp_ssh_key'] ?>`;

        function watchForm() {
            return {
                connetcionValues: {
                    type: '<?php echo $value['hgpoi_ftp_connection_type'] ?>',
                    host: '<?php echo $value['hgpoi_ftp_host'] ?>',
                    user: '<?php echo $value['hgpoi_ftp_user'] ?>',
                    pass: '<?php echo $value['hgpoi_ftp_pass'] ?>',
                    key: key,
                },
                path: '<?php echo ! empty( $value['hgpoi_ftp_directory'] ) ? $value['hgpoi_ftp_directory'] : ""  ?>',
                connectable: false,
                connected: false,
                connection_error: false,
                dirs: [],
                init() {

                    this.checkConnection();
                    for (const [key, value] of Object.entries(this.connetcionValues)) {
                        this.$watch(key, () => this.checkConnection());
                    }

                    this.$watch('path', () => this.getDir());

                },
                getDir() {
                    axios.post(ajaxurl, Qs.stringify({
                        action: 'listFtpDir',
                        path: this.path
                    })).then((rsp) => {
                        this.dirs = Object.values(rsp.data);
                    });
                },
                selectDir(dir) {
                    if (this.path != '') {
                        this.path += '/';
                    }
                    this.path += dir;
                    this.getDir();
                },
                clearPath(){
                    this.path = '';
                },
                checkConnection() {

                    this.connected = this.connection_error = false;

                    if (this.connetcionValues.type == 'ftp') {
                        if (this.connetcionValues.host != '' && this.connetcionValues.user != '' && this.connetcionValues.pass != '') {
                            this.connectable = true;
                            this.getDir();
                        } else {
                            this.connectable = false;
                        }
                    } else {
                        if (this.connetcionValues.host != '' && this.connetcionValues.user != '' && this.connetcionValues.key != '') {
                            this.connectable = true;
                        } else {
                            this.connectable = false;
                        }
                    }
                }
                ,
                testFtp() {

                    axios.post(ajaxurl, Qs.stringify({
                        action: 'test_ftp_connection',
                        nonce: '<?php echo wp_create_nonce( 'test_ftp_connection' )?>',
                        data: this.connetcionValues
                    }))
                        .then((rsp) => {
                            this.connected = rsp.data;
                        })
                        .catch((error) => {
                            this.connected = error.response.data;
                            this.connection_error = true;
                        })

                        .then(() => this.connectable = false);
                }
            }
        }
    </script>
    <!-- Create a header in the default WordPress 'wrap' container -->
    <div class="wrap" x-data="watchForm()" x-init="init">

        <!-- Add the icon to the page -->
        <h2>FTP Verbindungseinstellungen für den OpenImmo Import</h2>
        <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
		<?php settings_errors(); ?>
        <!-- Create the form that will be used to render our options -->
        <form method="post" action="options.php">
			<?php settings_fields( 'hgpoi_ftp_settings' ); ?>
			<?php do_settings_sections( 'hgpoi_ftp_settings' ); ?>
			<?php submit_button(); ?>
        </form>
    </div><!-- /.wrap -->


	<?php

}