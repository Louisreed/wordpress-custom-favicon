// Open the WooCommerce media library when the "Upload Image" button is clicked
document.getElementById('upload_image_button').addEventListener('click', function(event) {
    event.preventDefault();

    var frame = wp.media({
        title: 'Select or Upload Media',
        button: {
            text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
    });

    // When a file is selected, run a callback function
    frame.on( 'select', function() {
        // Get the selected attachment.
        var attachment = frame.state().get('selection').first().toJSON();

        // Insert the attachment URL into the field
        document.getElementsByName('custom_favicon')[0].value = attachment.url;
    });

    // Open the modal
    frame.open();
});
