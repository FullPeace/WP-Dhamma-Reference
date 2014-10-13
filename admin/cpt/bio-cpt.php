<?php
/**
 * The plugin's Biography Custom Post Type
 *
 * @package FPMTP
 *
 * The Custom Post Types used by the FullPeace Media To Post plugin
 * to create posts based on uploads in the media library.
 *
 * @since    0.1.0
 */

class FullPeace_Bio_PostType  extends AdminPageFramework_PostType {
    /**
     * This method is called at the end of the constructor.
     *
     * Alternatively, use the start_{extended class name} method, which also is called at the end of the constructor.
     */
    public function start() {

        $this->setAutoSave( true );
        $this->setAuthorTableFilter( true );

        $this->setPostTypeArgs(
            array(			// argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels' => array(
                    'name'                => _x( 'Biographies', 'Post Type General Name', FPMTP__I18N_NAMESPACE ),
                    'singular_name'       => _x( 'Biography', 'Post Type Singular Name', FPMTP__I18N_NAMESPACE ),
                    'menu_name'           => __( 'Biographies', FPMTP__I18N_NAMESPACE ),
                    'parent_item_colon'   => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
                    'all_items'           => __( 'All Biographies', FPMTP__I18N_NAMESPACE ),
                    'view_item'           => __( 'View Biography', FPMTP__I18N_NAMESPACE ),
                    'add_new_item'        => __( 'Add New Bio', FPMTP__I18N_NAMESPACE ),
                    'add_new'             => __( 'Add New', FPMTP__I18N_NAMESPACE ),
                    'edit_item'           => __( 'Edit Biography', FPMTP__I18N_NAMESPACE ),
                    'update_item'         => __( 'Update Biography', FPMTP__I18N_NAMESPACE ),
                    'search_items'        => __( 'Search Biographies', FPMTP__I18N_NAMESPACE ),
                    'not_found'           => __( 'Not found', FPMTP__I18N_NAMESPACE ),
                    'not_found_in_trash'  => __( 'Not found in Trash', FPMTP__I18N_NAMESPACE ),
                    'plugin_listing_table_title_cell_link'	=>	__( 'Biographies', FPMTP__I18N_NAMESPACE ),		// framework specific key. [3.0.6+]
                ),
                'public'			=>	true,
                'menu_position' 	=>	4,
                'menu_icon' => 'dashicons-groups',
                'supports'			=>	array( 'title', 'editor', 'thumbnail', 'excerpt' ), // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),	// 'custom-fields'
                'taxonomies'		=>	array(  ),
                'has_archive'		=>	true,
                'show_in_menu'      =>  true,
                'rewrite' => array( 'slug' => 'biographies', 'with_front' => false ),
                'show_admin_column' =>	true,	// this is for custom taxonomies to automatically add the column in the listing table.
                //'menu_icon'			=>	plugins_url( 'asset/image/wp-logo_16x16.png', APFDEMO_FILE ),
                // ( framework specific key ) this sets the screen icon for the post type for WordPress v3.7.1 or below.
                //'screen_icon'		=>	dirname( APFDEMO_FILE  ) . '/asset/image/wp-logo_32x32.png', // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', APFDEMO_FILE )
            )
        );

