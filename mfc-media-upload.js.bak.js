jQuery(document).ready(function($){

    var custom_uploader;
    var file_uploader;

     $('input.select-img').on('click', function(event){
   
        event.preventDefault();

       //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        } 

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {

            attachment = custom_uploader.state().get('selection').first().toJSON();

            $('.img').val(attachment.url);
            $('.img_id').val(attachment.id);
            $('#upload_img_preview.img').attr('src', attachment.url);


        custom_uploader = null; 
        });

        //Open the uploader dialog
        custom_uploader.open();

        

    });

    $('input.select-file').on('click', function(event){
       
        event.preventDefault();

       //If the uploader object has already been created, reopen the dialog
        if (file_uploader) {
            file_uploader.open();
            return;
        } 

        //Extend the wp.media object
        file_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose File',
            button: {
                text: 'Choose File'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        file_uploader.on('select', function() {
            
            file_attachment = file_uploader.state().get('selection').first().toJSON();

            $('.file').val(file_attachment.url);
            $('.file_id').val(file_attachment.id);

        file_uploader = null;
        });

        //Open the uploader dialog
        file_uploader.open();

        

    });




});