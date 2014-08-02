<?php
class FullPeace_eBooks_MetaBox extends AdminPageFramework_MetaBox {

    /*
     * ( optional ) Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        /*
         * ( optional ) Adds a contextual help pane at the top right of the page that the meta box resides.
         */
        $this->addHelpText(
            __( 'This text will appear in the contextual help pane.', FPMTP__I18N_NAMESPACE ),
            __( 'This description goes to the sidebar of the help pane.', FPMTP__I18N_NAMESPACE )
        );

        /*
         * ( optional ) Set form sections - if not set, the system default section will be applied so you don't worry about it.
         */
        $this->addSettingSections(
            array(
                'section_id'	=> 'selectors',
                'title'	=> __( 'Selectors', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'These are grouped in the <code>selectors</code> section.', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'section_id'	=> 'upload_media',
                'title'	=> __( 'Upload files', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Upload different file formats of this eBook.', FPMTP__I18N_NAMESPACE ),
            )
        );
        $this->addSettingSections(
            array(
                'section_id'	=>	'tabbed_sections_a',
                'section_tab_slug'	=>	'tabbed_sections',
                'title'			=>	__( 'Section Tab A', FPMTP__I18N_NAMESPACE ),
                'description'	=>	__( 'This is the first item of the tabbed section.', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'section_id'	=>	'tabbed_sections_b',
                'title'			=>	__( 'Section Tab B', FPMTP__I18N_NAMESPACE ),
                'description'	=>	__( 'This is the second item of the tabbed section.', FPMTP__I18N_NAMESPACE ),
            ),
            array(
                'section_id'	=>	'repeatable_tabbed_sections',
                'tab_slug'		=>	'sections',
                'section_tab_slug'	=>	'repeatable_tabbes_sections',
                'title'			=>	__( 'Repeatable', FPMTP__I18N_NAMESPACE ),
                'description'	=>	__( 'It is possible to tab repeatable sections.', FPMTP__I18N_NAMESPACE ),
                'repeatable'	=>	true,	// this makes the section repeatable
            ),
            array(
                'section_tab_slug' => '',	// reset the target tab slug  for the next use.
            )
        );
        /*
         * ( optional ) Adds setting fields into the meta box.
         */
        $this->addSettingFields(
            array(
                'field_id'		=> 'metabox_text_field',
                'type'			=> 'text',
                'title'			=> __( 'Text Input', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Type more than two characters.', FPMTP__I18N_NAMESPACE ),
                'help'			=> __( 'This is help text.', FPMTP__I18N_NAMESPACE ),
                'help_aside'	=> __( 'This is additional help text which goes to the side bar of the help pane.', FPMTP__I18N_NAMESPACE ),
            ),
            array( // Media File
                'field_id'		=>	'media_field',
                'section_id'	=>	'upload_media',
                'title'			=>	__( 'Media File', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'media',
                'allow_external_source'	=>	false,
            ),
            array( // Media File with Attributes
                'field_id'		=>	'media_with_attributes',
                'title'			=>	__( 'Media File with Attributes', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'media',
                'attributes_to_store'	=>	array( 'id', 'caption', 'description' ),
            ),
            array( // Repeatable Media Files
                'field_id'		=>	'media_repeatable_fields',
                'title'			=>	__( 'Repeatable Media Files', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'media',
                'repeatable'	=>	true,
            )
        );
        $this->addSettingFields(
            array (
                'section_id'	=> 'upload_media',
                'field_id'		=> 'image_field',
                'type'			=> 'image',
                'title'			=> __( 'Image', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'The description for the field.', FPMTP__I18N_NAMESPACE ),
            ),
            array()
        );

    }

    public function content_FullPeace_eBooks_MetaBox( $sContent ) {	// content_{instantiated class name}

        // Modify the output $sContent . '<pre>Insert</pre>'
        $sInsert = "<p>" . sprintf( __( 'This text is inserted with the <code>%1$s</code> hook.', FPMTP__I18N_NAMESPACE ), __FUNCTION__ ) . "</p>";
        return $sInsert . $sContent;

    }

    /**
     * @param $aInput
     * @param $aOldInput
     * @return mixed
     * @todo Update validation
     */
    public function validation_FullPeace_eBooks_MetaBox( $aInput, $aOldInput ) {	// validation_{instantiated class name}

        $_fIsValid = true;
        $_aErrors = array();

        // You can check the passed values and correct the data by modifying them.
        // $this->oDebug->logArray( $aInput );

        // Validate the submitted data.
        if ( strlen( trim( $aInput['metabox_text_field'] ) ) < 3 ) {

            $_aErrors['metabox_text_field'] = __( 'The entered text is too short! Type more than 2 characters.', FPMTP__I18N_NAMESPACE ) . ': ' . $aInput['metabox_text_field'];
            $_fIsValid = false;

        }
        if ( empty( $aInput['upload_media']['metabox_password'] ) ) {

            $_aErrors['upload_media']['metabox_password'] = __( 'The password cannot be empty.', FPMTP__I18N_NAMESPACE );
            $_fIsValid = false;

        }

        if ( ! $_fIsValid ) {

            $this->setFieldErrors( $_aErrors );
            $this->setSettingNotice( __( 'There was an error in your input in meta box form fields', FPMTP__I18N_NAMESPACE ) );
            return $aOldInput;

        }

        return $aInput;

    }

}