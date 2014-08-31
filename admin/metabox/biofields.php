<?php
class FullPeace_Bios_MetaBox extends AdminPageFramework_MetaBox {

    /*
     * ( optional ) Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        /*
         * Adds a contextual help pane at the top right of the page that the meta box resides.
         */
        $this->addHelpText(
            __( 'No help text.', FPMTP__I18N_NAMESPACE ),
            __( 'We have not written any help text for this. Please contact the developer for support.', FPMTP__I18N_NAMESPACE )
        );

        /*
         * Set form sections .
         */
        $this->addSettingSections(
            array(
                'section_id'	=> 'bio_details',
                'title'	=> __( 'Upload files', FPMTP__I18N_NAMESPACE ),
                'description'	=> __( 'Upload different file formats of this Book.', FPMTP__I18N_NAMESPACE ),
            )
        );
        /*
         * Adds setting fields into the meta box.
         */
        $ordain_years = array('-' => 'Not ordained');
        for ($x = date('Y'); $x > (date('Y')-120); $x--){
            $ordain_years[$x] = $x . '';
        }
        $this->addSettingFields(
            array( // Media File
                'field_id'		=>	'year_ordained',
                'section_id'	=>	'bio_details',
                'title'			=>	__( 'Year ordained', FPMTP__I18N_NAMESPACE ),
                'type'			=>	'select',
                'label'			=> $ordain_years,
                'default' 		=>	0,	// 0 means the first item
            )
        );

    }

    public function content_FullPeace_Bios_MetaBox( $sContent ) {	// content_{instantiated class name}
        return $sContent;

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
    public function validation_FullPeace_Bios_MetaBox( $aInput, $aOldInput ) {	// validation_{instantiated class name}

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