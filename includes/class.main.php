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

    /**
     *
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
     */
    private static function init_hooks()
    {
        self::$initiated = true;
        add_action( 'add_attachment', array( 'FullPeace_Media_To_Post', 'post_from_attachment' ) );
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
     * Parses audio files for ID3 tags and creates a post with taxonomy terms.
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
            'post_type'     => 'fpmtp_talks',
            'post_author'   => $current_user->ID
        );

        // Insert the custom post into the database
        $post_id = wp_insert_post( $my_post );
        wp_update_post( array(
                'ID' => $attachment_ID ,
                'post_parent' => $post_id
            )
        );

        wp_set_object_terms( $post_id, array( $metadata['artist'] ), 'fpmtp_speakers', true );
        wp_set_object_terms( $post_id, array( $metadata['album'] ), 'fpmtp_series', true );
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

}
