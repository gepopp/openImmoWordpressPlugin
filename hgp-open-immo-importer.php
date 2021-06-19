<?php
/**
 * Plugin Name:       OpenImmo Import
 * Plugin URI:        https://poppgerhard.at
 * Description:       Imports the OpenImmo-XML and attachmets from a given FTP Server into a custom post tye item.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gerhard Hubert Popp
 * Author URI:        https://poppgerhard.at/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hgp-openimmo-importer
 * Domain Path:       /languages
 */

namespace hgp_open_immo;

if( !function_exists('get_plugin_data') ){
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

$plugin_data = get_plugin_data( __FILE__ );

define('HGPOI_VERSION', $plugin_data['Version']);
define('HGPOI_DIR', __DIR__);

$loader = require_once( HGPOI_DIR . '/vendor/autoload.php' );
$loader->addPsr4('HGPOIClasses\\', __DIR__ . '/classes');

\A7\autoload(__DIR__ . '/src');
