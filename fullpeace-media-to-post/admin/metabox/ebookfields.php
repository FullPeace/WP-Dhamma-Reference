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
                'section_id'	=> 'upload_media',
                'title'	=> __( 'Upload files', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Upload different file formats of this eBook.', FPMTP__I18N_NAMESPACE ),
            )
        );
        /*
         * ( optional ) Adds setting fields into the meta box.
         */
        $this->addSettingFields(
            array( // Media File
                'field_id'		=>	'media_field',
                'section_id'	=>	'upload_media',
                'title'			=>	__( 'Media File', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Upload PDF, EPUB and MOBI files.', FPMTP__I18N_NAMESPACE ),
                'help'			=> __( 'Click to Select File.', FPMTP__I18N_NAMESPACE ),
                'help_aside'	=> __( 'To add ebook files to the post, click <code>Select File</code> and upload files or select from the Media Library of uploaded files.<br/>', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'media',
                'allow_external_source'	=>	false,
                'repeatable'	=>	true,
            )
        );

    }

    public function content_FullPeace_eBooks_MetaBox( $sContent ) {	// content_{instantiated class name}

        // Modify the output $sContent . '<pre>Insert</pre>'
        $sInsert = "<p>" .  __( 'Upload files with the <code>Select File</code> option. Click the plus <code>+</code> sign to add multiple files (PDF, EPUB, MOBI formats).', FPMTP__I18N_NAMESPACE ) . "</p>";
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

        if ( ! $_fIsValid ) {

            $this->setFieldErrors( $_aErrors );
            $this->setSettingNotice( __( 'There was an error in your input in meta box form fields', FPMTP__I18N_NAMESPACE ) );
            return $aOldInput;

        }

        return $aInput;

    }

}