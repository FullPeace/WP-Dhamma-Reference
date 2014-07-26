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
        require_once FPMTP__PLUGIN_DIR . 'admin/cpt/talks-cpt.php';
        FullPeace_Media_To_Post_Talks_CPT::register_cpt();
        FullPeace_Media_To_Post_Talks_CPT::register_talks_series_cpt();
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