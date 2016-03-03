<?php
/**
 * Class RW_Blog_Umzug_Installation
 *
 * Autoloader for the plugin
 *
 * @package   RW Blog Umzug
 * @author    Joachim Happel
 * @license   GPL-2.0+
 * @link      https://github.com/rpi-virtuell/rw-blog-umzug
 */
class RW_Blog_Umzug_Installation {
    /**
     * Check some thinks on plugin activation
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     */
    public static function on_activate() {

        // check WordPress version
        if ( ! version_compare( $GLOBALS[ 'wp_version' ], '4.0', '>=' ) ) {
            deactivate_plugins( RW_Blog_Umzug::$plugin_filename );
            die(
            wp_sprintf(
                '<strong>%s:</strong> ' .
                __( 'This plugin requires WordPress 4.0 or newer to work', RW_Blog_Umzug::get_textdomain() )
                , RW_Blog_Umzug::get_plugin_data( 'Name' )
            )
            );
        }


        // check php version
        if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
            deactivate_plugins( RW_Blog_Umzug::$plugin_filename );
            die(
            wp_sprintf(
                '<strong>%1s:</strong> ' .
                __( 'This plugin requires PHP 5.3 or newer to work. Your current PHP version is %1s, please update.', RW_Blog_Umzug::get_textdomain() )
                , RW_Blog_Umzug::get_plugin_data( 'Name' ), PHP_VERSION
            )
            );
        }


        // @TODO Checks einbaun die das Plugin ggf als Abhängigkeiten hat. buddypress, MU, bbPress usw
        

    }



    /**
     * Clean up after deactivation
     *
     * Clean up after deactivation the plugin
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     */
    public static function on_deactivation() {

    }

    /**
     * Clean up after uninstall
     *
     * Clean up after uninstall the plugin.
     * Delete options and other stuff.
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     *
     */
    public static function on_uninstall() {

    }
}
