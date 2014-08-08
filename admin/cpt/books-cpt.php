<?php

class FullPeace_Books_PostType  extends AdminPageFramework_PostType {

    /**
     * This method is called at the end of the constructor.
     *
     */
    public function start() {

        $this->setAutoSave( true );
        $this->setAuthorTableFilter( true );

        $this->setPostTypeArgs(
            array(			// argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels' => array(
                    'name'			=>	'Books',
                    'all_items' 	=>	__( 'All Books', FPMTP__I18N_NAMESPACE ),
                    'singular_name' =>	'Book',
                    'add_new'		=>	__( 'Add New', FPMTP__I18N_NAMESPACE ),
                    'add_new_item'	=>	__( 'Add New Book', FPMTP__I18N_NAMESPACE ),
                    'edit'			=>	__( 'Edit', FPMTP__I18N_NAMESPACE ),
                    'edit_item'		=>	__( 'Edit Book', FPMTP__I18N_NAMESPACE ),
                    'new_item'		=>	__( 'New Book', FPMTP__I18N_NAMESPACE ),
                    'view'			=>	__( 'View', FPMTP__I18N_NAMESPACE ),
                    'view_item'		=>	__( 'View Book', FPMTP__I18N_NAMESPACE ),
                    'search_items'	=>	__( 'Search Books', FPMTP__I18N_NAMESPACE ),
                    'not_found'		=>	__( 'No Book found', FPMTP__I18N_NAMESPACE ),
                    'not_found_in_trash' => __( 'No Book found in Trash', FPMTP__I18N_NAMESPACE ),
                    'parent'		=>	__( 'Parent Book', FPMTP__I18N_NAMESPACE ),
                    'plugin_listing_table_title_cell_link'	=>	__( 'Books', FPMTP__I18N_NAMESPACE ),		// framework specific key. [3.0.6+]
                ),
                'public'			=>	true,
                'menu_position' 	=>	5,
                'supports'			=>	array( 'title', 'editor', 'thumbnail', 'excerpt' ), // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),	// 'custom-fields'
                'taxonomies'		=>	array( 'categories', 'fpmtp_authors_taxonomy' , 'fpmtp_year_taxonomy', 'fpmtp_languages' ),
                'has_archive'		=>	true,
                'rewrite' => array( 'slug' => 'books', 'with_front' => false ),
                'show_admin_column' =>	true,	// this is for custom taxonomies to automatically add the column in the listing table.
                //'menu_icon'			=>	plugins_url( 'asset/image/wp-logo_16x16.png', APFDEMO_FILE ),
                // ( framework specific key ) this sets the screen icon for the post type for WordPress v3.7.1 or below.
                //'screen_icon'		=>	dirname( APFDEMO_FILE  ) . '/asset/image/wp-logo_32x32.png', // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', APFDEMO_FILE )
            )
        );

