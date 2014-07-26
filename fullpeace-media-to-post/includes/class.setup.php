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
     * Plugin activation. Creates Custom Post Types and Taxonomies used by this plugin
     */
    public static function on_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

        FullPeace_Media_To_Post_Admin::add_notice('Thank you for installing the FullPeace.org Media To Post plugin. If there is anything we can do to help you with it, please let us know by emailing developer@fullpeace.org');

        $version_setting_name = 'FPMTP_version';

        $installed_version_num = get_option($version_setting_name);

        // In case we need to compare versions for updates later

        // Update the version value

        if (is_multisite()) {
            update_site_option($version_setting_name, FPMTP__VERSION);
        } else {
            update_option($version_setting_name, FPMTP__VERSION);
        }

    }

    /**
     * Plugin deactivation.
     *
     * Does nothing this version.
     * @since    0.1.0
     */
    public static function on_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );
    }

    /**
     * Plugin uninstall.
     *
     * Does nothing this version.
     * @since    0.1.0
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