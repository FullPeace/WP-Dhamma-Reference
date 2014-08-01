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
     * Registers custom post types used by this plugin.
     */
    public static function register_custom_post_types()
    {
        /**
         * @todo Handle these values centrally so they're only retrieved once
         */
        $enable = array(
                'talks' => TRUE,//FullPeace_Media_To_Post::setting('enable_cpt_audio'),
                'video' => FALSE,//FullPeace_Media_To_Post::setting('enable_cpt_video'), // Not yet implemented
                'ebooks' => TRUE,//FullPeace_Media_To_Post::setting('enable_cpt_ebook'),
                );


        if(!empty($enable)) {
            require_once FPMTP__PLUGIN_DIR . 'admin/cpt/talks-cpt.php';
            if($enable['talks'])
                FullPeace_Media_To_Post_Talks_CPT::register_cpt();
            if($enable['talks'])
                FullPeace_Media_To_Post_Talks_CPT::register_talks_series_cpt();
            if($enable['ebooks'])
                FullPeace_Media_To_Post_Talks_CPT::register_ebook_cpt();
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_audio')] ) )
            $new_input[FullPeace_Media_To_Post::the_slug('enable_cpt_audio')] = absint( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_audio')] );
        if( isset( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_video')] ) )
            $new_input[FullPeace_Media_To_Post::the_slug('enable_cpt_video')] = absint( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_video')] );
        if( isset( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_ebook')] ) )
            $new_input[FullPeace_Media_To_Post::the_slug('enable_cpt_ebook')] = absint( $input[FullPeace_Media_To_Post::the_slug('enable_cpt_ebook')] );

        return $new_input;
    }
    /**
     * Registers custom taxonomies used by this plugin.
     */
    public static function register_custom_taxonomies()
    {
        require_once FPMTP__PLUGIN_DIR . 'admin/cpt/talks-taxonomies.php';
        FullPeace_Media_To_Post_Talks_Taxonomy::the_speaker();
        FullPeace_Media_To_Post_Talks_Taxonomy::the_series();
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