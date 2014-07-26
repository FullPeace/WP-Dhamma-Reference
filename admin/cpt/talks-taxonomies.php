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
class FullPeace_Media_To_Post_Talks_Taxonomy {

    /**
     * Registers the Speakers Custom Taxonomy for the Talks Custom Post Type.
     */
    public function the_speaker() {

        $labels = array(
            'name'                       => _x( 'Speakers', 'Taxonomy General Name', FPMTP__I18N_NAMESPACE ),
            'singular_name'              => _x( 'Speaker', 'Taxonomy Singular Name', FPMTP__I18N_NAMESPACE ),
            'menu_name'                  => __( 'Speakers', FPMTP__I18N_NAMESPACE ),
            'all_items'                  => __( 'All Speakers', FPMTP__I18N_NAMESPACE ),
            'parent_item'                => __( 'Parent Item', FPMTP__I18N_NAMESPACE ),
            'parent_item_colon'          => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
            'new_item_name'              => __( 'New Speaker Name', FPMTP__I18N_NAMESPACE ),
            'add_new_item'               => __( 'Add New Speaker', FPMTP__I18N_NAMESPACE ),
            'edit_item'                  => __( 'Edit Speaker', FPMTP__I18N_NAMESPACE ),
            'update_item'                => __( 'Update Speaker', FPMTP__I18N_NAMESPACE ),
            'separate_items_with_commas' => __( 'Separate items with commas', FPMTP__I18N_NAMESPACE ),
            'search_items'               => __( 'Search Speakers', FPMTP__I18N_NAMESPACE ),
            'add_or_remove_items'        => __( 'Add or remove Speakers', FPMTP__I18N_NAMESPACE ),
            'choose_from_most_used'      => __( 'Choose from the most used speakers', FPMTP__I18N_NAMESPACE ),
            'not_found'                  => __( 'Not Found', FPMTP__I18N_NAMESPACE ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array(
                                                'slug' => 'speakers',
                                                'with_front' => false,
                                            ),
        );
        register_taxonomy( 'fpmtp_speakers', array( 'fpmtp_talks' ), $args );

    }

    /**
     * Registers the Series Custom Taxonomy for the Talks Custom Post Type.
     */
    public function the_series(){

        $labels = array(
            'name'                       => _x( 'Series', 'Taxonomy General Name', FPMTP__I18N_NAMESPACE ),
            'singular_name'              => _x( 'Series', 'Taxonomy Singular Name', FPMTP__I18N_NAMESPACE ),
            'menu_name'                  => __( 'Series', FPMTP__I18N_NAMESPACE ),
            'all_items'                  => __( 'All Series', FPMTP__I18N_NAMESPACE ),
            'parent_item'                => __( 'Parent Item', FPMTP__I18N_NAMESPACE ),
            'parent_item_colon'          => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
            'new_item_name'              => __( 'New Series Name', FPMTP__I18N_NAMESPACE ),
            'add_new_item'               => __( 'Add New Series', FPMTP__I18N_NAMESPACE ),
            'edit_item'                  => __( 'Edit Series', FPMTP__I18N_NAMESPACE ),
            'update_item'                => __( 'Update Series', FPMTP__I18N_NAMESPACE ),
            'separate_items_with_commas' => __( 'Separate items with commas', FPMTP__I18N_NAMESPACE ),
            'search_items'               => __( 'Search Series', FPMTP__I18N_NAMESPACE ),
            'add_or_remove_items'        => __( 'Add or remove Series', FPMTP__I18N_NAMESPACE ),
            'choose_from_most_used'      => __( 'Choose from the most used series', FPMTP__I18N_NAMESPACE ),
            'not_found'                  => __( 'Not Found', FPMTP__I18N_NAMESPACE ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array(
                                                'slug' => 'series',
                                                'with_front' => false,
                                                ),
        );
        register_taxonomy( 'fpmtp_series', array( 'fpmtp_talks' ), $args );
    }

    /**
     * Adds the action to WP using the 'init' hook.
     */
    public function init()
    {
        // Hook into the 'init' action
        add_action( 'init', array('FullPeace_Media_To_Post_Talks_Taxonomy', 'the_speaker'), 0 );
        add_action( 'init', array('FullPeace_Media_To_Post_Talks_Taxonomy', 'the_series'), 0 );
    }

} 