        // the setUp() method is too late to add taxonomies. So we use start_{class name} action hook.
        $aPostTypeSettings = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_bios' );
        if($aPostTypeSettings['fpmtp_enable_bios_communitymembers'] ) {
            $this->addTaxonomy(
                'fpmtp_communitymember', // taxonomy slug
                array(            // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                    'labels' => array(
                        'name' => 'Community Member',
                        'add_new_item' => 'Add Community Member',
                        'new_item_name' => "New Community Member"
                    ),
                    'show_ui' => true,
                    'show_tagcloud' => false,
                    'hierarchical' => true, // Hierarchical to allow 'Ajahn', 'Bhikkhu', and so on
                    'show_admin_column' => true,
                    'sortable' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array('slug' => 'community-members', 'with_front' => false),
                    'show_table_filter' => true,    // framework specific key
                    'show_in_sidebar_menus' => true,    // framework specific key
                )
            );
        }
        if($aPostTypeSettings['fpmtp_enable_bios_locations'] ) {
            $this->addTaxonomy(
                'fpmtp_locations', // taxonomy slug
                array(            // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                    'labels' => array(
                        'name' => 'Location',
                        'add_new_item' => 'Add Location',
                        'new_item_name' => "New Location"
                    ),
                    'show_ui' => true,
                    'show_tagcloud' => false,
                    'hierarchical' => false,
                    'show_admin_column' => true,
                    'sortable' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array('slug' => 'locations', 'with_front' => true),
                    'show_table_filter' => true,    // framework specific key
                    'show_in_sidebar_menus' => true,    // framework specific key
                )
            );
        }

        $this->setFooterInfoLeft( '<em>The construction and maintenance of this page has been offered as an act of <strong>Dhamma Dana</strong>.</em><br />For assistance, please email <a href="mailto:developer@fullpeace.org">the developer</a>.' );
        $this->setFooterInfoRight( '<br />Created for <a href="http://amaravati.org/" target="_blank" >Amaravati B.M.</a>' );

        //add_filter( 'the_content', array( $this, 'replyToPrintOptionValues' ) );

    }

    /*
     * Built-in callback methods
     */
    public function columns_fpmtp_bios( $aHeaderColumns ) {	// columns_{post type slug}

        return array_merge(
            $aHeaderColumns,
            array(
                'cb'			    => '<input type="checkbox" />',	// Checkbox for bulk actions.
                'title'			    => __( 'Title', FPMTP__I18N_NAMESPACE ),		// Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
                'date'			    => __( 'Date', FPMTP__I18N_NAMESPACE ), 	// The date and publish status of the post.
               // 'ordainedcolumn'    => __( 'Ordained', FPMTP__I18N_NAMESPACE ), 	// The date and publish status of the post.
            )
        );

    }

    public function sortable_columns_fpmtp_bios( $aSortableHeaderColumns ) { // sortable_columns_{post type slug}
        return $aSortableHeaderColumns + array(
            //'ordainedcolumn' => 'ordainedcolumn',
        );
    }

    public function cell_fpmtp_bios_ordainedcolumn( $sCell, $iPostID ) { // cell_{post type}_{column key}

        return  __( 'Ordained', FPMTP__I18N_NAMESPACE ) . ': ' . get_post_meta( $iPostID, 'year_ordained', true );

    }
    /**
     * Modifies the output of the post content.
     */
    public function replyToPrintOptionValues( $sContent ) {

        if ( ! isset( $GLOBALS['post']->ID ) || get_post_type() != 'fpmtp_bios' ) return $sContent;

        // 1. To retrieve the meta box data	- get_post_meta( $post->ID ) will return an array of all the meta field values.
        // or if you know the field id of the value you want, you can do $value = get_post_meta( $post->ID, $field_id, true );
        $iPostID = $GLOBALS['post']->ID;
        $aPostData = array();
        foreach( ( array ) get_post_custom_keys( $iPostID ) as $sKey ) 	// This way, array will be unserialized; easier to view.
            $aPostData[ $sKey ] = get_post_meta( $iPostID, $sKey, true );

        // 2. To retrieve the saved options in the setting pages created by the framework - use the get_option() function.
        // The key name is the class name by default. The key can be changed by passing an arbitrary string
        // to the first parameter of the constructor of the AdminPageFramework class.
        $aSavedOptions = get_option( 'FullPeace_Media_To_Post' );

        return $sContent;
		// . "<h3>" . __( 'Saved Meta Field Values', FPMTP__I18N_NAMESPACE ) . "</h3>"
        // . $this->oDebug->getArray( $aPostData )
        // . "<h3>" . __( 'Saved Setting Options', FPMTP__I18N_NAMESPACE ) . "</h3>"
        // . $this->oDebug->getArray( $aSavedOptions );

    }

} 