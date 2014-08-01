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

        // Add form fields
        $this->addSettingFields(
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_talks',
                'title'			=>	__( 'Enable Talks', FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable Talks Custom Post Type.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this makes the plugin create a Talk for each MP3 file uploaded.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
            array(	// Single checkbox item - set a check box item to the 'label' element.
                'field_id'		=>	'fpmtp_enable_ebooks',
                'title'			=>	__( 'Enable eBooks', FPMTP__I18N_NAMESPACE ),
                'tip'			=>	__( 'Enable eBooks Custom Post Type.', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'checkbox',
                'label'			=>	__( 'Enabling this creates an eBooks type that allows upload of PDF, EPUB and MOBI files.', FPMTP__I18N_NAMESPACE ),	//'syntax fixer
                'default'		=>	true,
            ),
            array( // Submit button
                'field_id' => 'submit_button',
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
                'tab_slug'    =>    'fpmtp_tab_talks',
                'title'        =>    __( 'Talks', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'tab_slug'    =>    'fpmtp_tab_ebooks',
                'title'        =>    __( 'EBooks', FPMTP__I18N_NAMESPACE ),
            )
        );

        $this->setPageHeadingTabsVisibility( false );    // disables the page heading tabs by passing false.
        $this->setInPageTabTag( 'h2' );        // sets the tag used for in-page tabs
    }

    function content_my_tabs( $sContent ) {        // content_{page slug}
        return $sContent
        . '<h3>Page Content Filter</h3>'
        . '<p>This is inserted by the the page <em>content_</em> filter, set in the <b><i>\'content_ + page slug\'</i></b> method.</p>';
    }
    function content_my_tabs_fpmtp_tab_talks( $sContent ) {        // content_{page slug}_{tab slug}
        return $sContent
        . '<h3>Tab Content Filter</h3>'
        . '<p>This is the second tab! This is inserted by the <b><i>\'content_ + page slug + _ + tab slug\'</i></b> method.</p>';

    }
    function content_my_tabs_fpmtp_tab_ebooks( $sContent ) {            // content_{page slug}_{tab slug}
        return $sContent
        . '<h3>Tab Content Filter</h3>'
        . '<p>This is the third tab!.</p>';
    }
    // Action hook methods: 'do_' + page slug.
    public function do_fpmtp_settings() {
        // Show the saved option value.
        // The extended class name is used as the option key. This can be changed by passing a custom string to the constructor.
        echo '<h3>Saved Fields</h3>';
        echo '<pre>my_text_field: ' . AdminPageFramework::getOption( 'FullPeace_Options_Page', 'my_text_field', 'default text value' ) . '</pre>';
        echo '<pre>my_textarea_field: ' . AdminPageFramework::getOption( 'FullPeace_Options_Page', 'my_textarea_field', 'default text value' ) . '</pre>';

        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->getArray( AdminPageFramework::getOption( 'FullPeace_Options_Page' ) );

    }

    // Let's try using methods for filters. For filters, the method must return the output.
    // The method name is content_ + page slug, similar to the above methods for action hooks.
    public function content_fpmtp_settings( $sContent ) {
        return $sContent . '<h3>Filter Hook Method</h3><p>This is the first page from the filter! ( content_ + pageslug )</p>';
    }

}

if ( is_admin() )
    new FullPeace_Options_Page;