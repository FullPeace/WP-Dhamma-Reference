<?php

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AdminPageFramework' ) )
    include_once( FPMTP__PLUGIN_DIR . 'library/admin-page-framework.min.php' );

class FullPeace_Options_Page extends AdminPageFramework
{

    public function setUp() {
        // Create the root menu
        $this->setRootMenuPage(
            'Settings'    // specify the name of the page group
        );

        // Add the sub menus and the pages.
        // The third parameter accepts screen icon url that appears at the top of the page.
        $this->addSubMenuItems(
            array(
                'title' => 'Media To Post Settings',        // page title
                'page_slug' => 'fpmtp_settings',    // page slug
                'screen_icon' => 'https://lh5.googleusercontent.com/-vr0hu0pHcYo/UilDa_OwGYI/AAAAAAAABRg/29eid1MIBW0/s800/demo03_01_32x32.png'     // page screen icon for WP 3.7.x or below
            )
        );
        $this->addSettingSections(
            array(
                'section_id'    => 'fpmtp_settings_general',
                'page_slug'     => 'fpmtp_settings',
                'tab_slug'      => 'fpmtp_tab_main',
                'title' => 'General settings',
                'description'   => 'General settings for the Media To Post plugin.',
                'order' => 10,
            ),
            array(
                'section_id'    => 'fpmtp_settings_ebooks',
                'page_slug'     => 'fpmtp_settings',
                'tab_slug'      => 'fpmtp_tab_ebooks',
                'title' => 'eBooks',
                'description'   => 'Settings for eBooks',
            )
        );

        // Add form fields
        $this->addSettingFields(
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_audio',
                'section_id'    =>      'fpmtp_settings_general',
                'title'			=>	__( 'Enable Audio', FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable Audio Custom Post Type.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this makes the plugin create an Audio entry for each MP3 file uploaded.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_ebooks',
                'section_id'    =>      'fpmtp_settings_general',
                'title'			=>	__( 'Enable eBooks', FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable eBooks Custom Post Type.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this creates an eBooks type that allows upload of PDF, EPUB and MOBI files.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_bios',
                'section_id'    =>      'fpmtp_settings_general',
                'title'			=>	__( 'Enable Bios', FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable Biographies.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this creates a Bio (biography) post type to enter profile information on authors and speakers.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
//            array(	// Single checkbox item - set a check box item to the 'label' element.
//                'field_id'		=>	'fpmtp_enable_ftp',
//                'title'			=>	__( 'Enable FTP for external hosting', FPMTP__I18N_NAMESPACE ),
//                'tip'			=>	__( 'Enable FTP to host files in a different location', FPMTP__I18N_NAMESPACE ),
//                'type'			=>	'checkbox',
//                'label'			=>	__( 'Enabling this allows for FTP upload of media files. See FTP tab for setting this up, when enabled.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
//                'default'		=>	true,
//            ),
            array( // Submit button
                'field_id' => 'submit_button',
                'section_id'    =>      'fpmtp_settings_general',
                'type' => 'submit',
            )
        );

        // Add form fields
        $this->addSettingFields(
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_ebooks_authors',
                'section_id'    =>      'fpmtp_settings_ebooks',
                'title'			=>	__( 'Enable eBooks Authors' , FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable Authors for eBooks.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this allows for tagging eBooks with an Author.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
            array( // Submit button
                'field_id' => 'submit_button',
                'section_id'    =>      'fpmtp_settings_ebooks',
                'type' => 'submit',
            )
        );
        $this->addInPageTabs(
            'fpmtp_settings',    // set the target page slug so that the 'page_slug' key can be omitted from the next continuing in-page tab arrays.
            array(
                'tab_slug'    =>    'fpmtp_tab_main',    // avoid hyphen(dash), dots, and white spaces
                'title'        =>    __( 'General Settings', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'tab_slug'    =>    'fpmtp_tab_audio',
                'title'        =>    __( 'Audio', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'tab_slug'    =>    'fpmtp_tab_ebooks',
                'title'        =>    __( 'EBooks', FPMTP__I18N_NAMESPACE ),
            )//,
//            array(
//                'tab_slug'    =>    'fpmtp_tab_ftp',
//                'title'        =>    __( 'FTP', FPMTP__I18N_NAMESPACE ),
//            )
        );

        $this->setPageHeadingTabsVisibility( false );    // disables the page heading tabs by passing false.
        $this->setInPageTabTag( 'h2' );        // sets the tag used for in-page tabs
    }

    function content_my_tabs( $sContent ) {        // content_{page slug}
        return $sContent
        . '<h3>Page Content Filter</h3>'
        . '<p>This is inserted by the the page <em>content_</em> filter, set in the <b><i>\'content_ + page slug\'</i></b> method.</p>';
    }
    function content_my_tabs_fpmtp_tab_audio( $sContent ) {        // content_{page slug}_{tab slug}
        return $sContent
        . '<h3>Audio posts</h3>'
        . '<p>These are the settings for audio posts (currently MP3 files are supported). The files have to have valid ID3Tags ("artist", "album", and so on) to be rendered correctly.</p>';

    }
    function content_my_tabs_fpmtp_tab_ebooks( $sContent ) {            // content_{page slug}_{tab slug}
        return $sContent
        . '<h3>eBook posts</h3>'
        . '<p>Settings for eBook entries (PDF, EPUB, MOBI).</p>';
    }
//    function content_my_tabs_fpmtp_tab_ftp( $sContent ) {            // content_{page slug}_{tab slug}
//        return $sContent
//        . '<h3>FTP Settings</h3>'
//        . '<p><strong>FTP IS CURRENTLY NOT ENABLED IN THE PLUGIN. CHANGES HERE WILL HAVE NO EFFECT</strong></p>'
//        . '<p>When FTP is enabled and settings correct, the media files will be copied to external hosting via FTP on upload, then removed from this server.</p>';
//    }
    // Action hook methods: 'do_' + page slug.
    public function do_fpmtp_settings() {
        // Show the saved option value.
        // The extended class name is used as the option key. This can be changed by passing a custom string to the constructor.
        echo '<h3>Saved Fields</h3>';
        //echo '<pre>my_text_field: ' . AdminPageFramework::getOption( 'FullPeace_Options_Page', 'my_text_field', 'default text value' ) . '</pre>';
        //echo '<pre>my_textarea_field: ' . AdminPageFramework::getOption( 'FullPeace_Options_Page', 'my_textarea_field', 'default text value' ) . '</pre>';

        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->getArray( AdminPageFramework::getOption( 'FullPeace_Options_Page' ) );

    }

    // Let's try using methods for filters. For filters, the method must return the output.
    // The method name is content_ + page slug, similar to the above methods for action hooks.
    public function content_fpmtp_settings( $sContent ) {
        return $sContent . '<h3>Media To Post Settings</h3><p>Please contact the developer for help with these settings.</p>';
    }

}

if ( is_admin() )
    new FullPeace_Options_Page;