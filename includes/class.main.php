<?php
/**
 * The FullPeace Media To Posts Admin defines all functionality of the plugin
 *
 * @package FPMTP
 *
 * The FullPeace Media To Posts Admin defines all functionality of the plugin.
 *
 * This class defines the meta box used to display the post meta data and registers
 * the style sheet responsible for styling the content of the meta box.
 *
 * @since    0.1.0
 *
 * @todo Create template for single & archive Bio with links to Author & Speaker taxonomies
 * @todo Create template fpr single & archive Audio
 * @todo Create template for single & archive Book
 * @todo create template for taxonomy-fpmtp_series with playlist on term pages
 * @todo Create template for speakers and authors taxonomy pages for listing authors and number of related posts, with Bio excerpt
 * @todo Create search template for all CPTs + taxonomies
 * @todo Create search parsing function
 */
class FullPeace_Media_To_Post {

    /**
     * @var bool
     */
    private static $initiated = false;

    public static $slug = 'fpmtp';

    /**
     * Initiates the plugin
     */
    public static function init()
    {
        if ( ! self::$initiated )
        {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     * @todo Add check for enabled CPTs and load them based on that.
     * @todo Special case for eBooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;
        add_action( 'admin_notices', array( 'FullPeace_Media_To_Post_Admin', 'display_notice' ) );
        add_action( 'add_attachment', array( 'FullPeace_Media_To_Post', 'post_from_attachment' ) );
        add_filter( 'template_include', array( 'FullPeace_Media_To_Post', 'template_chooser' ) );

//        require_once FPMTP__PLUGIN_DIR . 'admin/class.types.php';
//        FullPeace_Media_To_Post_Types::register_custom_post_types();
//        FullPeace_Media_To_Post_Types::register_custom_taxonomies();

        require_once FPMTP__PLUGIN_DIR . 'public/class.public.php';
        add_action( 'plugins_loaded', array( 'FullPeace_Media_To_Post_Public', 'init' ) );
    }

    public static function get_slug($type = FALSE)
    {
        if($type)
            return self::$slug . '_' . $type;
        else
            return plugin_basename( __FILE__ );
    }

    public static function the_slug($type)
    {
        echo self::get_slug($type);
    }
	
	public static function settings(){
		return get_option( 'FullPeace_Media_To_Post' );
	}
	
	public static function debugSettings(){
		$x = self::settings();
		var_export($x, true);
	}

    public static function setting($setting, $option_value = FALSE)
    {
        if($option_value)
        {
            //FullPeace_Media_To_Post_Admin::add_notice('Setting setting ' . $setting . ' to ' . $option_value);
            $added = add_option( FullPeace_Media_To_Post::get_slug($setting), $option_value);
            register_setting( 'default', FullPeace_Media_To_Post::get_slug($setting) );
        }else{
            //FullPeace_Media_To_Post_Admin::add_notice('Got setting ' . $setting . ' :: ' . get_option(FullPeace_Media_To_Post::get_slug($setting)));
            return get_option(FullPeace_Media_To_Post::get_slug($setting));
        }
    }

    /**
     * Parses each attachment uploaded through the media library
     * and creates a custom post with related taxonomy terms.
     *
     * @param $attachment_ID
     */
    public static function post_from_attachment($attachment_ID)
    {
        $type = get_post_mime_type($attachment_ID);
        if(strpos($type, 'audio') === 0)
        {
            self::create_audio_post_from_upload($attachment_ID);
        }
    }


    /**
     * The attachment parser. Reads the uploaded file (for each file
     * uploaded via the Media Library) and creates a corresponding
     * Custom Post Type with related Custom Taxonomy terms.
     *
     * Currently supporting:
     *
     *  - MP3 format to Audio type
     *
     * @param $attachment_ID The attachment id of an attachment with mime type 'audio'
     */
    public static function create_audio_post_from_upload($attachment_ID)
    {
        global $current_user;
        get_currentuserinfo();

        $aAudioSettings = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_audio' );

        $attachment_post = get_post( $attachment_ID );
        $filepath = get_attached_file( $attachment_ID );
        //$attachment_meta = wp_get_attachment_metadata( $attachment_ID );
        //$attached_audio = get_attached_media ( 'audio', $attachment_ID );
        $metadata = wp_read_audio_metadata( $filepath );

        $meta_comment = (isset($metadata['comment'])) ? $metadata['comment'] : "";
        $meta_length = (isset($metadata['length_formatted'])) ? "\n\n".__('Length of recording', FPMTP__I18N_NAMESPACE ) .": ".$metadata['length_formatted'] : "";
        $meta_year = (isset($metadata['year'])) ? "\n".__('Year', FPMTP__I18N_NAMESPACE ) .": ".$metadata['year'] : "";

        $new_post_content = '[audio src="'.$attachment_post->guid.'"]'.
            "\n\n".$attachment_post->post_content .
            $meta_length .
            $meta_year;

        // Create new custom post object only for images
        $audio_custom_post = array(
            'post_title'    => $attachment_post->post_title,
            'post_content'  => $new_post_content ,
            'post_excerpt'  => $meta_comment ,
            'post_type'     => FullPeace_Media_To_Post::$slug . '_audio',
            'post_author'   => $current_user->ID
        );

        if(isset($aAudioSettings['fpmtp_audio_post_status']))
        {
            $audio_custom_post['post_status'] = $aAudioSettings['fpmtp_audio_post_status'];
        }

        // Insert the custom post into the database
        $audio_post_id = wp_insert_post( $audio_custom_post );
        wp_update_post( array(
                'ID' => $attachment_ID ,
                'post_parent' => $audio_post_id
            )
        );

        if(isset($metadata['artist'] ))
            wp_set_object_terms( $audio_post_id, array( $metadata['artist'] ), FullPeace_Media_To_Post::$slug . '_speakers', true );
        if(isset($metadata['album'] ))
            wp_set_object_terms( $audio_post_id, array( $metadata['album'] ), FullPeace_Media_To_Post::$slug . '_series', true );

        // I wonder if the file has an image attached?
        $post_thumbnail_id = get_post_thumbnail_id( $attachment_ID );
        if(is_numeric($post_thumbnail_id) && (int)$post_thumbnail_id>0)
        {
            set_post_thumbnail($audio_post_id, $post_thumbnail_id);
        }
		
		// Now that the attachment is parsed, check the setting to see if the file should be moved to FTP
		$plugin_settings = self::settings();
		if($plugin_settings['fpmtp_enable_ftp']) {
			require_once ( FPMTP__PLUGIN_DIR . 'library/Ftp.php' );
			
			try {
				$ftp = new Ftp;

				// Opens an FTP connection to the specified host
				$ftp->connect($plugin_settings['fpmtp_ftp_domain']);

				// Login with username and password
				$ftp->login($plugin_settings['fpmtp_ftp_username'], $plugin_settings['fpmtp_ftp_password']);

				$ftpdir = $plugin_settings['fpmtp_ftp_dir'];
				// Get the local filepath and upload to FTP

                /// THIS IS NOT DEVELOPED DUE TO CHANGED REQUIREMENTS

			} catch (FtpException $e) {
				echo 'Error: ', $e->getMessage();
			}
		}
    }

    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return    string    FPMTP__VERSION    The current version of the plugin.
     */
    public static function get_version()
    {
        return FPMTP__VERSION;
    }

    /**
     * Plugin installation and activation.
     *
     * Registers custom post types and taxonomies used by the plugin.
     * @since    0.1.0
     */
    public static function plugin_activation()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';
        FullPeace_Media_To_Post_Setup::on_activation();
        //Ensure the $wp_rewrite global is loaded
        global $wp_rewrite;
        //Call flush_rules() as a method of the $wp_rewrite object
        $wp_rewrite->flush_rules( false );
    }

