jQuery(document).ready(function($) {
    var mediaUploader;
    
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
        
        // If the media uploader already exists, open it
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        // Create the media uploader
        mediaUploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false // Set to true if you want to allow multiple file selections
        });
        
        // When an image is selected
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#rj_website_url').val(attachment.url);
            $('#rj_website_url').siblings('img').attr('src', attachment.url).show();
        });
        
        // Open the media uploader
        mediaUploader.open();
    });
});