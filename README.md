# FullPeace Media To Posts

* Contributors: [iohannis](http://callehunefalk.com/) for [FullPeace.org](http://fullpeace.org/) (see [GitHub](https://github.com/FullPeace))
* Tags: custom-post-types, media-upload
* Requires at least: 3.8.0
* Tested up to: 3.9.2
* Stable tag: 0.1.10
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

FullPeace Media To Posts displays the post meta data associated with a given post.

## Description

FullPeace Media To Posts displays the post meta data associated with a given post on the single post edit screen in the WordPress dashboard.

The plugin does not write any information to the database - it simply reads the information already stored and then provides the information in a user-friendly format.

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

## Installation

### Using The WordPress Dashboard

1. Download the `fullpeace-media-to-post.zip` file
2. Navigate to the 'Add New' Plugin Dashboard
3. Select `fullpeace-media-to-post.zip` from your computer
4. Upload
5. Activate the plugin on the WordPress Plugin Dashboard

### Using FTP

1. Extract `FullPeace-Media-To-Post.zip` to your computer
2. Upload the `FullPeace-Media-To-Post` directory to your `wp-content/plugins` directory
3. Activate the plugin on the WordPress Plugins Dashboard

## License

FullPeace Media To Posts is licensed under the [GPL v2](LICENSE.txt) or later.

> This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

> You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

## ChangeLog

Please see [CHANGES.md](CHANGES.md)