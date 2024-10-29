jQuery( function( $ ) {

	/**
	 * Logo uploader.
	 */
	$( 'body' ).on( 'click', '.jn-admin-tailor-upload', function( event ) {
		event.preventDefault();
		
		const button = $(this)
		const imageId = button.next().next().val();
		
		const customUploader = wp.media({
			title: 'Insert image',
			library : {
				type : 'image'
			},
			button: {
				text: 'Use this image'
			},
			multiple: false
		}).on( 'select', function() {
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();
			button.removeClass( 'button' ).html( '<img src="' + attachment.url + '" alt="Logo">');
			button.next().show(); // show "Remove logo" link
			button.next().next().val( attachment.id ); // Populate the hidden field with image ID
			button.siblings('.description').hide();
		})

		// Already selected images.
		customUploader.on( 'open', function() {
			if ( imageId ) {
			  const selection = customUploader.state().get( 'selection' )
			  attachment = wp.media.attachment( imageId );
			  attachment.fetch();
			  selection.add( attachment ? [attachment] : [] );
			}
		})

		customUploader.open()
	
	});

	// Handler for remove button.
	$( 'body' ).on( 'click', '.jn-admin-tailor-remove', function( event ) {
		event.preventDefault();

		const button = $(this);
		button.next().val( '' ); // emptying the hidden field
		button.hide().prev().addClass( 'button' ).html( 'Upload image' ); // replace the image with text
	});


	/**
	 * Color picker
	 */
	 $( '.jn-admin-tailor-login-color' ).wpColorPicker();

});
