<?php

/**
 * Ported from the PDF Metabox plugin by Juan Padial (http://curiosoando.com/)
 * based on this SO answer: http://wordpress.stackexchange.com/questions/112559/wordpress-custom-upload-field-error
 * 
 * This class takes care of the special features of the eBook custom post type,
 * by allowing to attachment of files (PDF, MOBI, EPUB) to the post.
 * 
 * @since 0.1.0
 */

class FullPeace_Media_To_Post_eBook
{

    /**
     * This is our constructor
     *
     * @return FullPeace_Media_To_Post_eBook
     */
    public static function __construct() {

        add_action( 'post_mime_types',         array( __CLASS__, 'ebook_mime_types'    )    );
        add_action( 'add_meta_boxes',          array( __CLASS__, 'admin_scripts'   ), 5 );
        add_action( 'add_meta_boxes',          array( __CLASS__, 'metabox_add'     )    );
        add_action( 'save_post',               array( __CLASS__, 'ebook_save_postdata') );
        add_action( 'wp_ajax_refresh_pdf',     array( __CLASS__, 'refresh_pdf' )    );

    }

    public static function ebook_mime_types() {
        $post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
        $post_mime_types['application/application/x-mobipocket-ebook'] = array( __( 'MOBIs' ), __( 'Manage MOBIs' ), _n_noop( 'MOBI <span class="count">(%s)</span>', 'MOBIs <span class="count">(%s)</span>' ) );
        $post_mime_types['application/epub+zip'] = array( __( 'EPUBs' ), __( 'Manage EPUBs' ), _n_noop( 'EPUB <span class="count">(%s)</span>', 'EPUBs <span class="count">(%s)</span>' ) );
        return $post_mime_types;
    }

    public static function admin_scripts() {

        wp_register_script( 'ebook-metabox-js', plugins_url( '/assets/js/ebook-metabox.js', __FILE__ ) , array( 'jquery' ), null, true );

    }

    public static function  metabox_add() {
        // Filterable metabox settings.
        $post_types		= array( FullPeace_Media_To_Post::get_slug( 'eBooks') );
        $context		= 'normal';
        $priority		= 'low';

        // Loop through all post types
        foreach( $post_types as $post_type ) {

            // Add Metaboxes
            add_meta_box( FullPeace_Media_To_Post::get_slug('ebook_metaboxes'), __( 'PDF', FPMTP__I18N_NAMESPACE ), array( __CLASS__, 'ebook_metaboxes' ), $post_type, $context, $priority );
            add_meta_box( FullPeace_Media_To_Post::get_slug('mobi_metabox'), __( 'MOBI', FPMTP__I18N_NAMESPACE ), array( __CLASS__, 'ebook_metaboxes' ), $post_type, $context, $priority );
            add_meta_box( FullPeace_Media_To_Post::get_slug('epub_metabox'), __( 'EPUB', FPMTP__I18N_NAMESPACE ), array( __CLASS__, 'ebook_metaboxes' ), $post_type, $context, $priority );
            // Add Necessary Scripts and Styles
            wp_enqueue_media();
            wp_enqueue_script( 'ebook-metabox-js' );

        }
    }


    public static function ebook_metaboxes( $post ) {
        $original_post = $post;
        echo self::ebook_metaboxes_html( $post->ID );
        $post = $original_post;
    }

    public static function get_item_link( $id ) {
        if(!$id) return;

        $item = '<a href="'.wp_get_attachment_url($id).'">'.get_the_title($id).'</a>';

        return $item;

    }


    public static function ebook_metaboxes_html( $post_id ) {

        $post_meta = get_post_custom($post_id);
        $return = '';
        $ebook_types = array('PDF','EPUB','MOBI');
        foreach ($ebook_types as $t) 
        {
            $lo = str_tolower($t); // Short for lower case usage

            $current_value = '';
            if( isset($post_meta[$lo . '-id'][0] ) ) $current_value = $post_meta[$lo . '-id'][0];
            //Nonce for verification
            wp_nonce_field( plugin_basename( __FILE__ ), 'pdf_noncename' );

            $return	 .= '<p>';
            $return  .= '<a title="'.__( $t, FPMTP__I18N_NAMESPACE ).'" class="button button-primary insert-' . $lo . '-button" id="insert-' . $lo . '-button" href="#" style="float:left">'.__( 'Upload ' . $t, FPMTP__I18N_NAMESPACE ).'</a><span id="' . $lo . '-spinner" class="spinner" style="float:left"></span></p>';
            $return  .= '<div style="clear:both"></div>';
            $return  .= '<input type="hidden" name="' . $lo . '-id" id="' . $lo . '-id" value="'.$current_value.'">';
            $return  .= '<div style="clear:both"></div>';

            $return .= '<div id="' . $lo . '-wrapper">';

            $item = self::get_item_link( $current_value );
            if( empty( $item ) ) {
                $return .= '<p>No ' . $lo . '.</p>';
            } else {
                $return .= $item;
            }

            $return .= '</div>';
        }

        return $return;
    }


    public static  function ebook_save_postdata($post_id){

        // First we need to check if the current user is authorised to do this action.
        //Currently capabilities of property post type is the same as normal post type
        if ( isset($_POST['post_type']) && 'post' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_post', $post_id ) ) return;
        }

        // Secondly we need to check if the user intended to change this value.
        if ( !isset( $_POST['pdf_noncename'] ) || ! wp_verify_nonce( $_POST['pdf_noncename'], plugin_basename( __FILE__ ) ) )
            return;

        // Thirdly we can save the value to the database
        $ebook_types = array('pdf','epub','mobi');
        foreach ($ebook_types as $t) {
            if (isset($_POST[$t . '-id'])):
                //Don't forget sanitize
                update_post_meta($post_id, $t . '-id', sanitize_text_field($_POST[$t . '-id']));
            else:
                if (isset($post_id)) {
                    delete_post_meta($post_id, $t . '-id');
                }
            endif;
        }
    }


    public static function  refresh_pdf() {
        if(isset($_POST['id'])){
            $item = $_POST['id'];
            if($item != '' && $item !=0){
                $pdf = self::get_item_link( $item );
                $ret = array();

                if( !empty( $pdf ) ) {
                    $ret['success'] = true;
                    $ret['pdf'] = $pdf;
                } else {
                    $ret['success'] = false;
                }
            } else {
                //Is success but the $_POST['ids'] is empty, maybe deleting detaching files so:
                $ret['success'] = true;
                $ret['pdf'] = '';
            }
        } else {
            $ret['success'] = false;
        }

        echo json_encode( $ret );
        die();

    }

}