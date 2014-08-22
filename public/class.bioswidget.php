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
		$page = get_page_by_title( single_term_title("", false), 'OBJECT', 'fpmtp_bios' );
    
		
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        <?php 
							echo get_the_post_thumbnail($page->ID);
						?>
						<h3><?php echo $page->post_title ?></h3>
						<p><?php echo $page->post_excerpt; ?></p>
						<p><a href="<?php echo get_permalink( $page->ID ); ?>"><?php echo __('More about', FPMTP__I18N_NAMESPACE);?> <?php echo $page->post_title; ?></a></p>
						<?php/*<p><?php echo var_export($page,true); ?></p>*/?>
              <?php echo $after_widget; ?>
        <?php
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
