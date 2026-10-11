jQuery(function($){

	// Download Emails.
	$('.thb-download-emails:not(.disabled)').on("click", function(e){
		var _this = $(this);

		$.ajax({
			method: 'POST',
			url: ajaxurl,
			data : {
				action : 'thb_download_emails'
			},
			beforeSend: function() {
				_this.addClass('disabled');
			},
			success: function(data) {
				_this.removeClass('disabled');

				location.href = data;
			}
		});
		return false;
	});

	// Registration.
	if ( $('.thb-registration').length ) {
		// Activate Product Key
		$('.thb-register:not(.disabled)').on("click", function(e){
			var _this = $(this),
					key = $('#thb_product_key').val(),
					purchase_code = $('#thb_purchase_code').val(),
					is_purchase_code = _this.hasClass('thb_purchase_code'),
					url = is_purchase_code ? _this.data('verify-by-purchase') : _this.data('verify'),
					data = {
						'domain': _this.data('domain')
					};

			if ( is_purchase_code ) {
				data.purchase_code = purchase_code;
			} else {
				data.product_key = key;
			}

			$.ajax({
				method: 'GET',
				url: url,
				data: data,
				beforeSend: function() {
					_this.addClass('disabled');
				},
				error: function(data) {
					_this.removeClass('disabled');
					if (data.responseText) {
						var response = $.parseJSON(data.responseText);
						if ( response.error_message ) {
							if ( 'Invalid product_key' === response.error_message) {
								_this.parents('.step').find('.thb_error_messages').html('<div class="thb-error"><p><span class="dashicons dashicons-warning"></span> Invalid Product Key</p><p><small>Please make sure that you are using the exactly the same​ WordPress URL inside Settings > General.</small></p></div>');
							} else if ( 'Invalid purchase_code' === response.error_message) {
								_this.parents('.step').find('.thb_error_messages').html('<div class="thb-error"><p><span class="dashicons dashicons-warning"></span> Invalid Envato Purchase Code</p><p><small>Please make sure that you are using the correct Envato Purchase Code for this theme.</small></p></div>');
							} else if ( 'Invalid domain' === response.error_message) {
								_this.parents('.step').find('.thb_error_messages').html('<div class="thb-error"><p><span class="dashicons dashicons-warning"></span> Invalid Domain</p><p><small>Please make sure that you are using the exactly the same​ WordPress URL inside Settings > General.</small></p></div>');
							} else {
								_this.parents('.step').find('.thb_error_messages').html('<div class="thb-error"><p><span class="dashicons dashicons-warning"></span> '+response.error_message+'</p></div>');
							}
						}
					}
				},
				success: function(data) {
					if (data.product_key) {
						key = data.product_key;
					}
					$.ajax( ajaxurl, {
						method : 'POST',
						data : {
							action: 'thb_update_options',
							key: key,
							expired: 0,
							security: _this.data('security'),
						},
						success:function() {
							location.reload();
						}
					});

				},
			});
			return false;
		});

		// Remove Product Key
		$('.thb-delete-key').on("click", function(e){
			var _this = $(this);
			$.ajax( ajaxurl, {
				method : 'POST',
				data : {
					action: 'thb_update_options',
					key: '',
					expired: 0,
					security: _this.data('security'),
				},
				success:function() {
					location.reload();
				}
			});
			return false;
		});
	}

	// Demo Content.
	if ( $('#thb-adm-popup').length ) {
		// Thb Admin Popup
		var thb_adm_p_vars = {
			popup: $('#thb-adm-popup'),
			close: $('.thb-popup-close'),
			btn: $('.import-opts-btn')
		};

		thb_adm_p_vars.close.on('click', function() {
			$(this).closest(thb_adm_p_vars.popup).removeClass('opvis');
		});
		$(document).on('keyup', function(e) {
			if (e.keyCode === 27) {
				if (thb_adm_p_vars.popup.hasClass('opvis')) {
					thb_adm_p_vars.close.trigger('click');
				}
			}
		});
		$('.thb-check-line [type=checkbox]').on('change', function() {
			var t = $(this);
			t.toggleClass('thb-checked');
			if (t.attr('id') === 'ty-contents') {
				if (!t.hasClass('child-opened')) {
					t.addClass('child-opened').parent().next().addClass('done');
				} else {
					t.removeClass('child-opened').parent().next().removeClass('done');
				}
			}
		});

		// Open Import Popup
		thb_adm_p_vars.btn.on('click', function() {
			var t = $(this),
				selected = t.data('demo');
			thb_adm_p_vars.popup.find('.button').data('selected', selected);
			thb_adm_p_vars.popup.find('figure img').attr('src', t.closest('.theme').find('img').attr('src'));
			thb_adm_p_vars.popup.find('[type=checkbox]');
			thb_adm_p_vars.popup.addClass('opvis');
		});

		// Demo Content Import
		var thb_data = new FormData(),
				thb_once = false;

		if (typeof ocdi !== 'undefined') {
			thb_data.append( 'action', 'ocdi_import_demo_data' );
			thb_data.append( 'security', ocdi.ajax_nonce );
		}
		/* jshint ignore:start */
		function thb_ajaxCall(thb_data) {

			// AJAX call.
			$.ajax({
				method:     'POST',
				url:        ocdi.ajax_url,
				data:       thb_data,
				contentType: false,
				processData: false
			})
			.done( function( response ) {
				if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
					thb_ajaxCall( thb_data );
				} else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
					// Fix for data.set and data.delete, which they are not supported in some browsers.
					var newData = new FormData();
					newData.append( 'action', 'ocdi_after_import_data' );
					newData.append( 'security', ocdi.ajax_nonce );
					thb_ajaxCall( newData );
				} else {
					location.reload();
				}
			});
		}


		// Import Form Submit
		thb_adm_p_vars.popup.find('form').on('submit', function(e) {
			e.preventDefault();
			var t = $(this),
				demo = t.find('.button').data('selected');

			thb_adm_p_vars.popup.find('form').addClass('thb-loading');
			t.closest('.thb-popup-box').find('.thb-import-loading').addClass('opvis');
			t.children('[type=submit]').addClass('disabled').attr('disabled', 'disabled').unbind('click');

			thb_data.append( 'selected', demo );
			thb_data.append( 'thb_import_options', t.serialize());

			thb_ajaxCall(thb_data);
		});
		/* jshint ignore:end */
	}

	// Product Attribute Settings.
	if ( $('#thb-term-color').length ) {
		$('#thb-term-color').wpColorPicker();

		if ($('#thb-term-image').val() === 0) {
			$('.thb_remove_header').hide();
		}
		// Uploading files
		var header_file_frame;

		$(document).on( 'click', '.thb_upload_header', function( event ){

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( header_file_frame ) {
				header_file_frame.open();
				return;
			}

			// Create the media frame.
			header_file_frame = wp.media.frames.downloadable_file = wp.media({
				title: thb_admin.i18n.mediaTitle,
				button: {
					text: thb_admin.i18n.mediaButton,
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			header_file_frame.on( 'select', function() {
				var attachment = header_file_frame.state().get('selection').first().toJSON();

				$( '#thb-term-image').val( attachment.id );
				$( '#thb_term_image_holder img').attr('src', attachment.url );
				$( '.thb_remove_header').show();
			});

			// Finally, open the modal.
			header_file_frame.open();
		});

		$(document).on( 'click', '.thb_remove_header', function( event ){
			$('#thb_term_image_holder img').attr('src', thb_admin.wc_placeholder);
			$('#thb-term-image').val('');
			$('.thb_remove_header').hide();
			return false;
		});
	}

	// Product Category Settings.
	if ( $('#product_cat_header').length ) {
		if ($('#product_cat_thumbnail_id').val() === 0) {
			$('.remove_image_button').hide();
		}
		if ($('#product_cat_header_id').val() === 0) {
			$('.thb_remove_header').hide();
		}
		// Uploading files
		var header_file_frame2;

		$(document).on( 'click', '.thb_upload_header', function( event ){

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( header_file_frame2 ) {
				header_file_frame2.open();
				return;
			}

			// Create the media frame.
			header_file_frame2 = wp.media.frames.downloadable_file = wp.media({
				title: thb_admin.i18n.mediaTitle,
				button: {
					text: thb_admin.i18n.mediaButton,
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			header_file_frame2.on( 'select', function() {
				var attachment = header_file_frame2.state().get('selection').first().toJSON();

				$( '#product_cat_header_id').val( attachment.id );
				$( '#product_cat_header img').attr('src', attachment.url );
				$( '.thb_remove_header').show();
			});

			// Finally, open the modal.
			header_file_frame2.open();
		});

		$(document).on( 'click', '.thb_remove_header', function( event ){
			$('#product_cat_header img').attr('src', thb_admin.wc_placeholder);
			$('#product_cat_header_id').val('');
			$('.thb_remove_header').hide();
			return false;
		});
	}
});