        // the setUp() method is too late to add taxonomies. So we use start_{class name} action hook.
        $aPostTypeSettings = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_books' );
        if($aPostTypeSettings['fpmtp_enable_books_authors'] ) {
            $this->addTaxonomy(
                'fpmtp_authors_taxonomy', // taxonomy slug
                array(            // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                    'labels' => array(
                        'name' => 'Author',
                        'add_new_item' => 'Add Author',
                        'new_item_name' => "New Author"
                    ),
                    'show_ui' => true,
                    'show_tagcloud' => false,
                    'hierarchical' => true, // Hierarchical to allow 'Ajahn Sumedho', 'Luang Por Sumedho', and so on
                    'show_admin_column' => true,
                    'sortable' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array('slug' => 'authors', 'with_front' => false),
                    'show_table_filter' => true,    // framework specific key
                    'show_in_sidebar_menus' => true,    // framework specific key
                )
            );
        }
        if($aPostTypeSettings['fpmtp_enable_books_languages'] ) {
            $this->addTaxonomy(
                'fpmtp_languages', // taxonomy slug
                array(            // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                    'labels' => array(
                        'name' => 'Languages',
                        'add_new_item' => 'Add New Language',
                        'new_item_name' => "New Language"
                    ),
                    'show_ui' => true,
                    'show_tagcloud' => true,
                    'hierarchical' => false,
                    'show_admin_column' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array('slug' => 'languages', 'with_front' => false),
                    'show_table_filter' => true,    // framework specific key
                    'show_in_sidebar_menus' => true,    // framework specific key
                )
            );
        }
        if($aPostTypeSettings['fpmtp_enable_books_year'] ) {
            $this->addTaxonomy(
                'fpmtp_year_taxonomy',
                array(
                    'labels' => array(
                        'name' => 'Year published',
                        'add_new_item' => 'Add Year',
                        'new_item_name' => "New Year"
                    ),
                    'show_ui' => true,
                    'show_tagcloud' => false,
                    'hierarchical' => false,
                    'show_admin_column' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array('slug' => 'books-by-year', 'with_front' => false),
                    'show_table_filter' => true,    // framework specific key
                    'show_in_sidebar_menus' => false,    // framework specific key
                )
            );
        }

        $this->setFooterInfoLeft( '<br />Custom Text on the left hand side.' );
        $this->setFooterInfoRight( '<br />Custom text on the right hand side' );

        add_filter( 'the_content', array( $this, 'replyToPrintOptionValues' ) );

        add_filter( 'request', array( $this, 'replyToSortCustomColumn' ) );

    }

    /*
     * Built-in callback methods
     */
    public function columns_fpmtp_books( $aHeaderColumns ) {	// columns_{post type slug}

        return array_merge(
            $aHeaderColumns,
            array(
                'cb'			=> '<input type="checkbox" />',	// Checkbox for bulk actions.
                'thumbnail' => __( 'Thumbnail', FPMTP__I18N_NAMESPACE ),
                'title'			=> __( 'Title', FPMTP__I18N_NAMESPACE ),		// Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
                //'fpmtp_authors_taxonomy'		=> __( 'Author', FPMTP__I18N_NAMESPACE ),		// Post author.
                'categories'	=> __( 'Categories', FPMTP__I18N_NAMESPACE ),	// Categories the post belongs to.
                // 'tags'		=> __( 'Tags', FPMTP__I18N_NAMESPACE ),	// Tags for the post. 
                // 'comments' 		=> '<div class="comment-grey-bubble"></div>', // Number of pending comments.
                //'date'			=> __( 'Date', FPMTP__I18N_NAMESPACE ), 	// The date and publish status of the post.
                //'fpmtp_year_taxonomy'			=> __( 'Year published' ),
            )
        );

    }
    public function sortable_columns_fpmtp_books( $aSortableHeaderColumns ) {	// sortable_columns_{post type slug}
        return $aSortableHeaderColumns + array(
            'title' => 'title',
            'thumbnail' => 'thumbnail',
            'fpmtp_authors_taxonomy' => 'fpmtp_authors_taxonomy',
            'fpmtp_year_taxonomy' => 'fpmtp_year_taxonomy',
        );
    }

    /**
     * Custom callback methods
     */

    /**
     * Modifies the way how the sample column is sorted. This makes it sorted by post ID.
     *
     * @see			http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
     */
    public function replyToSortCustomColumn( $aVars ){

//        if ( isset( $aVars['orderby'] ) && 'fpmtp_authors_taxonomy' == $aVars['orderby'] ){
//            $aVars = array_merge(
//                $aVars,
//                array(
//                    'meta_key'	=>	'metabox_text_field',
//                    'orderby'	=>	'meta_value',
//                )
//            );
//        }
//        if ( isset( $aVars['orderby'] ) && 'fpmtp_year_taxonomy' == $aVars['orderby'] ){
//            $aVars = array_merge(
//                $aVars,
//                array(
//                    'meta_key'	=>	'metabox_text_field',
//                    'orderby'	=>	'meta_value',
//                )
//            );
//        }
        return $aVars;
    }

    /**
     * Modifies the output of the post content.
     */
    public function replyToPrintOptionValues( $sContent ) {

        if ( ! isset( $GLOBALS['post']->ID ) || get_post_type() != 'fpmtp_books' ) return $sContent;

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
        $sAuthorLinks = "";
        $authors = wp_get_object_terms($GLOBALS['post']->ID,"fpmtp_authors_taxonomy");
        if ( !empty( $authors ) && !is_wp_error( $authors ) ){
            foreach ( $authors as $term ) {
                if( !empty($sAuthorLinks) ) $sAuthorLinks .= ", ";
                $sAuthorLinks .= '<a href="'.get_term_link( $term ).'" title="' . sprintf(__('View all books by %s', FPMTP__I18N_NAMESPACE), $term->name) . '">' . $term->name . "</a>";
            }
            $sAuthorLinks = "<p>" . __( 'Author:', FPMTP__I18N_NAMESPACE ) .' '. $sAuthorLinks . "</p>";
        }
        $sUploadedMedia = ": ";
        if(!empty($aPostData['upload_media']) && !empty($aPostData['upload_media']['media_field'])) {
                foreach ($aPostData['upload_media']['media_field'] as $key => $sFileUrl) {
                    $sUploadedMedia .= ' <a href="' . $sFileUrl . '" title="' . $GLOBALS['post']->post_title . '"><img src="' . FPMTP__PLUGIN_URL . "assets/img/" . substr(strrchr($sFileUrl, "."), 1) . '.png"></a>';
                }
            $sUploadedMedia = "<h3>" . __( 'Download', FPMTP__I18N_NAMESPACE ) .' '. $sUploadedMedia . "</h3>";
        }

        return $sContent . $sAuthorLinks . $sUploadedMedia;
        //. $this->oDebug->getArray( $aPostData )
        //. $this->oDebug->getArray( $aSavedOptions );

    }

}