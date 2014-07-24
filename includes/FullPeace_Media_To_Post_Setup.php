<?php
defined( 'ABSPATH' ) OR exit;

/**
 * Class FullPeace_Media_To_Post_Setup
 *
 * @package FPMTP
 *
 * Setup class for FullPeace Media To Post, with methods for activation, deactivation and uninstall.
 */
class FullPeace_Media_To_Post_Setup
{
    /**
     * Method called on plugin activation
     */
    public static function on_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );
    }

    /**
     * Method called on plugin deactivation
     */
    public static function on_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );
    }

    /**
     * Method called on uninstall
     */
    public static function on_uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( 'bulk-plugins' );

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;
    }
}