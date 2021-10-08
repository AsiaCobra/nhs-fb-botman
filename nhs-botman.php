<?php
/**
 * Plugin Name: NHS Facebook Botman
 * Plugin URI: https://github.com/asiacobra/nhs-fb-botman
 * Description: Facebook Bot for WordPress powered by Nayhtetsoe.
 * Author: NayHtetSoe
 * Author URI: https://github.com/asiacobra/nhs-fb-botman
 * Version: 0.1
 * Text Domain: nhs-fb-botman
 *
 * @author 		Nayhtetsoe
 * @version		0.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


define("NHSBOTMAN_PLUGIN_DIR", __file__);
define("NHSBOTMAN_PLUGIN_BASE", dirname(__file__));
define("NHSBOTMAN_PLUGIN_URL", plugin_dir_url(NHSBOTMAN_PLUGIN_DIR));

load_plugin_textdomain(
    "nhs-fb-botman",
    false,
    dirname( plugin_basename( __FILE__ ) ).'/languages'
);

define("NHSBOTMAN_PLUGIN_JS_DIR", NHSBOTMAN_PLUGIN_URL."assets/js/");
define("NHSBOTMAN_PLUGIN_CSS_DIR", NHSBOTMAN_PLUGIN_URL . "assets/css/");
define("NHSBOTMAN_PLUGIN_INCLUDE_DIR", NHSBOTMAN_PLUGIN_BASE . "/includes/");


require_once NHSBOTMAN_PLUGIN_BASE . '/vendor/autoload.php';
// require_once NHSBOTMAN_PLUGIN_BASE . '/lib/class.settings-api.php';
require_once NHSBOTMAN_PLUGIN_BASE . '/includes/admin-fbsetting.php';
require_once NHSBOTMAN_PLUGIN_BASE . '/includes/add-template.php';
// require_once NHSBOTMAN_PLUGIN_BASE . '/wc-endpoint-function.php';
 



 
/**
* Get the value of a settings field
*
* @param string $option settings field name
* @param string $section the section name this field belongs to
* @param string $default default text if it's not found
* @return mixed
*/
function my_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
    return $options[$option];
    }
 
    return $default;
}

?>
