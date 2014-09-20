<?php
/**
 * The FullPeace Media To Posts Admin defines all functionality of the plugin
 *
 * @package FPMTP
 *
 * The FullPeace Media To Posts Admin defines all functionality of the plugin.
 *
 * This class defines the meta box used to display the post meta data and registers
 * the style sheet responsible for styling the content of the meta box.
 *
 * @since    0.1.0
 *
 */
class FullPeace_Media_To_Post {

    /**
     * @var bool
     */
    private static $initiated = false;

    public static $slug = 'fpmtp';

    /**
     * Initiates the plugin
     */
    public static function init()
    {
        if ( ! self::$initiated )
        {
            self::init_hooks();
        }
    }

    public static function isPluginPost($sPostTypeName){
        $validPostTypes = array("fpmtp_bios", "fpmtp_books", "fpmtp_audio");
        return in_array($sPostTypeName, $validPostTypes);
    }

    /**
     * Initializes WordPress hooks
     * @todo Add check for enabled CPTs and load them based on that.
     * @todo Special case for eBooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;
        add_action( 'admin_notices', array( 'FullPeace_Media_To_Post_Admin', 'display_notice' ) );
        add_action( 'add_attachment', array( 'FullPeace_Media_To_Post', 'post_from_attachment' ) );
		
		add_action('admin_head', array( 'FullPeace_Media_To_Post','hide_add_buttons'));
        add_filter( 'query_vars', array( 'FullPeace_Media_To_Post','add_query_vars_filter' ) );
        add_shortcode( 'audio_series', array( 'FullPeace_Media_To_Post','shortcode_series') );
        add_shortcode( 'bios', array( 'FullPeace_Media_To_Post','shortcode_bio') );
        add_shortcode( 'dhamma', array( 'FullPeace_Media_To_Post','shortcode_dhamma') );
        add_action( 'save_post',  'FullPeace_Media_To_Post_plugin_post_save' );

        add_filter('the_excerpt_rss', array( 'FullPeace_Media_To_Post','featuredtoRSS'));
        add_filter('the_content_feed', array( 'FullPeace_Media_To_Post','featuredtoRSS'));
        add_action( "rss_item", array(  'FullPeace_Media_To_Post', "feed_addMeta" ), 5, 1 );
        add_action( "rss2_item", array(  'FullPeace_Media_To_Post', "feed_addMeta" ), 5, 1 );
        // Remove this, instead encourage custom templates
        //add_filter( 'template_include', array( 'FullPeace_Media_To_Post', 'template_chooser' ) );

//        require_once FPMTP__PLUGIN_DIR . 'admin/class.types.php';
//        FullPeace_Media_To_Post_Types::register_custom_post_types();
//        FullPeace_Media_To_Post_Types::register_custom_taxonomies();

        require_once FPMTP__PLUGIN_DIR . 'public/class.public.php';
        add_action( 'plugins_loaded', array( 'FullPeace_Media_To_Post_Public', 'init' ) );
    }
	
	// hide "add new" button on edit page
	public static function hide_add_buttons() {
	  global $pagenow;
	  if(is_admin()){
		if( ($pagenow == 'edit.php' || $pagenow == 'post.php') && $_GET['post_type'] == 'fpmtp_audio'){
			echo '<style>.add-new-h2{display: none;}</style>';
		}  
		if( ($pagenow == 'post-new.php' || $pagenow == 'post.php') && $_GET['post_type'] == 'fpmtp_books'){
			remove_action( 'media_buttons', 'media_buttons' );
		}  
	  }
	}

    /**
     * Enable podcasting.
     *
     * Source: http://wordpress.stackexchange.com/questions/85800/add-audio-attachment-link-to-rss
     *
     * @since 0.1.15
     */
    public static function featuredtoRSS($content) {
        global $post;
//
//        if ( has_post_thumbnail( $post->ID ) ){
//            $content = '' . get_the_post_thumbnail( $post->ID, 'topImage', array( 'style' =>  'margin:0 auto; border: 1px solid #555; display:block;' ) ) . '' . $content;
//        }

        $audios =& get_children( 'post_type=attachment&post_parent='.$post->ID.'&post_mime_type=audio' );
        foreach ( $audios as $id => $audio ){
            $content.='<a href="'.wp_get_attachment_url($id).'" target="_blank">'.$audio->post_title.'</a> ';
        }

        return $content;
    }

