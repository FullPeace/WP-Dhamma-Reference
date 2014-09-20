<?php
/**
 * The plugin's Custom Post Types
 *
 * @package FPMTP
 *
 * The Custom Post Types used by the FullPeace Media To Post plugin
 * to create posts based on uploads in the media library.
 *
 * @since    0.1.0
 */

class FullPeace_Audio_PostType  extends AdminPageFramework_PostType {
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
                'name'                => _x( 'Dhamma Talks', 'Post Type General Name', FPMTP__I18N_NAMESPACE ),
                'singular_name'       => _x( 'Dhamma Talk', 'Post Type Singular Name', FPMTP__I18N_NAMESPACE ),
                'menu_name'           => __( 'Dhamma Talks', FPMTP__I18N_NAMESPACE ),
                'parent_item_colon'   => __( 'Parent Item:', FPMTP__I18N_NAMESPACE ),
                'all_items'           => __( 'All Dhamma Talks', FPMTP__I18N_NAMESPACE ),
                'view_item'           => __( 'View Talk', FPMTP__I18N_NAMESPACE ),
                'add_new_item'        => __( 'Add New Dhamma Talk', FPMTP__I18N_NAMESPACE ),
                'add_new'             => __( 'Add New', FPMTP__I18N_NAMESPACE ),
                'edit_item'           => __( 'Edit Talk', FPMTP__I18N_NAMESPACE ),
                'update_item'         => __( 'Update Talk', FPMTP__I18N_NAMESPACE ),
                'search_items'        => __( 'Search Talk', FPMTP__I18N_NAMESPACE ),
                'not_found'           => __( 'Not found', FPMTP__I18N_NAMESPACE ),
                'not_found_in_trash'  => __( 'Not found in Trash', FPMTP__I18N_NAMESPACE ),
                'plugin_listing_table_title_cell_link'	=>	__( 'Dhamma Talk', FPMTP__I18N_NAMESPACE ),		// framework specific key. [3.0.6+]
            ),
            'public'			=>	true,
            'menu_position' 	=>	4,
            'menu_icon' => 'dashicons-media-audio',
            'supports'			=>	array( 'title', 'editor', 'thumbnail', 'excerpt' ), // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),	// 'custom-fields'
            'taxonomies'		=>	array( 'category', 'fpmtp_speakers', 'fpmtp_series', 'fpmtp_languages' ),
            'has_archive'		=>	true,
            'show_in_menu'      =>  true,
            'rewrite' => array( 'slug' => 'audio', 'with_front' => false ),
            'show_admin_column' =>	true,	// this is for custom taxonomies to automatically add the column in the listing table.
            //'menu_icon'			=>	plugins_url( 'asset/image/wp-logo_16x16.png', APFDEMO_FILE ),
            // ( framework specific key ) this sets the screen icon for the post type for WordPress v3.7.1 or below.
            //'screen_icon'		=>	dirname( APFDEMO_FILE  ) . '/asset/image/wp-logo_32x32.png', // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', APFDEMO_FILE )
        )
    );

    // the setUp() method is too late to add taxonomies. So we use start_{class name} action hook.
    $aPostTypeSettings = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_audio' );
    if($aPostTypeSettings['fpmtp_enable_audio_languages']) {
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
    if($aPostTypeSettings['fpmtp_enable_audio_speakers']) {
        $this->addTaxonomy(
            'fpmtp_speakers', // taxonomy slug
            array(            // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                'labels' => array(
                    'name' => 'Speakers',
                    'add_new_item' => 'Add New Speaker',
                    'new_item_name' => "New Speaker"
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'rewrite' => array('slug' => 'speakers', 'with_front' => false),
                'show_table_filter' => true,    // framework specific key
                'show_in_sidebar_menus' => true,    // framework specific key
            )
        );
    }
    if($aPostTypeSettings['fpmtp_enable_audio_series']) {
        $this->addTaxonomy(
            'fpmtp_series',
            array(
                'labels' => array(
                    'name' => 'Series',
                    'add_new_item' => 'Add New Series',
                    'new_item_name' => "New Series"
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'rewrite' => array('slug' => 'series', 'with_front' => false),
                'show_table_filter' => true,    // framework specific key
                'show_in_sidebar_menus' => true,    // framework specific key
            )
        );
    }

    $this->setFooterInfoLeft( '<br />For assistance, please email <a href="mailto:developer@fullpeace.org">the developer</a>.' );
    $this->setFooterInfoRight( '<br />Created for <a href="http://amaravati.org/" target="_blank" >Amaravati B.M.</a>' );

    add_filter( 'the_content', array( $this, 'replyToPrintOptionValues' ) );

    add_filter( 'request', array( $this, 'replyToSortCustomColumn' ) );

}

/*
 * Built-in callback methods
 */
public function columns_fpmtp_audio( $aHeaderColumns ) {	// columns_{post type slug}

    return array_merge(
        $aHeaderColumns,
        array(
            'cb'			=> '<input type="checkbox" />',	// Checkbox for bulk actions. 
            'title'			=> __( 'Title', FPMTP__I18N_NAMESPACE ),		// Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
            //'speakers'  => __( 'Speakers', FPMTP__I18N_NAMESPACE ),		// eBook author, not the post author.
            'categories'	=> __( 'Categories', FPMTP__I18N_NAMESPACE ),	// Categories the post belongs to.
            // 'tags'		=> __( 'Tags', FPMTP__I18N_NAMESPACE ),	// Tags for the post. 
            //'comments' 		=> '<div class="comment-grey-bubble"></div>', // Number of pending comments.
            'date'			=> __( 'Date', FPMTP__I18N_NAMESPACE ), 	// The date and publish status of the post. 
            //'series'			=> __( 'Series' ),
				'shortcodecolumn' => __( 'Shortcode' ),
        )
    );

}
public function sortable_columns_fpmtp_audio( $aSortableHeaderColumns ) {	// sortable_columns_{post type slug}
    return $aSortableHeaderColumns + array(
        'fpmtp_speakers' => 'fpmtp_speakers',
        'fpmtp_series' => 'fpmtp_series',
    );
}
public function cell_fpmtp_audio_series( $sCell, $iPostID ) {	// cell_{post type}_{column key}
    return sprintf( __( 'Post ID: %1$s', FPMTP__I18N_NAMESPACE ), $iPostID ) . "<br />"
    . __( 'Text', FPMTP__I18N_NAMESPACE ) . ': ' . get_post_meta( $iPostID, 'metabox_text_field', true );
}
public function cell_fpmtp_audio_shortcodecolumn( $sCell, $iPostID ) { // cell_{post type}_{column key}
	return '[dhamma include="'.$iPostID.'"]';
}

/**
 * Custom callback methods
 */

/**
 * Modifies the way how the talk series column is sorted. This makes it sorted by post ID.
 *
 * @see			http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 */
public function replyToSortCustomColumn( $aVars ){

//    if ( isset( $aVars['orderby'] ) && 'fpmtp_series' == $aVars['orderby'] ){
//        $aVars = array_merge(
//            $aVars,
//            array(
//                'meta_key'	=>	'metabox_text_field',
//                'orderby'	=>	'meta_value',
//            )
//        );
//    }elseif ( isset( $aVars['orderby'] ) && 'fpmtp_speakers' == $aVars['orderby'] ){
//        $aVars = array_merge(
//            $aVars,
//            array(
//                'meta_key'	=>	'metabox_text_field',
//                'orderby'	=>	'meta_value',
//            )
//        );
//    }
    return $aVars;
}

/**
 * Modifies the output of the post content.
 */
public function replyToPrintOptionValues( $sContent ) {

    if ( ! isset( $GLOBALS['post']->ID ) || get_post_type() != 'fpmtp_audio' ) return $sContent;

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
//	 . "<!--\n<h3>" . __( 'Saved Meta Field Values', FPMTP__I18N_NAMESPACE ) . "</h3>  \n"
//     . $this->oDebug->getArray( $aPostData )
//     . "\n<h3>" . __( 'Saved Setting Options', FPMTP__I18N_NAMESPACE ) . "</h3>\n"
//     . $this->oDebug->getArray( $aSavedOptions )
//    . "\n<h3>" . __( 'Post data', FPMTP__I18N_NAMESPACE ) . "</h3>\n"
//    . "\n<pre>" . var_export($GLOBALS['post'],true) . "</pre>\n"
//    . "\n<h3>" . __( 'Attached media', FPMTP__I18N_NAMESPACE ) . "</h3>\n"
//    . "\n<pre>" . var_export( get_attached_media( 'audio' ),true) . "</pre>\n"
//     . "\n-->";

}

}