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

        require_once FPMTP__PLUGIN_DIR . 'admin/class.types.php';
        FullPeace_Media_To_Post_Types::register_custom_post_types();
        FullPeace_Media_To_Post_Types::register_custom_taxonomies();

        require_once FPMTP__PLUGIN_DIR . 'public/class.public.php';
        add_action( 'plugins_loaded', array( 'FullPeace_Media_To_Post_Public', 'init' ) );
    }

    public static function get_slug($type)
    {
        return self::$slug . '_' . $type;
    }

    public static function the_slug($type)
    {
        echo self::get_slug($type);
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
            self::create_talks_post_from_audio($attachment_ID);
        }
    }


    /**
     * The attachment parser. Reads the uploaded file (for each file
     * uploaded via the Media Library) and creates a corresponding
     * Custom Post Type with related Custom Taxonomy terms.
     *
     * Currently supporting:
     *
     *  - MP3 format to Talks type
     *
     * @param $attachment_ID The attachment id of an attachment with mime type 'audio'
     * @todo Create a setting in wp-admin for creating a template for the post content.
     */
    public static function create_talks_post_from_audio($attachment_ID)
    {
        global $current_user;
        get_currentuserinfo();

        $attachment_post = get_post( $attachment_ID );
        $filepath = get_attached_file( $attachment_ID );
        $attachment_meta = wp_get_attachment_metadata( $attachment_ID );
        $attached_audio = get_attached_media ( 'audio', $attachment_ID );
        $metadata = wp_read_audio_metadata( $filepath );

        $new_post_content = '[audio src="'.$attachment_post->guid.'"]'.
            "\n\n".$metadata['comment'] .
            "\n\n".$attachment_post->post_content .
            "\n\nLength of recording: ".$metadata['length_formatted'] .
            "\nYear: ".$metadata['year'];

        // Create new custom post object only for images
        $my_post = array(
            'post_title'    => $attachment_post->post_title,
            'post_content'  => $new_post_content ,
            'post_type'     => FullPeace_Media_To_Post::$slug . '_talks',
            'post_author'   => $current_user->ID
        );

        // Insert the custom post into the database
        $talk_post_id = wp_insert_post( $talk_custom_post );
        wp_update_post( array(
                'ID' => $attachment_ID ,
                'post_parent' => $talk_post_id
            )
        );
        FullPeace_Media_To_Post_Admin::add_notice('New Talk added ' . get_edit_post_link( $talk_post_id ));

        wp_set_object_terms( $talk_post_id, array( $metadata['artist'] ), FullPeace_Media_To_Post::$slug . '_speakers', true );
        wp_set_object_terms( $talk_post_id, array( $metadata['album'] ), FullPeace_Media_To_Post::$slug . '_series', true );

        // I wonder if the file has an image attached?
        $post_thumbnail_id = get_post_thumbnail_id( $attachment_ID );
        FullPeace_Media_To_Post_Admin::add_notice('post thumb ' . $post_thumbnail_id);
        if(is_numeric($post_thumbnail_id) && (int)$post_thumbnail_id>0)
        {
            set_post_thumbnail($talk_post_id, $post_thumbnail_id);
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

    public static function view( $name, array $args = array() ) {
        $args = apply_filters( self::$slug . '_view_arguments', $args, $name );

        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }

        load_plugin_textdomain( FPMTP__I18N_NAMESPACE );

        $file = FPMTP__PLUGIN_DIR . 'views/'. $name . '.php';

        include( $file );
    }
}
