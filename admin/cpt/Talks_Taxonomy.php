<?php
/**
 * The Talks Taxonomy
 *
 * @package FPMTP
 *
 * The Talks Taxonomy used by the FullPeace Media To Post plugin
 * to create taxonomies based on audio uploads in the media library.
 *
 * @since    0.1.0
 */
class Talks_Taxonomy {

    /**
     * Register Taxonomy
     */
    public function the_speaker() {

        $labels = array(
            'name'                       => _x( 'Speakers', 'Taxonomy General Name', 'media2cpt' ),
            'singular_name'              => _x( 'Speaker', 'Taxonomy Singular Name', 'media2cpt' ),
            'menu_name'                  => __( 'Speakers', 'media2cpt' ),
            'all_items'                  => __( 'All Speakers', 'media2cpt' ),
            'parent_item'                => __( 'Parent Item', 'media2cpt' ),
            'parent_item_colon'          => __( 'Parent Item:', 'media2cpt' ),
            'new_item_name'              => __( 'New Speaker Name', 'media2cpt' ),
            'add_new_item'               => __( 'Add New Speaker', 'media2cpt' ),
            'edit_item'                  => __( 'Edit Speaker', 'media2cpt' ),
            'update_item'                => __( 'Update Speaker', 'media2cpt' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'media2cpt' ),
            'search_items'               => __( 'Search Speakers', 'media2cpt' ),
            'add_or_remove_items'        => __( 'Add or remove Speakers', 'media2cpt' ),
            'choose_from_most_used'      => __( 'Choose from the most used speakers', 'media2cpt' ),
            'not_found'                  => __( 'Not Found', 'media2cpt' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'speaker', array( 'talk' ), $args );

    }

    public function the_series(){

        $labels = array(
            'name'                       => _x( 'Series', 'Taxonomy General Name', 'media2cpt' ),
            'singular_name'              => _x( 'Serie', 'Taxonomy Singular Name', 'media2cpt' ),
            'menu_name'                  => __( 'Series', 'media2cpt' ),
            'all_items'                  => __( 'All Series', 'media2cpt' ),
            'parent_item'                => __( 'Parent Item', 'media2cpt' ),
            'parent_item_colon'          => __( 'Parent Item:', 'media2cpt' ),
            'new_item_name'              => __( 'New Series Name', 'media2cpt' ),
            'add_new_item'               => __( 'Add New Series', 'media2cpt' ),
            'edit_item'                  => __( 'Edit Series', 'media2cpt' ),
            'update_item'                => __( 'Update Series', 'media2cpt' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'media2cpt' ),
            'search_items'               => __( 'Search Series', 'media2cpt' ),
            'add_or_remove_items'        => __( 'Add or remove Series', 'media2cpt' ),
            'choose_from_most_used'      => __( 'Choose from the most used series', 'media2cpt' ),
            'not_found'                  => __( 'Not Found', 'media2cpt' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'series', array( 'talk' ), $args );
    }

    /**
     * Adds the action to WP using the 'init' hook.
     */
    public function init()
    {
        // Hook into the 'init' action
        add_action( 'init', array($this, 'the_speaker'), 0 );
        add_action( 'init', array($this, 'the_series'), 0 );
    }

} 