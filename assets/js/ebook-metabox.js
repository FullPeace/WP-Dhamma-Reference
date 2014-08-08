// **************************************************************
// Refresh pdf function - fired when a new pdf is selected
// TODO: Update to handle more file types
// **************************************************************

function Refresh_PDF(the_ids){
    jQuery('#pdf-spinner').css('display','inline');

    var data = {
        action: 'refresh_pdf',
        id: the_ids
    };

    jQuery.post(ajaxurl, data, function(response) {
        var obj;
        try {
            obj = jQuery.parseJSON(response);
        }
        catch(e) {  // bad JSON catch
            // add some error messaging ?
        }

        if(obj.success === true) { // it worked. AS IT SHOULD.
            jQuery('div#pdf-wrapper').html(obj.pdf);
            jQuery('#pdf-spinner').css('display','none');
            // add some success messaging ?
        }
        else {  // something else went wrong
            // add some error messaging ?
        }
    });
}

// **************************************************************
// now start the engine
// **************************************************************

jQuery(document).ready( function($) {

    jQuery('#insert-pdf-button,#insert-mobi-button,#insert-epub-button').click(function(e) {

        e.preventDefault();
        var frame = wp.media({
            title : 'Pick the PDF to attach to this entry',
            frame: 'select',
            multiple : false,
            library : {
                type : 'application/pdf'
            },
            button : { text : 'Insert' }
        });

        frame.on('close',function() {
            // get selections and save to hidden input plus other AJAX stuff etc.
            var selection = frame.state().get('selection');
            var pdf_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                pdf_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = pdf_ids.join(",");
            jQuery('#pdf-id').val(ids);
            Refresh_PDF(ids);
        });

        frame.on('open',function() {
            var selection = frame.state().get('selection');
            ids = jQuery('#pdf-id').val().split(',');
            ids.forEach(function(id) {
                attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });

        frame.open();
    });

});