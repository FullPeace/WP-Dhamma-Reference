<?php
/*
 * This plugin hooks into the upload feature in the Media Library in Wordpress (admin),
 * parses the uploaded files and creates Custom Post Types and relevant related Custom Taxonomy terms.
 *
 * For MP3 files, the following information is parsed:
 * - ID3Tag 'artist' > Speaker (custom taxonomy) term added.
 * - ID3Tag 'album' > Series (custom taxonomy) term added.
 * - WP attachment post content is copied to the post content of the Talk post that is created.
 *
 * @package FPMTP
 *
 * @wordpress-plugin
 * Plugin Name:       FullPeace Media To Post
 * Plugin URI:        http://github.com/tommcfarlin/post-meta-manager
 * Description:       FullPeace Media To Post creates Custom Post Types for media files on upload via the Media Library.
 * Version:           0.1.0
 * Author:            FullPeace.org
 * Author URI:        http://fullpeace.org
 * Text Domain:       fullpeace-media-to-posts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( !function_exists( 'add_action' ) ) { // Don't expose the plugin
    exit;
}

/**
 * Definitions
 */
define( 'FPMTP__VERSION', '0.1.0' );
define( 'FPMTP__I18N_NAMESPACE', 'fullpeace_org' );
define( 'FPMTP__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FPMTP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Include the core class
 */
require_once FPMTP__PLUGIN_DIR . 'includes/class.main.php';


register_activation_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_uninstall' ) );

add_action( 'init', array( 'FullPeace_Media_To_Post', 'init' ) );

// Create a custom post type - this class deals with front-end components so checking with is_admin() is not necessary.
require_once( FPMTP__PLUGIN_DIR . 'admin/cpt/ebooks-cpt.php' );
new FullPeace_eBooks_PostType( 'fpmtp_ebooks' ); 	// post type slug

if ( is_admin() ) {
    require_once( FPMTP__PLUGIN_DIR . 'admin/class.optionspage.php' );
    // Create meta boxes with form fields that appear in post definition pages (where you create a post) of the given post type.
    include_once( FPMTP__PLUGIN_DIR . 'admin/metabox/ebookfields.php' );
    new FullPeace_eBooks_MetaBox(
        'fpmtp_ebooks_meta_box',	// meta box ID
        __( 'Demo Meta Box with Built-in Field Types', FPMTP__I18N_NAMESPACE ),	// title
        array( 'fpmtp_ebooks' ),	// post type slugs: post, page, etc.
        'normal',	// context (what kind of metabox this is)
        'default'	// priority
    );
    require_once( FPMTP__PLUGIN_DIR . 'admin/class.admin.php' );
    add_action( 'init', array( 'FullPeace_Media_To_Post_Admin', 'init' ) );
}