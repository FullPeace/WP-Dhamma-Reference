<?php
/**
 * Example Widget Class
 */
class FullPeace_Taxonomies_Widget extends WP_Widget {
 
 
    /** constructor  */
    function FullPeace_Taxonomies_Widget() {
        parent::WP_Widget(false, $name = 'Taxonomy Filter Widget');
    }
 
    /** @see WP_Widget::widget -- do not rename this */
	/**
	 * @todo 
	 **/
    function widget($args, $instance) {

        $the_taxonomies = array('fpmtp_authors_taxonomy' , 'fpmtp_speakers', 'fpmtp_year_taxonomy' , 'fpmtp_series', 'fpmtp_languages', 'fpmtp_audio_languages', 'fpmtp_audio_year' );
        if( !is_post_type_archive( array('fpmtp_books','fpmtp_audio', 'fpmtp_bios') )
            && !is_singular( array('fpmtp_books','fpmtp_audio', 'fpmtp_bios') )
            && !is_tax( $the_taxonomies ) && !is_search())
            return;

        extract( $args );
        global $post;
        $title 		= apply_filters('widget_title', $instance['title']);

        if(is_search()){
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            echo do_shortcode('[searchandfilter fields="search,fpmtp_authors_taxonomy,fpmtp_year_taxonomy,fpmtp_languages,fpmtp_speakers,fpmtp_series,fpmtp_audio_year,fpmtp_audio_languages" all_items_labels="Search,All Authors,Any Year (Books),Any Language (Books), All Speakers (Audio),All Series (Audio),Any Year (Audio),Any Language (Audio)" submit_label="Search"]');
            echo $after_widget;
            return;
        }


        if(is_singular( 'fpmtp_books' ) || is_post_type_archive( 'fpmtp_books' ) || is_tax(array('fpmtp_authors_taxonomy' , 'fpmtp_year_taxonomy' , 'fpmtp_languages' )) ){
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            echo do_shortcode('[searchandfilter fields="fpmtp_authors_taxonomy,fpmtp_year_taxonomy,fpmtp_languages" all_items_labels="All Authors,Any Year,Any Language" empty_search_url="/dhamma-books" show_count="1,1,1" submit_label="Search"]');
            echo $after_widget;
            return;
        }

        if(is_singular( 'fpmtp_audio' ) || is_post_type_archive( 'fpmtp_audio' ) || is_tax(array('fpmtp_speakers' , 'fpmtp_series', 'fpmtp_audio_year' , 'fpmtp_audio_languages' )) ){
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            echo do_shortcode('[searchandfilter fields="fpmtp_speakers,fpmtp_series,fpmtp_audio_year,fpmtp_audio_languages" all_items_labels="All Speakers,All Series,Any Year,Any Language" empty_search_url="/audio" show_count="1,1,1,1" submit_label="Search"]');
            echo $after_widget;
            return;
        }

        if( is_singular( 'fpmtp_bios' ) || is_post_type_archive( 'fpmtp_bios' ) ){
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            echo do_shortcode('[searchandfilter fields="fpmtp_speakers,fpmtp_authors_taxonomy" all_items_labels="Talks by Speaker,Books by Author" empty_search_url="/biographies" show_count="1,1" submit_label="Search"]');
            echo $after_widget;
            return;
        }

        $tax_translation = array(
            'fpmtp_authors_taxonomy' => __('Any Book Author', FPMTP__I18N_NAMESPACE),
            'fpmtp_speakers' => __('Any Dhamma Talk Speaker', FPMTP__I18N_NAMESPACE),
            'fpmtp_year_taxonomy'  => __('Any Publishing Year (Books)', FPMTP__I18N_NAMESPACE),
            'fpmtp_series' => __('Any Dhamma Talk Series', FPMTP__I18N_NAMESPACE),
            'fpmtp_languages'  => __('Any Language (Books)', FPMTP__I18N_NAMESPACE),
            'fpmtp_audio_languages'  => __('Any Language (Audio)', FPMTP__I18N_NAMESPACE),
            'fpmtp_audio_year'  => __('Any Year (Audio)', FPMTP__I18N_NAMESPACE),
            'category'  => __('All Categories', FPMTP__I18N_NAMESPACE),
        );

        if( is_post_type_archive( array('fpmtp_books') ) || is_tax( array('fpmtp_authors_taxonomy' , 'fpmtp_year_taxonomy' , 'fpmtp_languages' ) ) ){
            $tax_translation = array(
                'fpmtp_authors_taxonomy' => __('Any Author', FPMTP__I18N_NAMESPACE),
                'fpmtp_year_taxonomy'  => __('Any Publishing Year', FPMTP__I18N_NAMESPACE),
                'fpmtp_languages'  => __('Any Language', FPMTP__I18N_NAMESPACE),
                'category'  => __('Any Category', FPMTP__I18N_NAMESPACE),
            );
        }

        if( is_post_type_archive( array('fpmtp_audio') )  || is_tax( array('fpmtp_speakers', 'fpmtp_year_taxonomy' , 'fpmtp_series', 'fpmtp_audio_year' , 'fpmtp_audio_languages' ) ) ){
            $tax_translation = array(
                'fpmtp_speakers' => __('Any Speaker', FPMTP__I18N_NAMESPACE),
                'fpmtp_series' => __('All Series', FPMTP__I18N_NAMESPACE),
                'fpmtp_audio_languages'  => __('Any Language', FPMTP__I18N_NAMESPACE),
                'fpmtp_audio_year'  => __('Any Year', FPMTP__I18N_NAMESPACE),
            );
        }

        if($layout && file_exists(get_stylesheet_directory() . '/fpmtp-' . $layout . '.php' ))
        {
            get_template_part('fpmtp', $layout);
        }else {
            ?>
            <!-- <?php echo single_term_title("", false); ?> -->
            <?php echo $before_widget; ?>
            <?php if ($title)
                echo $before_title . $title . $after_title; ?>
            <?php
            $out = '';
            $out .= '<ul>';
            foreach ($tax_translation as $cat_slug => $cat_title) {
                $out .= '<li class="widget-' . $cat_slug . '">';
                $out .= '<h4 class="mtn">' . $cat_title . '</h4>';
                $terms = get_terms($cat_slug);
                if (!empty($terms) && !is_wp_error($terms)) {
                    $out .= '<ul>';
                    foreach ($terms as $term) {
                        $post_class_active = ($term->slug == $post->post_name) ? ' class="active" ' : '';
                        $out .= '<li' . $post_class_active . '><a href="' . get_term_link($term) . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a></li>';
                    }
                    $out .= '</ul>';
                    $out .= '</li>';
                }
            }
            $out .= '</ul>';

            echo $out;
            ?>
            <?php echo $after_widget; ?>
        <?
        }
        wp_reset_postdata();
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
        $title 		= esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <em><?php echo __('This displays the taxonomies and terms for filtering books and talks, on archive pages.', FPMTP__I18N_NAMESPACE ); ?></em>
        </p>
        <?php 
    }
 
 
} // end class FullPeace_Taxonomies_Widget
add_action('widgets_init', create_function('', 'return register_widget("FullPeace_Taxonomies_Widget");'));
