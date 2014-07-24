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

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path(  __FILE__  ) . 'includes/FullPeace_Media_To_Post.php';

/**
 * Instantiates the FullPeace Media To Posts class and
 * starts the plugin.
 */
function run_fpmtp_manager() {

    $fpmtp = new FullPeace_Media_To_Post('0.1.0');
    $fpmtp->run();

}

// Call the above function to begin execution of the plugin.
run_fpmtp_manager();