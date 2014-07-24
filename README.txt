=== WP Media to Posts ===
Contributors: iohannis
Tags: media, custom-post-types, upload
Donate link: http://fullpeace.org/donations/
Requires at least: 3.9.0
Tested up to: 3.9.1
License: GPL2

This plugin creates Custom Post Types for media files - particularly MP3 files to Talk posts.

== Description ==
This plugin hooks into the upload feature in the Media Library in Wordpress (admin), parses the uploaded files and creates Custom Post Types and relevant related Custom Taxonomy terms.

For MP3 files, the following information is parsed:
- ID3Tag 'artist' > Speaker (custom taxonomy) term added.
- ID3Tag 'album' > Series (custom taxonomy) term added.
- WP attachment post content is copied to the post content of the Talk post that is created.

== Installation ==
- Install and activate via the admin interface.

== Frequently Asked Questions ==
Please use the forums for questions, this page will be filled out with any questions that are relevant to many.

== Changelog ==
0.1.0 Initial release
