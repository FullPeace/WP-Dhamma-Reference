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
                    'name'                => _x( 'Bios', 'Post Type General Name', FPMTP__I18N_NAMESPACE ),
                    'singular_name'       => _x( 'Bio', 'Post Type Singular Name', FPMTP__I18N_NAMESPACE ),
                    'menu_name'           => __( 'Bios', FPMTP__I18N_NAMESPACE ),
                    'parent_item_colon'   => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
                    'all_items'           => __( 'All Bios', FPMTP__I18N_NAMESPACE ),
                    'view_item'           => __( 'View Bio', FPMTP__I18N_NAMESPACE ),
                    'add_new_item'        => __( 'Add New Bio', FPMTP__I18N_NAMESPACE ),
                    'add_new'             => __( 'Add New', FPMTP__I18N_NAMESPACE ),
                    'edit_item'           => __( 'Edit Bio', FPMTP__I18N_NAMESPACE ),
                    'update_item'         => __( 'Update Bio', FPMTP__I18N_NAMESPACE ),
                    'search_items'        => __( 'Search Bios', FPMTP__I18N_NAMESPACE ),
                    'not_found'           => __( 'Not found', FPMTP__I18N_NAMESPACE ),
                    'not_found_in_trash'  => __( 'Not found in Trash', FPMTP__I18N_NAMESPACE ),
                    'plugin_listing_table_title_cell_link'	=>	__( 'Bios', FPMTP__I18N_NAMESPACE ),		// framework specific key. [3.0.6+]
                ),
                'public'			=>	true,
                'menu_position' 	=>	5,
                'supports'			=>	array( 'title', 'editor', 'thumbnail', 'excerpt' ), // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),	// 'custom-fields'
                'taxonomies'		=>	array(  ),
                'has_archive'		=>	true,
                'rewrite' => array( 'slug' => 'bios', 'with_front' => false ),
                'show_admin_column' =>	true,	// this is for custom taxonomies to automatically add the column in the listing table.
                //'menu_icon'			=>	plugins_url( 'asset/image/wp-logo_16x16.png', APFDEMO_FILE ),
                // ( framework specific key ) this sets the screen icon for the post type for WordPress v3.7.1 or below.
                //'screen_icon'		=>	dirname( APFDEMO_FILE  ) . '/asset/image/wp-logo_32x32.png', // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', APFDEMO_FILE )
            )
        );


        $this->setFooterInfoLeft( '<br />Biography details for author, speakers and other members of the organisation.' );
        $this->setFooterInfoRight( '<br />Created for <a href="http://amaravati.org/" target="_blank" >Amaravati B.M.</a>' );

        add_filter( 'the_content', array( $this, 'replyToPrintOptionValues' ) );

    }

    /*
     * Built-in callback methods
     */
    public function columns_fpmtp_bios( $aHeaderColumns ) {	// columns_{post type slug}

        return array_merge(
            $aHeaderColumns,
            array(
                'cb'			=> '<input type="checkbox" />',	// Checkbox for bulk actions.
                'title'			=> __( 'Title', FPMTP__I18N_NAMESPACE ),		// Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
                'date'			=> __( 'Date', FPMTP__I18N_NAMESPACE ), 	// The date and publish status of the post.
            )
        );

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

        return "<h3>" . __( 'Saved Meta Field Values', FPMTP__I18N_NAMESPACE ) . "</h3>"
        . $this->oDebug->getArray( $aPostData )
        . "<h3>" . __( 'Saved Setting Options', FPMTP__I18N_NAMESPACE ) . "</h3>"
        . $this->oDebug->getArray( $aSavedOptions );

    }

} 