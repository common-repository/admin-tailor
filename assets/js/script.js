window.jQuery(function ($) {

	/**
	 * Logo uploader.
	 */
	$('body').on('click', '.jn-admin-tailor-upload', function (event) {
		event.preventDefault();

		var wp         = window.wp;
		var attachment = null;
		var button     = $(this);
		var imageId    = button.next().next().val();

		var customUploader = window.wp.media({
			title: 'Insert image',
			library: {
				type: 'image'
			},
			button: {
				text: 'Use this image'
			},
			multiple: false
		}).on('select', function () {
			attachment = customUploader.state().get('selection').first().toJSON();
			button.removeClass('button').html('<img src="' + attachment.url + '" alt="Logo">');
			button.next().show(); // show "Remove logo" link
			button.next().next().val(attachment.id); // Populate the hidden field with image ID
			button.siblings('.description').hide();
		});

		// Already selected images.
		customUploader.on('open', function () {
			if (imageId) {
				var selection = customUploader.state().get('selection');
				attachment = wp.media.attachment(imageId);
				attachment.fetch();
				selection.add(attachment ? [attachment] : []);
			}
		});

		customUploader.open();

	});

	// Handler for remove button.
	$('body').on('click', '.jn-admin-tailor-remove', function (event) {
		event.preventDefault();

		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.hide().prev().addClass('button').html('Upload image'); // replace the image with text
	});


	/**
	 * Color picker.
	 */
	$('input[name="jn_admin_tailor_login_color"]').wpColorPicker();
	$('input[name="jn_admin_tailor_login_footer_color"]').wpColorPicker();

	/**
	 * Login background patterns.
	 */
	var activePattern = $('input[name="jn_admin_tailor_login_pattern_url"]');
	var patternList   = $('.patterns').find('li');

	patternList.on('click', function () {
		var $this = $(this);
		var url   = $this.find('img').attr('src');

		patternList.find('span').remove();

		$this.prepend('<span>&#10003;</span>');
		activePattern.val(url);
	});

});
