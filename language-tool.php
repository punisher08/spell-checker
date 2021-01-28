<?php
/**
 * @package Language Tool
 */
/*
Plugin Name: Language Tool
Description: Custom plug-in for Language Tool.
Version: 1.0.0
Author: Gene
*/

define( 'LANGTOOL_PLUGIN', __FILE__ );
define( 'LANGTOOL_PLUGIN_DIR',
	untrailingslashit( dirname( LANGTOOL_PLUGIN ) ) );

function languageTool(){
    ob_start();
    require_once LANGTOOL_PLUGIN_DIR . '/templates/langtool-textarea.php';
    $out = ob_get_clean();
    return $out;
}
add_shortcode('langtool', 'languageTool');