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
 */
class FullPeace_Media_To_Post {


    /**
     * A reference to the loader class that coordinates the hooks and callbacks
     * throughout the plugin.
     *
     * @access protected
     * @var    FullPeace_Media_To_Post_Loader   $loader    Manages hooks between the WordPress hooks and the callback functions.
     */
    protected $loader;

    /**
     * Represents the slug of hte plugin that can be used throughout the plugin
     * for internationalization and other purposes.
     *
     * @access protected
     * @var    string   $plugin_slug    The single, hyphenated string used to identify this plugin.
     */
    protected $plugin_slug;

    /**
     * Maintains the current version of the plugin so that we can use it throughout
     * the plugin.
     *
     * @access protected
     * @var    string   $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Initializes this class and stores the current version of this plugin.
     *
     * @param    string    $version    The current version of this plugin.
     */
    public function __construct( $version ) {

        $this->plugin_slug = 'fullpeace-media-to-post';
        $this->version = '0.1.0';

        $this->define_installation_hooks();
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Executes the plugin by calling the run method of the loader class which will
     * register all of the hooks and callback functions used throughout the plugin
     * with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return    string    $this->version    The current version of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Imports the FullPeace Media To Post administration classes, and the FullPeace Media To Post Loader.
     *
     * The FullPeace Media To Post administration class defines all unique functionality for
     * introducing custom functionality into the WordPress dashboard.
     *
     * The FullPeace Media To Post Loader is the class that will coordinate the hooks and callbacks
     * from WordPress and the plugin. This function instantiates and sets the reference to the
     * $loader class property.
     *
     * @access    private
     */
    private function load_dependencies()
    {
        require_once plugin_dir_path( __FILE__  ) . 'admin/FullPeace_Media_To_Post_Admin.php';
        require_once plugin_dir_path( __FILE__  ) . 'public/FullPeace_Media_To_Post_Public.php';

        require_once plugin_dir_path( __FILE__  ) . 'FullPeace_Media_To_Post_Loader.php';
        $this->loader = new FullPeace_Media_To_Post_Loader();
    }

    /**
     * Defines the hooks and callback functions that are used for setting up the plugin stylesheets
     * and the plugin's meta box.
     *
     * This function relies on the FullPeace Media To Posts Admin class and the FullPeace Media To Posts
     * Loader class property.
     *
     * @access    private
     */
    private function define_admin_hooks()
    {
    }

    /**
     * Defines the hooks and callback functions for installing and uninstalling the plugin.
     *
     * @access    private
     */
    private function define_installation_hooks()
    {
        require_once plugin_dir_path( __FILE__  ) . 'FullPeace_Media_To_Post_Setup.php';

        register_activation_hook(   __FILE__, array( 'FullPeace_Media_To_Post_Setup', 'on_activation' ) );
        register_deactivation_hook( __FILE__, array( 'FullPeace_Media_To_Post_Setup', 'on_deactivation' ) );
        register_uninstall_hook(    __FILE__, array( 'FullPeace_Media_To_Post_Setup', 'on_uninstall' ) );
    }

    /**
     * Defines the hooks and callback functions that are used for rendering information on the front
     * end of the site.
     *
     * This function relies on the FullPeace Media To Posts Public class and the FullPeace Media To Posts
     * Loader class property.
     *
     * @access    private
     */
    private function define_public_hooks()
    {
    }

}
