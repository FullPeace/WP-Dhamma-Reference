<?php
/**
 * Example Widget Class
 */
class FullPeace_Bios_Widget extends WP_Widget {
 
 
    /** constructor  */
    function FullPeace_Bios_Widget() {
        parent::WP_Widget(false, $name = 'Bios Widget');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
	/**
	 * @todo 
	 **/
    function widget($args, $instance) {
        if(!is_tax( 'fpmtp_authors_taxonomy' ) && !is_tax( 'fpmtp_speakers' ))
			return;
		extract( $args );
        $title 		= apply_filters('widget_title', $instance['title']);
		// Removed in favor of transient array
		//$page = get_page_by_title( single_term_title("", false), 'OBJECT', 'fpmtp_bios' );
        $term_title = get_queried_object()->slug;//single_term_title("", false);
        if(!is_wp_error($term_title)) {
            echo "<!-- $term_title -->";
            $bio = FullPeace_Media_To_Post_Public::getBio($term_title);
            //echo "<!-- ".var_export($bio,true)." -->";
        }
        else
        {
            $error_string = $term_title->get_error_message();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }


        if(!empty($bio) && !is_wp_error($bio)) {
            ?>
            <!-- <?php echo single_term_title("", false); ?> -->
            <?php echo $before_widget; ?>
            <?php if ($title)
                echo $before_title . $title . $after_title; ?>
            <?php
            echo $bio['bio_thumbnail'];
            ?>
            <h3><?php echo $bio['name'] ?></h3>
            <p><?php echo $bio['excerpt']; ?></p>
            <ul class="bio-links">
                <li>
                    <a href="<?php echo $bio['bio_link']; ?>" title="<?php echo __('More about', FPMTP__I18N_NAMESPACE); ?> <?php echo $bio['name']; ?>"><?php echo __('About', FPMTP__I18N_NAMESPACE); ?> <?php echo $bio['name']; ?></a>
                </li>
                <?php if ($bio['author_link']) : ?>
                    <li>
                        <a href="<?php $bio['author_link']; ?>" title="<?php echo __('Books by', FPMTP__I18N_NAMESPACE); ?> <?php echo $bio['name']; ?>"><?php echo __('Books', FPMTP__I18N_NAMESPACE); ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($bio['speaker_link']) : ?>
                    <li>
                        <a href="<?php $bio['speaker_link']; ?>" title="<?php echo __('Audio with', FPMTP__I18N_NAMESPACE); ?> <?php echo $bio['name']; ?>"><?php echo __('Audio', FPMTP__I18N_NAMESPACE); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php /*<p><?php echo var_export($page,true); ?></p>*/ ?>
            <?php echo $after_widget; ?>
        <?php
        }elseif(is_wp_error($bio))
        {
            $error_string = $bio->get_error_message();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
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
          <em><?php echo __('This displays the Bio (biography) Name, Featured Image and Excerpt for a Bio matching the name of an Authors or Speakers page.', FPMTP__I18N_NAMESPACE ); ?></em>
        </p>
        <?php 
    }
 
 
} // end class FullPeace_Bios_Widget
add_action('widgets_init', create_function('', 'return register_widget("FullPeace_Bios_Widget");'));
