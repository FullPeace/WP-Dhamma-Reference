=== FullPeace Media To Post ===
Contributors: iohannis
Tags: media, custom-post-types, upload
Donate link: http://fullpeace.org/donations/
Requires at least: 3.8.0
Tested up to: 4.0
License: GPLv2

This plugin creates Custom Post Types for media files - particularly MP3 files to Talk posts.

== Description ==
This plugin hooks into the upload feature in the Media Library in Wordpress (admin), parses the uploaded files and creates Custom Post Types and relevant related Custom Taxonomy terms.

For MP3 files, the following information is parsed:
- ID3Tag 'artist' > Speaker (custom taxonomy) term added.
- ID3Tag 'album' > Series (custom taxonomy) term added.
- WP attachment post content is copied to the post content of the Talk post that is created.
- ID3Tags 'comment' and 'length_formatted' are appended to the post content, if available in the file.

For (e)Books, the plugin creates a new Books post type, allowing upload of:
- PDF files
- EPUB files
- MOBI files

Additionally, a Bio (biography or profile) post type can be added from the settings.

== Installation ==
- Install and activate via the admin interface.

== Frequently Asked Questions ==
Please use the forums for questions, this page will be filled out with any questions that are relevant to many.

== Changelog ==
0.1.14 Added Bios details, several shortcodes and a series playlist. Bug fixes for upload parsing.

0.1.9 Bug fixes

0.1.8 Added Bios widget

0.1.7 Removed the Template Chooser method

0.1.6 Bug fixes

0.1.5 Bug fixes

0.1.4 Added post_status option for imported Audio

0.1.3 Bug fixes

0.1.2 Further cleanup of code, better descriptions
- Added a template for Bios CPT to be used is the template is missing one. NOTE: Only enabled for the X theme for now.

0.1.1 This is the nearly ready version, changes contain:
      - Bug fixes
      - Moved files
      - Deleted a lot of unused code

0.1.0 Initial release
