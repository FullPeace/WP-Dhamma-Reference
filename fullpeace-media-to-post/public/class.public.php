<?php
/**
 * The FullPeace Media To Post Public defines all functionality for the frontend
 *
 * @package FPMTP
 *
 * The FullPeace Media To Post Public defines all functionality for the public-facing
 * sides of the plugin.
 *
 * This class defines the shortcodes used to display the custom posts
 * generated by the plugin, as well as templates to use if none are existing
 * for the custom post types and taxonomies.
 *
 * @since    0.1.0
 */
class FullPeace_Media_To_Post_Public {

    /**
     * @var bool
     */
    private static $initiated = false;
    /**
     * The templates array defines the templates available to use in the frontend.
     * @var array
     */
    private static $templates = array();

    /**
     * Initializes the class
     */
    public static function init() {
        if ( ! self::$initiated ) {
            self::init_hooks();
        }
    }

    /**
     * Initialize hooks
     */
    public static function init_hooks()
    {
        self::$initiated = true;

        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter(
            'page_attributes_dropdown_pages_args',
            array( 'FullPeace_Media_To_Post_Public', 'register_project_templates' )
        );

        // Add a filter to the save post to inject our template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( 'FullPeace_Media_To_Post_Public', 'register_project_templates' )
        );

        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( 'FullPeace_Media_To_Post_Public', 'view_project_template')
        );

        // Add our templates
        self::setTemplates( array(
            'single-'.FullPeace_Media_To_Post::$slug.'_talks_series.php' => 'Talks Series Template', // @todo Create this
            //'talks-template.php'        => 'Talks Template',        // @todo Create this
            //'series-template.php'       => 'Series Template',       // @todo Create this
        ));
    }


    /**
     * @return array
     */
    public static function getTemplates()
    {
        return self::$templates;
    }

    /**
     * @param array $templates
     */
    public static function setTemplates($templates)
    {
        self::$templates = $templates;
    }

    public static function register_project_templates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list.
        // If it doesn't exist or is empty, prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, self::getTemplates() );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     * @var $template
     */
    public function view_project_template( $template )
    {
        global $post;

        if (!isset($this->templates[get_post_meta(
                $post->ID, '_wp_page_template', true
            )] ) )
        {
            return $template;
        }

        $file = plugin_dir_path(__FILE__). get_post_meta(
                $post->ID, '_wp_page_template', true
            );

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            return $file;
        }
        else { echo $file; }

        return $template;
    }

    /**
     * Shortcode handler for [fpmtp_talks] shortcode
     * @param $atts
     */
    public static function custom_talks_shortcode( $atts ) {

        // Attributes
        extract( shortcode_atts(
                array(
                    'speaker' => '',
                    'series' => '',
                    'category' => '',
                    'year' => '',
                ), $atts )
        );

        // Code
        // return WP_Query( array( 'post_type' => FullPeace_Media_To_Post::$slug . '_talks' ) );
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes()
    {
        add_shortcode( FullPeace_Media_To_Post::$slug . '_talks', array('FullPeace_Media_To_Post_Public', 'custom_talks_shortcode') );
    }
}