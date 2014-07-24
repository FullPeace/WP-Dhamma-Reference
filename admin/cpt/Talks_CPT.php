<?php
/**
 * The Talks Custom Post Type
 *
 * @package FPMTP
 *
 * The Talks Custom Post Type used by the FullPeace Media To Post plugin
 * to create posts based on audio uploads in the media library.
 *
 * @since    0.1.0
 */
class Talks_CPT {

    /**
     * Register Custom Post Type
     */
    public function the_cpt() {

        $labels = array(
            'name'                => _x( 'Talks', 'Post Type General Name', 'media2cpt' ),
            'singular_name'       => _x( 'Talk', 'Post Type Singular Name', 'media2cpt' ),
            'menu_name'           => __( 'Talks', 'media2cpt' ),
            'parent_item_colon'   => __( 'Parent Item:', 'media2cpt' ),
            'all_items'           => __( 'All Talks', 'media2cpt' ),
            'view_item'           => __( 'View Talk', 'media2cpt' ),
            'add_new_item'        => __( 'Add New Talk', 'media2cpt' ),
            'add_new'             => __( 'Add New', 'media2cpt' ),
            'edit_item'           => __( 'Edit Talk', 'media2cpt' ),
            'update_item'         => __( 'Update Talk', 'media2cpt' ),
            'search_items'        => __( 'Search Talks', 'media2cpt' ),
            'not_found'           => __( 'Not found', 'media2cpt' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'media2cpt' ),
        );
        $args = array(
            'label'               => __( 'talk', 'media2cpt' ),
            'description'         => __( 'Talks (audio files)', 'media2cpt' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', ),
            'taxonomies'          => array( 'speaker', 'compilation', 'duration', 'location', 'category' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );
        register_post_type( 'talk', $args );

    }

    /**
     * Adds the action to WP using the 'init' hook.
     */
    public function init()
    {
        // Hook into the 'init' action
        add_action( 'init', array($this, 'the_cpt'), 0 );
    }

} 