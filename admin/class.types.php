<?php

/**
 * The FullPeace Media To Post Admin defines all functionality for the dashboard
 * of the plugin
 *
 * @package FPMTP
 *
 * The FullPeace Media To Post Type provides functions for registering custom
 * post types and taxonomies used by the plugin.
 *
 * @since    0.1.0
 */
class FullPeace_Media_To_Post_Types {


    /**
     * The attachment parser. Reads the uploaded file (for each file
     * uploaded via the Media Library) and creates a corresponding
     * Custom Post Type with related Custom Taxonomy terms.
     *
     * Currently supporting:
     *
     *  - MP3 format to Talks type
     *
     * @param $attachment_ID
     */
    public static function post_type_from_attachment($attachment_ID)
    {
        global $current_user;
        get_currentuserinfo();

        $attachment_post = get_post( $attachment_ID );
        $filepath = get_attached_file( $attachment_ID );
        $attachment_meta = wp_get_attachment_metadata( $attachment_ID );
        $attached_media = get_attached_media ( 'audio', $attachment_ID );
        $metadata = wp_read_audio_metadata( $filepath );


        $type = get_post_mime_type($attachment_ID);
        if(strpos($type, 'audio') === 0)
        {
            // Create new custom post object only for images
            $my_post = array(
                'post_title'    => $attachment_post->post_title,
                'post_content'  => '[audio src="'.$attachment_post->guid.'"]'. $attachment_post->post_content . "\n^_^\n".$filepath . "\n^_^\n".var_export($attachment_post , true) . "\n^_^\n".var_export($attachment_meta , true) . "\n^_^\n".var_export($attached_media , true). "\n^_^\nmetadata: ".var_export($metadata , true),
                'post_type'   => 'talk',
                'post_author'   => $current_user->ID
            );

            // Insert the custom post into the database
            $post_id = wp_insert_post( $my_post );
            wp_update_post( array(
                    'ID' => $attachment_ID ,
                    'post_parent' => $post_id
                )
            );

            wp_set_object_terms( $post_id, array( $metadata['artist'] ), 'speaker', true );
            wp_set_object_terms( $post_id, array( $metadata['album'] ), 'compilation', true );
        }
    }

    /**
     * Registers custom post types used by this plugin.
     */
    public static function register_custom_post_types()
    {
        require_once FPMTP__PLUGIN_DIR . 'admin/cpt/talks-cpt.php';
        FullPeace_Media_To_Post_Talks_CPT::init();
    }

    /**
     * Registers custom taxonomies used by this plugin.
     */
    public static function register_custom_taxonomies()
    {
        require_once FPMTP__PLUGIN_DIR . 'admin/cpt/talks-taxonomies.php';
        FullPeace_Media_To_Post_Talks_Taxonomy::init();
    }

    /**
     * Check if the specified custom post type exists.
     *
     * @param $type
     */
    public static function custom_post_type_exists($type)
    {
        // Check if the specified custom post type exists
    }

    /**
     * Check if the specified custom taxonomy exists.
     *
     * @param $taxonomy_name
     */
    public static function custom_taxonomy_exists($taxonomy_name)
    {
        // Check if the specified custom taxonomy exists
    }
} 