    /**
     * Plugin deactivation.
     *
     * Does nothing this version.
     * @since    0.1.0
     */
    public static function plugin_deactivation()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';

        FullPeace_Media_To_Post_Setup::on_deactivation();
        delete_option(self::$slug . '_deferred_admin_notices');
    }

    /**
     * Plugin uninstall.
     *
     * Does nothing this version.
     * @since    0.1.0
     */
    public static function plugin_uninstall()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';

        FullPeace_Media_To_Post_Setup::on_uninstall();
    }


    /**
     * Returns the template file
     *
     * @since       0.1.2
     */

    public static function  template_chooser($template) {

        // Post ID
        $post_id = get_the_ID();

        // For all other CPT
        if( get_post_type( $post_id ) != 'fpmtp_bios' ) {
            return $template;
        }

        // Else use custom template
        if ( is_single() ) {
            return self::get_template_hierarchy('single');
        }

    }

    /**
     * Get the custom template if is set
     *
     * @since       0.1.2
     */

    public static function    get_template_hierarchy( $template ) {

        // Get the template slug
        $template_slug = rtrim($template, '.php');
        $template      = $template_slug . '.php';

        // Check if a custom template exists in the theme folder, if not, load the plugin template file
        if ( $theme_file = locate_template(array('plugin_template/'.$template)) ) {
            $file = $theme_file;
        }
        else {
            $file = FPMTP__PLUGIN_DIR . 'templates/' . $template;
        }

        return apply_filters( 'repl_template_'.$template, $file);
    }
}
