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
        self::$initiated = true;


        add_action('admin_init', array('FullPeace_Media_To_Post_Admin', 'admin_init'));
        add_action('admin_menu', array('FullPeace_Media_To_Post_Admin', 'admin_menu'), 5);
        add_action('admin_notices', array('FullPeace_Media_To_Post_Admin', 'display_notice'));
        //add_filter('get_sample_permalink_html', array('FullPeace_Media_To_Post_Admin', 'disable_editing_url_for_audio_series'), '',4);

        //add_action( 'create_term', array( 'FullPeace_Media_To_Post_Admin', 'act_on_create_term' ), 1, 3 );
        //add_action( 'created_term', array( 'FullPeace_Media_To_Post_Admin', 'act_on_created_term' ), 1, 3 );

        add_filter('upload_mimes',  array( 'FullPeace_Media_To_Post_Admin', 'add_book_mime_types'), 1, 1);
    }

    public static function admin_init() {
        load_plugin_textdomain( FPMTP__I18N_NAMESPACE );

//        self::setting('enable_cpt_audio', 'enable');
//        self::setting('enable_cpt_video', '');
//        self::setting('enable_cpt_book', '');

        // New approach ^_^
        //self::register_settings();
//        //add_option( FullPeace_Media_To_Post::get_slug('set_cpt_audio'), 'Talks');
//        //add_option( FullPeace_Media_To_Post::get_slug('set_cpt_video'), 'Video');
//        add_option( FullPeace_Media_To_Post::get_slug('enable_cpt_audio'), 'enable');
//        add_option( FullPeace_Media_To_Post::get_slug('enable_cpt_video'), '');
//        add_option( FullPeace_Media_To_Post::get_slug('enable_cpt_book'), '');
//        //register_setting( 'default', FullPeace_Media_To_Post::get_slug('set_cpt_audio') );
//        //register_setting( 'default', FullPeace_Media_To_Post::get_slug('set_cpt_video') );
//        register_setting( 'default', FullPeace_Media_To_Post::get_slug('enable_cpt_audio') );
//        register_setting( 'default', FullPeace_Media_To_Post::get_slug('enable_cpt_video') );
//        register_setting( 'default', FullPeace_Media_To_Post::get_slug('enable_cpt_book') );
    }


    public static function add_book_mime_types($mime_types){

        // @todo: The below would disable uploading images in the post content. Needs to restrict metabox upload.
//        $screen = get_current_screen();
//        if($screen->post_type == 'fpmtp_books'){
//            $aRestrictedMimeTypes = array();
//            $aRestrictedMimeTypes['pdf'] = 'application/pdf';
//            $aRestrictedMimeTypes['mobi'] = 'application/x-mobipocket-book';
//            $aRestrictedMimeTypes['epub'] = 'application/epub+zip';
//            return $aRestrictedMimeTypes; // Only allow these book formats
//        }

        //Adding book extensions
        $mime_types['mobi'] = 'application/x-mobipocket-book';
        $mime_types['epub'] = 'application/epub+zip';

        // Just in case
        if(!isset($mime_types['pdf']))
            $mime_types['pdf'] = 'application/pdf';

        return $mime_types;
    }

    public static function setting($setting, $option_value = FALSE)
    {
        return FullPeace_Media_To_Post::setting($setting, $option_value);
    }

    private static function register_settings() {

        register_setting( FullPeace_Media_To_Post::get_slug( 'option_group' ), FullPeace_Media_To_Post::get_slug('setting_enable_audio') );
        register_setting( FullPeace_Media_To_Post::get_slug( 'option_group' ), FullPeace_Media_To_Post::get_slug('setting_enable_video') );
        register_setting( FullPeace_Media_To_Post::get_slug( 'option_group' ), FullPeace_Media_To_Post::get_slug('setting_enable_audio') );
        add_option( FullPeace_Media_To_Post::get_slug('setting_enable_audio'), 1);
        add_option( FullPeace_Media_To_Post::get_slug('setting_enable_video'), 0);
        add_option( FullPeace_Media_To_Post::get_slug('setting_enable_audio'), 1);

        add_settings_section( 'main_settings' , 'Main Settings', array( __CLASS__, 'display_settings_section' ), FullPeace_Media_To_Post::get_slug( 'settings' ));
        add_settings_field('plugin_text_string', 'Plugin Text Input', array( __CLASS__, 'display_settings_section' ), FullPeace_Media_To_Post::get_slug( 'settings' ), FullPeace_Media_To_Post::get_slug( 'main_settings' ));

        add_settings_section(
        // ID used to identify this section and with which to register options
            'settings_section',
            // Title to be displayed on the administration page
            'Configuration of Media To Post',
            // Callback used to render the description of the section
            array( __CLASS__, 'display_settings_section' ),
            // Page on which to add this section of options
            FullPeace_Media_To_Post::get_slug('settings')
        );

        add_settings_field(
            FullPeace_Media_To_Post::get_slug('setting_field_audio'),
            'Enable Talks',
            array( __CLASS__, 'render_option_input_html_audio' ),
            FullPeace_Media_To_Post::get_slug('settings'),
            'settings_section'
        );

        add_settings_field(
            FullPeace_Media_To_Post::get_slug('setting_field_video'),
            'Enable Video',
            array( __CLASS__, 'render_option_input_html_video' ),
            FullPeace_Media_To_Post::get_slug('settings'),
            'settings_section'
        );

        add_settings_field(
            FullPeace_Media_To_Post::get_slug('setting_field_book'),
            'Enable Books',
            array( __CLASS__, 'render_option_input_html_book' ),
            FullPeace_Media_To_Post::get_slug('settings'),
            'settings_section'
        );

    }// end of register_settings()

    /**
     * Generate the HTML input element
     *
     * @since 0.1.0
     */
    public static function render_option_input_html_audio() {
        // First, we read the option from db
        $options = get_option( FullPeace_Media_To_Post::get_slug('setting_enable_audio') );
var_dump($options);
        $html = '<input type="checkbox" id="checkbox_audio" name="render_option_input_html_audio[checkbox_audio]" value="1"' . checked( 1, $options['checkbox_audio'], false ) . '/>';
        $html .= '<label for="checkbox_audio">This is an example of a checkbox</label>';

        echo $html;

    } // end render_option_input_html_audio

    /**
     * Generate the HTML input element
     *
     * @since 0.1.0
     */
    public static function render_option_input_html_video() {
        // First, we read the option from db
        $options = get_option( FullPeace_Media_To_Post::get_slug('setting_enable_video') );
        var_dump($options);

        $html = '<input type="checkbox" id="'.FullPeace_Media_To_Post::get_slug('setting_enable_video').'" name="'.FullPeace_Media_To_Post::get_slug('setting_enable_video').'" value="1"' . checked( 1, $options[FullPeace_Media_To_Post::get_slug('setting_enable_video')], false ) . '/>';
        $html .= '<label for="'.FullPeace_Media_To_Post::get_slug('setting_enable_video').'">This is an example of a checkbox</label>';

        echo $html;

    } // end render_option_input_html_video

    /**
     * Generate the HTML input element
     *
     * @since 0.1.0
     */
    public static function render_option_input_html_book() {
        // First, we read the option from db
        $options = get_option( FullPeace_Media_To_Post::get_slug('setting_enable_book') );
        var_dump($options);

        $html = '<input type="checkbox" id="'.FullPeace_Media_To_Post::get_slug('setting_enable_book').'" name="'.FullPeace_Media_To_Post::get_slug('setting_enable_book').'" value="1"' . checked( 1, $options[FullPeace_Media_To_Post::get_slug('setting_enable_book')], false ) . '/>';
        $html .= '<label for="'.FullPeace_Media_To_Post::get_slug('setting_enable_book').'"> Custom Post Type for attaching Book files</label>';

        echo $html;

    } // end render_option_input_html_book

    /**
     * This function provides a simple description for the Sunny Demo Options page.
     * This function is being passed as a parameter in the add_settings_section function.
     *
     * @since 0.1.0
     */
    public static function display_settings_section() {
        echo '<p>Media To Post plugin creates posts when you upload files to Wordpress.</p>';
    } // end of display_settings_section

    public static function admin_menu() {
        // Replaced by admin page framework
        //self::load_menu();
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

        add_submenu_page(
            'edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_audio',
            'talks-series',
            'Talks Series',
            'edit_posts',
            'edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_audio_series');

        self::register_settings();

        $hook = add_options_page(
            __('Media To Post', FPMTP__I18N_NAMESPACE),
            __('FullPeace Media To Post Settings', FPMTP__I18N_NAMESPACE),
            'manage_options',
            FullPeace_Media_To_Post::get_slug('settings'),
            array( 'FullPeace_Media_To_Post_Admin', 'display_page' ) );

        if ( version_compare( $GLOBALS['wp_version'], '3.3', '>=' ) ) {
            add_action( "load-$hook", array( 'FullPeace_Media_To_Post_Admin', 'admin_help' ) );
        }

    }



    function disable_editing_url_for_audio_series($return, $id, $new_title, $new_slug){
        global $post;
        if($post->post_type == FullPeace_Media_To_Post::$slug . '_audio_series')
        {
            $ret2 = preg_replace('/<span id="edit-slug-buttons">.*<\/span>|<span id=\'view-post-btn\'>.*<\/span>/i', '', $return);
        }

        return $ret2;
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
            'code' => (int) get_option( FullPeace_Media_To_Post::$slug . '_alert_code' ),
            'msg'  => get_option( FullPeace_Media_To_Post::$slug . '_alert_msg' )
        ) );
    }

    public static function display_notice() {
        if ($notices= get_option(FullPeace_Media_To_Post::$slug . '_deferred_admin_notices')) {
            foreach ($notices as $notice) {
                echo "<div class='updated'><p>$notice</p></div>";
            }
            delete_option(FullPeace_Media_To_Post::$slug . '_deferred_admin_notices');
        }
    }

    public static function add_notice( $notice) {
        $notices= get_option(FullPeace_Media_To_Post::$slug . '_deferred_admin_notices', array());
        $notices[]= $notice;
        update_option(FullPeace_Media_To_Post::$slug . '_deferred_admin_notices', $notices);
    }

    public static function get_page_url( $page = 'config' ) {

        $args = array( 'page' => 'fpmtp-settings' );

        $url = add_query_arg( $args, admin_url( 'options-general.php' ) );

        return $url;
    }


    public static function display_page() {
        FullPeace_Media_To_Post::view('settings');
        /*?>
        <div class="wrap">
            <h2>Configuration</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'default' ); ?>
                <h3>Settings for the Media To Post plugin</h3>
                <p>Enable parsing files uploaded via the media library.</p>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="<?php FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); ?>"">Enable parsing audio files:</label></th>
                        <td><input type="checkbox" id="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); ?>" <?php echo self::setting('enable_cpt_book') == 'enable' ? "checked" : "" ; ?> value="enable" />
                            <a href="<?php echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_audio'); ?>">Talks</a></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); ?>">Enable parsing video files:</label> (NOT YET IMPLEMENTED)</th>
                        <td><input type="checkbox" id="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); ?>" <?php echo self::setting('enable_cpt_video') == 'enable' ? "checked" : "" ; ?> value="enable" /> <a href="<?php echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_video'); ?>">Video</a></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_book'); ?>">Enable Books (PDF, EPUB, MOBI files):</label> (NOT YET IMPLEMENTED)</th>
                        <td><input type="checkbox" id="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_book'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('enable_cpt_book'); ?>" <?php echo self::setting('enable_cpt_book') == 'enable' ? "checked" : "" ; ?> value="enable" /> <a href="<?php echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_book'); ?>">Book</a></td>
                    </tr>
                </table>
                <!--
                <p><strong>RECOMMENDED:</strong> <em>Please do not these settings unless you are absolutely sure of what you are doing.
                        Changing these values require significant operations for existing posts.</em></p>
                <p>If there are no posts generated from media on this site, or you don't care about the existing posts
                (they risk being 'orphaned'), then you can change these settings to the names you want.</p>
                <p><strong>AS OF VERSION 0.1.0, THIS FEATURE IS DISABLED.</strong></p>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>">Custom Post Type for audio files:</label></th>
                        <td><input type="text" id="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>" value="<?php echo get_option(FullPeace_Media_To_Post::get_slug('set_cpt_audio')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>">Custom Post Type for audio files:</label></th>
                        <td><input type="text" id="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>" value="<?php echo get_option(FullPeace_Media_To_Post::get_slug('set_cpt_video')); ?>" /></td>
                    </tr>
                </table>
                -->
                <?php submit_button(); ?>
            </form>
        </div>
    <?php*/
    }


    // Pre create term
    function act_on_create_term( $term_id, $tt_id, $taxonomy)
    {
    }

    // Pre create $taxonomy
    function act_on_create_taxonomy( $term_id, $tt_id )
    {
    }

    // Post create term
    function act_on_created_term( $term_id, $tt_id, $taxonomy)
    {
        return;
        FullPeace_Media_To_Post_Admin::add_notice('hooked ' . __FUNCTION__ . ' term ' . $term_id .'.'.$tt_id .'.'.$taxonomy .'.');

        if(taxonomy_exists(FullPeace_Media_To_Post::$slug . '_series') && post_type_exists(FullPeace_Media_To_Post::$slug . '_audio_series') && $taxonomy == FullPeace_Media_To_Post::$slug . '_series'){
            /**
             * Lookup the term name
             */
            $inserted_term = get_term_by('id', (int)$term_id, $taxonomy);
FullPeace_Media_To_Post_Admin::add_notice('hooked ' . __FUNCTION__ . ' term ' . $inserted_term->name);
            if($inserted_term)
            {
//                $args=array(
//                    'name' => $inserted_term->name,
//                    'post_type' => FullPeace_Media_To_Post::$slug . '_audio_series',
//                    'posts_per_page' => 1
//                );
//                $talks_series_posts = get_posts( $args );
                $talks_series_posts = get_page_by_title( $inserted_term->name, 'OBJECT', FullPeace_Media_To_Post::$slug . '_audio_series' );
FullPeace_Media_To_Post_Admin::add_notice('hooked ' . __FUNCTION__ . ' $talks_series_posts ' . $talks_series_posts->ID);

                if(empty($talks_series_posts))
                {
                    // No such post - insert
                    // Create post object
                    $new_ts_post = array(
                        'post_title'    => $inserted_term->name,
                        'post_content'  => '',
                        'post_status'   => 'publish',
                        'post_type'     => FullPeace_Media_To_Post::$slug . '_audio_series',
                    );

                    // Insert the post into the database
                    wp_insert_post( $new_ts_post );
                }
            }
        }
    }

    // Post create_$taxonomy
    function act_on_created_taxonomy( $term_id, $tt_id ) {
    }

} 