    /**
     * Add stuff to RSS items
     * @param $for_comments
     * @since 0.1.15
     */
    public function feed_addMeta($for_comments) {
        global $post;

        if($post->post_type!='fpmtp_audio')
            return;
        if(!$for_comments) {
            $audios =& get_children( 'post_type=attachment&post_parent='.$post->ID.'&post_mime_type=audio' );
            if ($audios) {
                foreach ($audios as $audio_id => $audio) {
                    echo '<enclosure url="' . wp_get_attachment_url($audio_id) . '" length="' . @filesize( get_attached_file( $audio_id ) ) . '" type="'. get_post_mime_type( $audio->ID ).'" />' . "\n";
                }

                if ( has_post_thumbnail($post->ID) ) {
                    $thumbnail_id = get_post_thumbnail_id( $post->ID );
                    $thumb = wp_get_attachment_image_src($thumbnail_id);
                    echo '<media:thumbnail xmlns:media="http://search.yahoo.com/mrss/" url="'. $thumb[0] . '" width="' . $thumb[1] . '" height="' . $thumb[2] . '" />' . "\n";
                }

            }
        }
    }

    // Add a shortcode that executes our function
    public static function shortcode_dhamma( $atts )
    {
        $include = false;
        $type = false;

        $result = '<!-- include or type param required -->';
        extract( shortcode_atts( array(
            'include' => 0,
            'type' => 0,
        ), $atts, 'dhamma' ) );

		if(!$include && !$type){return $result;}

        $posts = array();
        if($include) {
            $post = get_post($include);

            if ($post) {
                $posts[] = $post;
                $result = '';
            }
        }elseif($type){
            switch($type){
                case 'talks':
                    $type = 'fpmtp_audio';
                    break;
                case 'books':
                    $type = 'fpmtp_books';
                    break;
                case 'bios':
                    $type = 'fpmtp_bios';
                    break;
                default:
                    break;
            }

            $args=array(
                'post_type' => $type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            $posts = get_posts($args);
        }
        foreach($posts as $post):
            $result .= '
            <article  id="' . $post->post_name . '" class="dhamma-included ' . $post->post_type . '">';

            if (has_post_thumbnail($post->ID)) {
                $result .= '<div class="dhamma-include-featured">';
                $result .= '<a href="' . get_post_permalink($post->ID) . '" class=""><img src="' . wp_get_attachment_url(get_post_thumbnail_id($post->ID)) . '" class=""></a>';
                $result .= '</div>';
            }
            $result .= '
                  <h2 class="dhamma-include-header">
                    <a href="' . get_post_permalink($post->ID) . '" title="' . $post->post_title . '">' . ((strlen($post->post_title) > 53) ? substr($post->post_title, 0, 50) . '...' : $post->post_title) . '</a>
                  </h2>
                    <div class="dhamma-include-content">
          ';
            $result .= do_shortcode($post->post_content);
            $result .= '
                    </div>';

            $result .= '
            </article><!-- #' . $post->post_name . ' -->';
        endforeach;

        return $result;
    }

    // Add a shortcode that executes our function
    public static function shortcode_series( $atts )
    {
        $container_id = false;
        $container_class = false;
        $thumb_class = false;
        $img_class = false;
        extract( shortcode_atts( array(
            'container_id' => 'all-audio-series',
            'container_class' => 'x-iso-container x-iso-container-portfolio cols-4 isotope',
            'thumb_class' => 'entry-thumb',
            'img_class' => 'attachment-entry-renew wp-post-image'
        ), $atts, 'audio_series' ) );

	   $audio_series_query = get_transient('fpmtp_audio_series_query');
	   if ( !$audio_series_query ) {
           $audio_series_query = FullPeace_Media_To_Post::audio_series_cache();
       }
	    
	   $thumbnail = 'audio_series_thumbnail';
        if(!isset($result))
            $result = "";

        $result .= '<div id="' .  $container_id . '" class="' .  $container_class . '">';
//        $result .= '<!--';
//        $result .=  print_r($audio_series_query, true);
//        $result .= '-->';

           foreach ( $audio_series_query as $series_cat ) {
               if ( $series_cat[$thumbnail] ) {
                   $result .= '<article style="opacity:1;float:left;" class="x-portfolio type-x-portfolio status-publish has-post-thumbnail hentry has-post-thumbnail">';
                   $result .= '<div class="entry-featured">';
                   $result .= '<a href="' .  $series_cat['term_link'] . '" class="' .  $thumb_class . '"><img src="' .  $series_cat[$thumbnail] . '" class="' .  $img_class . '"></a>';
                   $result .= '<div class="entry-cover">
        <div class="entry-cover-content">
          <span>'.__('Audio', FPMTP__I18N_NAMESPACE ).'</span>
          <h2 class="entry-title entry-title-portfolio">
            <a href="'.$series_cat['term_link'].'" title="'.$series_cat['name'].'">'.((strlen($series_cat['name'])>53)?substr($series_cat['name'], 0, 50).'...':$series_cat['name']).'</a>
          </h2>
        </div>
      </div>';
                   //$result .= '<h3><a href="' .  $series_cat['term_link'] . '" class="title-overlay">' .  $series_cat['name'] . '</a></h3>';
                   $result .= '</div>';
                   $result .= '</article>';
               }
//               else{
//
//                   $result .= '<!-- cat ';
//                   $result .=  print_r($series_cat, true);
//                   $result .= '-->';
//               }
           }

        $result .= '</div><!-- #' .  $container_id . ' -->';

        return $result;
    }

    public static function  audio_series_cache() {

        /* Retrieves all the terms from the taxonomy portfolio_category
         *  http://codex.wordpress.org/Function_Reference/get_categories
         */

        $args = array(
            'type' => 'fpmtp_audio',
            'orderby' => 'date',
            'order' => 'DESC',
            'taxonomy' => 'fpmtp_series');

        $categories = get_categories( $args );

        $audio_series_query = array();

        /* Pulls the first post from each of the individual categories */

        foreach( $categories as $category ) {

            $args = array(
                'posts_per_page' => 1,
                'post_type' => 'fpmtp_audio',
                'fpmtp_series' => $category->slug,
                'no_found_rows' => true,
                'meta_query' => array(array('key' => '_thumbnail_id')) ,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false
            );
            $the_query = new WP_Query( $args );

            // The Loop
            while ( $the_query->have_posts() ) : $the_query->the_post();

                $series_thumbnail = null;
                $series_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id());

                /* All the data pulled is saved into an array which we'll save later */

                $audio_series_query[$category->slug] = array(
                    'name' => $category->name,
                    'term_link' =>  esc_attr( get_term_link( $category->slug, 'fpmtp_series' ) ),
                    'audio_series_thumbnail' => $series_thumbnail[0],
                );

            endwhile;
        }

        // Reset Post Data
        wp_reset_postdata();

        set_transient( 'fpmtp_audio_series_query', $audio_series_query );

        return $audio_series_query;
    }

