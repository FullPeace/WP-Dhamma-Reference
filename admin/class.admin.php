<?php

/**
 * The FullPeace Media To Post Admin defines all functionality for the dashboard
 * of the plugin
 *
 * @package FPMTP
 */

/**
 * The FullPeace Media To Post Admin defines all functionality for the dashboard
 * of the plugin.
 *
 * @since    0.1.0
 */
class FullPeace_Media_To_Post_Admin {

    private static $initiated = false;
    private static $notices = array();

    public static function init() {
        if ( ! self::$initiated ) {
            self::init_hooks();
        }
    }

    public static function init_hooks()
    {
        // The standalone stats page was removed in 3.0 for an all-in-one config and stats page.
        // Redirect any links that might have been bookmarked or in browser history.
        if (isset($_GET['page']) && 'akismet-stats-display' == $_GET['page']) {
            wp_safe_redirect(esc_url_raw(self::get_page_url('stats')), 301);
            die;
        }

        self::$initiated = true;

        add_action('admin_init', array('FullPeace_Media_To_Post_Admin', 'admin_init'));
        add_action('admin_menu', array('FullPeace_Media_To_Post_Admin', 'admin_menu'), 5);
        add_action('admin_notices', array('FullPeace_Media_To_Post_Admin', 'display_notice'));
    }

    public static function admin_init() {
        load_plugin_textdomain( FPMTP__I18N_NAMESPACE );
        add_meta_box( 'fpmtp-status', __('Current status', FPMTP__I18N_NAMESPACE), array( 'FullPeace_Media_To_Post_Admin', 'comment_status_meta_box' ), 'comment', 'normal' );
    }

    public static function admin_menu() {
        self::load_menu();
    }

    public static function admin_head() {
        if ( !current_user_can( 'manage_options' ) )
            return;
    }

    public static function admin_plugin_settings_link( $links ) {
        $settings_link = '<a href="'.esc_url( self::get_page_url() ).'">'.__('Settings', FPMTP__I18N_NAMESPACE).'</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    public static function load_menu() {
        $hook = add_options_page( __('Media To Post', FPMTP__I18N_NAMESPACE), __('FullPeace Media To Post Settings', FPMTP__I18N_NAMESPACE), 'manage_options', 'fpmtp-settings', array( 'FullPeace_Media_To_Post_Admin', 'display_page' ) );

        if ( version_compare( $GLOBALS['wp_version'], '3.3', '>=' ) ) {
            add_action( "load-$hook", array( 'Akismet_Admin', 'admin_help' ) );
        }
    }


    /**
     * Add help to the settings page
     *
     * @return false if not the plugin's page
     */
    public static function admin_help() {
        $current_screen = get_current_screen();

        // Screen Content
        if ( current_user_can( 'manage_options' ) ) {
            if ( ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) ) {
                //setup page
                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'setup-manual',
                        'title'		=> __( 'Setup' , FPMTP__I18N_NAMESPACE),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'Media To Post Setup' , FPMTP__I18N_NAMESPACE) . '</strong></p>' .
                            '<p>' . esc_html__( 'Manage your settings for the conversion of uploaded media to Wordpress posts.' , FPMTP__I18N_NAMESPACE) . '</p>',
                    )
                );
            }
            else {
                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'settings',
                        'title'		=> __( 'Settings' , FPMTP__I18N_NAMESPACE),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'Media To Post Configuration' , FPMTP__I18N_NAMESPACE) . '</strong></p>' ,
                    )
                );
            }
        }

        // Help Sidebar
        $current_screen->set_help_sidebar(
            '<p><strong>' . esc_html__( 'For more information:' , FPMTP__I18N_NAMESPACE) . '</strong></p>' .
            '<p><a href="https://fullpeace.org/faq/" target="_blank">'     . esc_html__( 'Plugin FAQ' , FPMTP__I18N_NAMESPACE) . '</a></p>' .
            '<p><a href="https://fullpeace.org/support/" target="_blank">' . esc_html__( 'Plugin Support' , FPMTP__I18N_NAMESPACE) . '</a></p>'
        );
    }

    public static function display_alert() {
        Akismet::view( 'notice', array(
            'type' => 'alert',
            'code' => (int) get_option( 'fpmtp_alert_code' ),
            'msg'  => get_option( 'fpmtp_alert_msg' )
        ) );
    }

    public static function display_notice() {
        if ($notices= get_option('fpmtp_deferred_admin_notices')) {
            foreach ($notices as $notice) {
                echo "<div class='updated'><p>$notice</p></div>";
            }
            delete_option('fpmtp_deferred_admin_notices');
        }
    }

    public static function add_notice( $notice) {
        $notices= get_option('fpmtp_deferred_admin_notices', array());
        $notices[]= $notice;
        update_option('fpmtp_deferred_admin_notices', $notices);
    }

    public static function get_page_url( $page = 'config' ) {

        $args = array( 'page' => 'fpmtp-settings' );

        $url = add_query_arg( $args, admin_url( 'options-general.php' ) );

        return $url;
    }

} 