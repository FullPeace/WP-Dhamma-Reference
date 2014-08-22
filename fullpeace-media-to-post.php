<?php
/*
 * This plugin hooks into the upload feature in the Media Library in Wordpress (admin),
 * parses the uploaded files and creates Custom Post Types and relevant related Custom Taxonomy terms.
 *
 * For MP3 files, the following information is parsed:
 * - ID3Tag 'artist' > Speaker (custom taxonomy) term added.
 * - ID3Tag 'album' > Series (custom taxonomy) term added.
 * - WP attachment post content is copied to the post content of the Audio post that is created.
 * - ID3Tags 'comment' and 'length_formatted' are appended to the post content, if available in the file.
 *
 * For (e)Books, the plugin creates a new Books post type, allowing upload of:
 * - PDF files
 * - EPUB files
 * - MOBI files
 *
 * @package FPMTP
 *
 * @wordpress-plugin
 * Plugin Name:       FullPeace Media To Post
 * Plugin URI:        http://github.com/FullPeace/fullpeace-media-to-post
 * Description:       FullPeace Media To Post creates Custom Post Types for media files on upload via the Media Library.
 * Version:           0.1.9
 * Author:            FullPeace.org
 * Author URI:        http://fullpeace.org
 * Text Domain:       fullpeace-media-to-posts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Definitions
 */
define( 'FPMTP__VERSION', '0.1.9' );
define( 'FPMTP__DEVMODE', false );
define( 'FPMTP__I18N_NAMESPACE', 'fullpeace-media-to-posts' );
define( 'FPMTP__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FPMTP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/* Include the library */
if ( ! class_exists( 'AdminPageFramework' ) ) {
    include_once( FPMTP__PLUGIN_DIR . 'library/admin-page-framework.min.php'	    );
}
/**
 * Include the core class
 */
require_once FPMTP__PLUGIN_DIR . 'includes/class.main.php';


register_activation_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'FullPeace_Media_To_Post', 'plugin_uninstall' ) );

add_action( 'init', array( 'FullPeace_Media_To_Post', 'init' ) );


// Create a custom post type - this class deals with front-end components so checking with is_admin() is not necessary.
$aEnablePostTypes = AdminPageFramework::getOption( 'FullPeace_Options_Page', 'fpmtp_settings_general' );
if($aEnablePostTypes['fpmtp_enable_books']) {
    require_once(FPMTP__PLUGIN_DIR . 'admin/cpt/books-cpt.php');
    new FullPeace_Books_PostType('fpmtp_books');    // post type slug
}
if($aEnablePostTypes['fpmtp_enable_audio']) {
    require_once(FPMTP__PLUGIN_DIR . 'admin/cpt/audio-cpt.php');
    new FullPeace_Audio_PostType('fpmtp_audio');    // post type slug
}
if($aEnablePostTypes['fpmtp_enable_bios']) {
    require_once(FPMTP__PLUGIN_DIR . 'admin/cpt/bio-cpt.php');
    new FullPeace_Bio_PostType('fpmtp_bios');    // post type slug
}

require_once( FPMTP__PLUGIN_DIR . 'public/class.bioswidget.php' );

if ( is_admin() ) {
    require_once( FPMTP__PLUGIN_DIR . 'admin/class.optionspage.php' );
    // Create meta boxes with form fields that appear in post definition pages (where you create a post) of the given post type.
    include_once( FPMTP__PLUGIN_DIR . 'admin/metabox/bookfields.php' );
    new FullPeace_Books_MetaBox(
        'fpmtp_books_meta_box',	// meta box ID
        __( 'Upload Book files (PDF, EPUB, MOBI)', FPMTP__I18N_NAMESPACE ),	// title
        array( 'fpmtp_books' ),	// post type slugs: post, page, etc.
        'normal',	// context (what kind of metabox this is)
        'default'	// priority
    );
    require_once( FPMTP__PLUGIN_DIR . 'admin/class.admin.php' );
    add_action( 'init', array( 'FullPeace_Media_To_Post_Admin', 'init' ) );

    // Allow updates from Github
    require_once( FPMTP__PLUGIN_DIR . 'library/plugin-updater/BFIGitHubPluginUploader.php' );
    $this_plugin_file =  __FILE__ ;
    $this_plugin_gituser =  'FullPeace' ;
    $this_plugin_gitrepo =  "fullpeace-media-to-post" ;
    new BFIGitHubPluginUpdater( $this_plugin_file, $this_plugin_gituser, $this_plugin_gitrepo );
}

include_once(FPMTP__PLUGIN_DIR . 'library/wpse-playlist/wpse-playlist.php');