    public static function  series_playlist_shortcode_cache($termSlug) {

        $audio_series_query = array();

        $result = '';

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'fpmtp_audio',
            'fpmtp_series' => $termSlug,
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        );
        $the_query = new WP_Query( $args );

        // The Loop
        while ( $the_query->have_posts() ) :

            $the_query->the_post();

            $series_thumbnail = null;
            $series_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id());

            /* All the data pulled is saved into an array which we'll save later */

            $attached_media = get_attached_media( 'audio' );

            $audio_trac = reset($attached_media);
            $audio_series_query[] = '[wpse_trac title="'.get_the_title().'" src="'.str_replace("https:","",$audio_trac->guid).'"]';

        endwhile;


        // Reset Post Data
        wp_reset_postdata();

        if(!empty($audio_series_query))
        {
            $result = '[wpse_playlist type="audio" current="no" tracklist="yes" tracknumbers="no" images="yes" artist="yes"]' . "\n" . implode("\n", $audio_series_query)."\n".'[/wpse_playlist]';
        }
        set_transient( 'fpmtp_audio_series_query_'.$termSlug, $result );

        return $result;
    }

    public static function shortcode_bio($atts)
    {
        //ob_start();

        $order = 'ASC';
        $orderby = 'year_ordained';
        $limit = null;
        $community_member = null;
        $ids = null;
        $show_excerpt = false;

        // define attributes and their defaults
        extract(shortcode_atts(array(
            'order' => 'ASC',
            'orderby' => $orderby,
            'limit' => -1,
            'ids' => false,
            'community_member' => false,
            'show_excerpt' => true,
        ), $atts));

        $order = (in_array($order, array('ASC','DESC')) ? $order : 'ASC');

        // define query parameters based on attributes
        $options = array(
            'post_type' => 'fpmtp_bios',
            'order' => $order,
            'orderby' => (in_array($orderby, array('ID', 'title', 'name')) ? $orderby : 'ID'),
            'posts_per_page' => $limit,
        );
        if ($ids) {
            $options['post__in'] = $ids;
        }
        if ($community_member) {
            $options['tax_query'] = array(
                array(
                    'taxonomy' => 'fpmtp_communitymember',
                    'field' => 'slug',
                    'terms' => explode(",", $community_member),
                ),
            );
        }
        $query = new WP_Query($options);
        $row_class = 'even';

        $aResult = array();
        $sortKey = (in_array($orderby, array('ID', 'title', 'name')) ? '' : $orderby );

        
        // run the loop based on the query
        if ($query->have_posts()) {
			while ( $query->have_posts() ) :
                $query->the_post();
                    $row = "";
                $row_class = ($row_class == 'even') ? 'odd' : 'even';
                $post = $query->get_queried_object();
                $term_list = wp_get_post_terms(get_the_ID(), 'fpmtp_communitymember', array("fields" => "all"));

                $get_the_title = get_the_title();
                $get_permalink = get_permalink();
                $post_name = basename(get_the_permalink());
                $iPostID = get_the_ID();
                 if(has_post_thumbnail()):
                     $thumb_id = get_post_thumbnail_id();
                     $thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail', true);
                    $thmb_nail = get_the_post_thumbnail();
                    $thumb_result = <<<THUMBRESULT
                <a class="x-img x-img-link x-img-circle left" href="#bio-picture" title="$get_the_title"  data-toggle="tooltip" data-placement="bottom" data-trigger="hover"   data-options="thumbnail: '$thumb_url'">$thmb_nail</a>
THUMBRESULT;
                endif;


                if($show_excerpt) :
                    $get_the_excerpt = get_the_excerpt();
                    $the_excerpt = <<<OUTPUTRESULT
    <div id="details-$post_name" class="details">
        $get_the_excerpt
    </div>
OUTPUTRESULT;
                endif;

                $aPostData = array();
                foreach( ( array ) get_post_custom_keys( $iPostID ) as $sKey ) {    // This way, array will be unserialized; easier to view.
                    $aPostData[$sKey] = get_post_meta($iPostID, $sKey, true);
                }

                $meta_ordained = date("Y");
                    $get_ordained = "";
                $meta_position = "";
                    $get_position = "";
                if(!empty($aPostData['bio_details'])) {
                    if($aPostData['bio_details']['year_ordained']) :
                        $meta_ordained = $aPostData['bio_details']['year_ordained'];
                        $label_ordained = __('Ordained', FPMTP__I18N_NAMESPACE) . ":";
                        $get_ordained = <<<OUTPUTRESULT
                            <h4 class="fpmtp-bios-ordained">$label_ordained $meta_ordained</h4>
OUTPUTRESULT;
                    endif;
                    if($aPostData['bio_details']['community_position']) :
                        $meta_position = $aPostData['bio_details']['community_position'];
                        $get_position = <<<OUTPUTRESULT
                            <h4 class="fpmtp-bios-position">$meta_position</h4>
OUTPUTRESULT;
                    endif;
                }

                    $row = <<<OUTPUTRESULT
			
                <div class="fpmtp-bios $row_class post-{$post->ID} {$post->post_name}">
                    $thumb_result
                    <div class="fpmtp-bios-info-wrap">
                        <h3 class="fpmtp-bios-name">$get_the_title</h3>
                        $get_position
                        $get_ordained
                        <a href="$get_permalink">Full biography</a>
                        $the_excerpt
                    </div>
                </div>

OUTPUTRESULT;
                    if(!empty($sortKey)) :
                        if($sortKey=='year_ordained') :
                            $aResult[$meta_ordained][] = $row . '<!-- o '.$meta_ordained.' -->';
                        elseif($sortKey=='community_position') :
                            $aResult[$meta_position][] = $row . '<!-- p '.$meta_position.' -->';
                        else :
                            $aResult[] = $row . '<!-- i '.$iPostID.' -->';
                        endif;
                    else :
                        $aResult[] = $row . '<!-- ii '.$iPostID.' -->';
                    endif;

				endwhile;
			}

        /* Restore original Post Data */
        wp_reset_postdata();

        if(!empty($aResult)) :
            $result = <<<OUTPUTRESULT
    <div class="fpmtp-bios-listing">
OUTPUTRESULT;
            //$result = ob_get_clean();
            if($sortKey=='year_ordained') :
                if($order=='ASC') sort($aResult);
                if($order=='DESC') rsort($aResult);
                foreach ($aResult as $s) {
                    $result .= implode("",$s);
                }
            else :
                $result .= implode("",$aResult);
            endif;
            $result .= <<<OUTPUTRESULT
                <!-- Close fpmtp-bios-listing -->
                <!-- $sortKey -->
            </div>
OUTPUTRESULT;
            unset($aResult);
            return $result;
        else :
                return '';
        endif;
    }

    public static function  bios_cache() {
        $args = array(
            'type'          => 'fpmtp_audio',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'hide_empty'    => 1,
            'taxonomy'      => 'fpmtp_speakers');

        $audio_categories = get_categories( $args );

        $args = array(
            'type'          => 'fpmtp_books',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'hide_empty'    => 1,
            'taxonomy'      => 'fpmtp_authors_taxonomy');

        $books_categories = get_categories( $args );

        $bios_query = array();

        /* Pulls the first post from each of the individual categories */

        foreach( $audio_categories as $category ) {

            $page = get_page_by_title( $category->name, 'OBJECT', 'fpmtp_bios' );

            if(!is_wp_error($page)) {
                $bio_thumbnail = null;
                $bio_thumbnail = wp_get_attachment_image_src($page->ID);

                /* All the data pulled is saved into an array which we'll save later */
                $speaker_link = get_term_link($category->slug, 'fpmtp_speakers');
                if(is_wp_error($speaker_link)){
                    $speaker_link = $speaker_link->get_error_message();
                    //echo $speaker_link;
                }
                $bios_query[$category->slug] = array(
                    'name' => get_the_title($page->ID),
                    'excerpt' => $page->post_excerpt,
                    'bio_link' => get_permalink($page->ID),
                    'bio_thumbnail' => $bio_thumbnail[0],
                    'speaker_link' => esc_attr($speaker_link),
                    'speaker_talk_count' => $category->count,
                    'author_link' => false,
                    'author_book_count' => false,
                );
            }
        }

        foreach( $books_categories as $category ) {
            $page = get_page_by_title( $category->name, 'OBJECT', 'fpmtp_bios' );

            if(!is_wp_error($page)) {
                $bio_thumbnail = null;
                $bio_thumbnail = wp_get_attachment_image_src($page->ID);

                /* All the data pulled is saved into an array which we'll save later */
                if (isset($bios_query[$category->slug]) && !empty($bios_query[$category->slug])) {
                    $bios_query[$category->slug]['author_link'] = esc_attr(get_term_link($category->slug, 'fpmtp_authors_taxonomy'));
                    $bios_query[$category->slug]['author_book_count'] = $category->count;
                } else {
                    $author_link = get_term_link($category->slug, 'fpmtp_authors_taxonomy');
                    if(is_wp_error($author_link)){
                        $author_link = $author_link->get_error_message();
                        //echo $author_link;
                    }

                    $bios_query[$category->slug] = array(
                        'name' => get_the_title($page->ID),
                        'excerpt' => $page->post_excerpt,
                        'bio_link' => get_permalink($page->ID),
                        'bio_thumbnail' => $bio_thumbnail[0],
                        'speaker_link' => false,
                        'speaker_talk_count' => false,
                        'author_link' => esc_attr($author_link),
                        'author_book_count' => $category->count,
                    );
                }
            }
        }

        // Reset Post Data - Do we need to do this without having called a WP_Query?
        //wp_reset_postdata();

        set_transient( 'fpmtp_bios_query', $bios_query );

        return $bios_query;
    }

    public static function add_query_vars_filter( $vars ){
        $vars[] = "playlist"; // Add param to parse playlist=series as mp3 (m3u) playlist
        return $vars;
    }

    public static function get_slug($type = FALSE)
    {
        if($type)
            return self::$slug . '_' . $type;
        else
            return plugin_basename( __FILE__ );
    }

    public static function the_slug($type)
    {
        echo self::get_slug($type);
    }
	
	public static function settings(){
		return get_option( 'FullPeace_Media_To_Post' );
	}
	
	public static function debugSettings(){
		$x = self::settings();
		var_export($x, true);
	}

    public static function setting($setting, $option_value = FALSE)
    {
        if($option_value)
        {
            //FullPeace_Media_To_Post_Admin::add_notice('Setting setting ' . $setting . ' to ' . $option_value);
            $added = add_option( FullPeace_Media_To_Post::get_slug($setting), $option_value);
            register_setting( 'default', FullPeace_Media_To_Post::get_slug($setting) );
        }else{
            //FullPeace_Media_To_Post_Admin::add_notice('Got setting ' . $setting . ' :: ' . get_option(FullPeace_Media_To_Post::get_slug($setting)));
            return get_option(FullPeace_Media_To_Post::get_slug($setting));
        }
    }

    /**
     * Parses each attachment uploaded through the media library
     * and creates a custom post with related taxonomy terms.
     *
     * @param $attachment_ID
     */
    public static function post_from_attachment($attachment_ID)
    {
        $type = get_post_mime_type($attachment_ID);
        if(strpos($type, 'audio') === 0)
        {
            self::create_audio_post_from_upload($attachment_ID);
        }
    }


    /**
     * The attachment parser. Reads the uploaded file (for each file
     * uploaded via the Media Library) and creates a corresponding
     * Custom Post Type with related Custom Taxonomy terms.
     *
     * Currently supporting:
     *
     *  - MP3 format to Audio type
     *
     * @param $attachment_ID The attachment id of an attachment with mime type 'audio'
     */
    public static function create_audio_post_from_upload($attachment_ID)
    {
        global $current_user;
        get_currentuserinfo();

        $aAudioSettings = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_audio' );

        $attachment_post = get_post( $attachment_ID );
        $filepath = get_attached_file( $attachment_ID );

        $metadata = wp_read_audio_metadata( $filepath );

        $meta_comment = (isset($metadata['comment'])) ? $metadata['comment'] : "";
        $meta_length = (isset($metadata['length_formatted'])) ? "\n\n".__('Length of recording', FPMTP__I18N_NAMESPACE ) .": ".$metadata['length_formatted'] : "";
        $meta_year = (isset($metadata['year'])) ? "\n".__('Year', FPMTP__I18N_NAMESPACE ) .": ".$metadata['year'] : "";

        $new_post_content = '[audio src="'.$attachment_post->guid.'"]'.
            "\n\n".$attachment_post->post_content .
            $meta_length .
            $meta_year .
        "\n\n".'<a href="'.$attachment_post->guid.'" download0"'.basename($attachment_post->guid).'">'.__('Download' , FPMTP__I18N_NAMESPACE).'</a>';

        // Create new custom post object only for images
        $audio_custom_post = array(
            'post_title'    => $attachment_post->post_title,
            'post_content'  => $new_post_content ,
            'post_excerpt'  => $meta_comment ,
            'post_type'     => FullPeace_Media_To_Post::$slug . '_audio',
            'post_author'   => $current_user->ID
        );

        if(isset($aAudioSettings['fpmtp_audio_post_status']))
        {
            $audio_custom_post['post_status'] = $aAudioSettings['fpmtp_audio_post_status'];
        }

        // Insert the custom post into the database
        $audio_post_id = wp_insert_post( $audio_custom_post );
        wp_update_post( array(
                'ID' => $attachment_ID ,
                'post_parent' => $audio_post_id
            )
        );

        if(isset($metadata['artist'] ))
            wp_set_object_terms( $audio_post_id, array( $metadata['artist'] ), FullPeace_Media_To_Post::$slug . '_speakers', true );
        if(isset($metadata['album'] ))
            wp_set_object_terms( $audio_post_id, array( $metadata['album'] ), FullPeace_Media_To_Post::$slug . '_series', true );


        // MP3 featured image is not set at this point, defer to next page load
        $option_name = 'fpmtp_deferred_featured_images' ;
        if ( get_option( $option_name ) !== false ) {
            $new_value = (array)get_option( $option_name );
            $new_value[$attachment_ID] = $audio_post_id;
            // The option already exists, so we just update it.
            update_option( $option_name, $new_value );
        } else {
            $new_value = array($attachment_ID => $audio_post_id);
            // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            add_option( $option_name, $new_value, $deprecated, $autoload );
        }
		
		// Now that the attachment is parsed, check the setting to see if the file should be moved to FTP
		$plugin_settings = self::settings();
		if($plugin_settings['fpmtp_enable_ftp']) {
			require_once ( FPMTP__PLUGIN_DIR . 'library/Ftp.php' );
			
			try {
				$ftp = new Ftp;

				// Opens an FTP connection to the specified host
				$ftp->connect($plugin_settings['fpmtp_ftp_domain']);

				// Login with username and password
				$ftp->login($plugin_settings['fpmtp_ftp_username'], $plugin_settings['fpmtp_ftp_password']);

				$ftpdir = $plugin_settings['fpmtp_ftp_dir'];
				// Get the local filepath and upload to FTP

                /// THIS IS NOT DEVELOPED DUE TO CHANGED REQUIREMENTS

			} catch (FtpException $e) {
				echo 'Error: ', $e->getMessage();
			}
		}
    }

    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return    string    FPMTP__VERSION    The current version of the plugin.
     */
    public static function get_version()
    {
        return FPMTP__VERSION;
    }

    /**
     * Plugin installation and activation.
     *
     * Registers custom post types and taxonomies used by the plugin.
     * @since    0.1.0
     */
    public static function plugin_activation()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';
        FullPeace_Media_To_Post_Setup::on_activation();
        //Ensure the $wp_rewrite global is loaded
        global $wp_rewrite;
        //Call flush_rules() as a method of the $wp_rewrite object
        $wp_rewrite->flush_rules( false );
    }

    /**
     * Plugin deactivation.
     *
     * Does nothing this version.
     * @since    0.1.0
     */
    public static function plugin_deactivation()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';

        FullPeace_Media_To_Post_Setup::on_deactivation();
        delete_option(self::$slug . '_deferred_admin_notices');
    }

    /**
     * Plugin uninstall.
     *
     * Does nothing this version.
     * @since    0.1.0
     */
    public static function plugin_uninstall()
    {
        require_once FPMTP__PLUGIN_DIR . 'includes/class.setup.php';

        FullPeace_Media_To_Post_Setup::on_uninstall();
    }


    /**
     * Returns the template file
     *
     * @since       0.1.2
     */

    public static function  template_chooser($template) {

        // Post ID
        $post_id = get_the_ID();

        // For all other CPT
        if( get_post_type( $post_id ) != 'fpmtp_bios' ) {
            return $template;
        }

        // Else use custom template
        if ( is_single() ) {
            return self::get_template_hierarchy('single');
        }

    }

    /**
     * Get the custom template if is set
     *
     * @since       0.1.2
     */

    public static function    get_template_hierarchy( $template ) {

        // Get the template slug
        $template_slug = rtrim($template, '.php');
        $template      = $template_slug . '.php';

        // Check if a custom template exists in the theme folder, if not, load the plugin template file
        if ( $theme_file = locate_template(array('plugin_template/'.$template)) ) {
            $file = $theme_file;
        }
        else {
            $file = FPMTP__PLUGIN_DIR . 'templates/' . $template;
        }

        return apply_filters( 'repl_template_'.$template, $file);
    }
}



/**
 * Deletes the audio_series_query transient if a post is updated.
 *
 * Moved this outside the class, since WP 4.0 was throwing an error.
 */

function  FullPeace_Media_To_Post_plugin_post_save( $post_id ) {

    // If this is an auto save routine don't do anyting
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    $post = get_post($post_id);

    if ( $post->post_type == 'fpmtp_audio' ) {
        delete_transient( 'fpmtp_audio_series_query' );
        $term_list = wp_get_post_terms($post->ID, 'fpmtp_series', array("fields" => "all"));
        foreach ($term_list as $t) {
            delete_transient( 'fpmtp_audio_series_query_' . $t->slug );
        }

    }

    if ( $post->post_type == 'fpmtp_bios' ) {
        delete_transient( 'fpmtp_bios_query' );
    }
}