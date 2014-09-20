<?php
class FullPeace_Books_MetaBox extends AdminPageFramework_MetaBox {

    /*
     * ( optional ) Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        /*
         * Adds a contextual help pane at the top right of the page that the meta box resides.
         */
        $sInsert = "<p>" .  __( 'Upload files with the <code>Select File</code> option.', FPMTP__I18N_NAMESPACE ) .
            "</p><p>" . __( 'Click the plus <code>+</code> button to add multiple files (PDF, EPUB, MOBI formats).', FPMTP__I18N_NAMESPACE ) .
            "</p><p>" . __( 'Remove files with the minus <code>-</code> button.</p>', FPMTP__I18N_NAMESPACE ) . "</p>";
        $this->addHelpText(
            __( 'Upload files', FPMTP__I18N_NAMESPACE ),
            $sInsert
        );

        /*
         * Set form sections .
         */
        $this->addSettingSections(
            array(
                'section_id'	=> 'upload_media',
                //'title'	=> __( 'Upload files', FPMTP__I18N_NAMESPACE ),
                //'description'	=> __( 'Upload different file formats of this Book.', FPMTP__I18N_NAMESPACE ),
                'order' => 1,
            )
        );
        /*
         * Adds setting fields into the meta box.
         */
        $this->addSettingFields(
            array( // Media File
                'field_id'		=>	'media_field',
                'section_id'	=>	'upload_media',
                'title'			=>	__( 'Media File', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Upload PDF, EPUB and MOBI files.', FPMTP__I18N_NAMESPACE ),
                'help'			=> __( 'Click to Select File.', FPMTP__I18N_NAMESPACE ),
                'help_aside'	=> __( 'To add book files to the post, click <code>Select File</code> and upload files or select from the Media Library of uploaded files.<br/>', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'media',
                'allow_external_source'	=>	false,
                'repeatable'	=>	true,
            )
        );

    }

    public function content_FullPeace_Books_MetaBox( $sContent ) {	// content_{instantiated class name}
        return $sContent;

        /// Skip the extra text

        // Modify the output $sContent . '<pre>Insert</pre>'
        $sInsert = "<p>" .  __( 'Upload files with the <code>Select File</code> option.', FPMTP__I18N_NAMESPACE ) .
            "</p><p>" . __( 'Click the plus <code>+</code> button to add multiple files (PDF, EPUB, MOBI formats).', FPMTP__I18N_NAMESPACE ) .
            "</p><p>" . __( 'Remove files with the minus <code>-</code> button.</p>', FPMTP__I18N_NAMESPACE ) . "</p>";
        return $sInsert . $sContent;

    }

    /**
     * @param $aInput
     * @param $aOldInput
     * @return mixed
     * @todo Update validation
     */
    public function validation_FullPeace_Books_MetaBox( $aInput, $aOldInput ) {	// validation_{instantiated class name}

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