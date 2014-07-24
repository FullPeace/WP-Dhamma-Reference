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
     * A reference to the version of the plugin that is passed to this class from the caller.
     *
     * @access private
     * @var    string    $version    The current version of the plugin.
     */
    private $version;

    /**
     * Initializes this class and stores the current version of this plugin.
     *
     * @param    string    $version    The current version of this plugin.
     */
    public function __construct( $version ) {
        $this->version = $version;
    }

    /**
     * Registers custom post types used by this plugin.
     */
    public function register_custom_post_types()
    {

    }

    /**
     * Registers custom taxonomies used by this plugin.
     */
    public function register_custom_taxonomies()
    {

    }
} 