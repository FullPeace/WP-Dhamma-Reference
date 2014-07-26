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
class FullPeace_Media_To_Post_Talks_CPT {

    /**
     * Register Custom Post Type
     */
    public static function register_cpt() {

        $labels = array(
            'name'                => _x( 'Talks', 'Post Type General Name', FPMTP__I18N_NAMESPACE ),
            'singular_name'       => _x( 'Talk', 'Post Type Singular Name', FPMTP__I18N_NAMESPACE ),
            'menu_name'           => __( 'Talks', FPMTP__I18N_NAMESPACE ),
            'parent_item_colon'   => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
            'all_items'           => __( 'All Talks', FPMTP__I18N_NAMESPACE ),
            'view_item'           => __( 'View Talk', FPMTP__I18N_NAMESPACE ),
            'add_new_item'        => __( 'Add New Talk', FPMTP__I18N_NAMESPACE ),
            'add_new'             => __( 'Add New', FPMTP__I18N_NAMESPACE ),
            'edit_item'           => __( 'Edit Talk', FPMTP__I18N_NAMESPACE ),
            'update_item'         => __( 'Update Talk', FPMTP__I18N_NAMESPACE ),
            'search_items'        => __( 'Search Talks', FPMTP__I18N_NAMESPACE ),
            'not_found'           => __( 'Not found', FPMTP__I18N_NAMESPACE ),
            'not_found_in_trash'  => __( 'Not found in Trash', FPMTP__I18N_NAMESPACE ),
        );
        $args = array(
            'label'               => __( 'talk', FPMTP__I18N_NAMESPACE ),
            'description'         => __( 'Talks (audio files)', FPMTP__I18N_NAMESPACE ),
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
            'rewrite'             => array(
                                        'slug' => 'talks',
                                        'with_front' => false,
                                    ),
        );
        register_post_type( 'fpmtp_talks', $args );

    }
    /**
     * Register relational Custom Post Type between talks and series, to be able to edit the series page.
     *
     * @todo Update the title of the CPT on taxonomy term name change
     **/
    function register_talks_series_cpt() {

        $labels = array(
            'name'                => _x( 'Talks Series', 'Post Type General Name', FPMTP__I18N_NAMESPACE ),
            'singular_name'       => _x( 'Talk Series', 'Post Type Singular Name', FPMTP__I18N_NAMESPACE ),
            'menu_name'           => __( 'Talks Series', FPMTP__I18N_NAMESPACE ),
            'parent_item_colon'   => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
            'all_items'           => __( 'All Items', FPMTP__I18N_NAMESPACE ),
            'view_item'           => __( 'View Item', FPMTP__I18N_NAMESPACE ),
            'add_new_item'        => __( 'Add New Item', FPMTP__I18N_NAMESPACE ),
            'add_new'             => __( 'Add New', FPMTP__I18N_NAMESPACE ),
            'edit_item'           => __( 'Edit Item', FPMTP__I18N_NAMESPACE ),
            'update_item'         => __( 'Update Item', FPMTP__I18N_NAMESPACE ),
            'search_items'        => __( 'Search Item', FPMTP__I18N_NAMESPACE ),
            'not_found'           => __( 'Not found', FPMTP__I18N_NAMESPACE ),
            'not_found_in_trash'  => __( 'Not found in Trash', FPMTP__I18N_NAMESPACE ),
        );
        $rewrite = array(
            'slug'                => 'talks-series',
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $capabilities = array(
            'edit_post'           => 'edit_post',
            'read_post'           => 'read_post',
            'delete_post'         => 'delete_post',
            'edit_posts'          => 'edit_posts',
            'edit_others_posts'   => 'edit_others_posts',
            'publish_posts'       => 'publish_posts', //false, // New posts should not be added manually, only on term creation
            'read_private_posts'  => 'read_private_posts',
        );
        $args = array(
            'label'               => __( 'fpmtp_talks_series', FPMTP__I18N_NAMESPACE ),
            'description'         => __( 'Series of talks', FPMTP__I18N_NAMESPACE ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author', 'revisions', ),
            'taxonomies'          => array( 'fpmtp_series' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            //'capabilities'        => $capabilities,
        );
        register_post_type( 'fpmtp_talks_series', $args );

    }
    /**
     * Adds the action to WP using the 'init' hook.
     */
    public static function init()
    {
        FullPeace_Media_To_Post_Admin::add_notice('init CPT');
        // Hook into the 'init' action
        add_action( 'init', array('FullPeace_Media_To_Post_Talks_CPT', 'register_cpt'), 0 );
    }

}