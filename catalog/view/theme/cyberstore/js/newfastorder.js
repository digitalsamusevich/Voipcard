function quickorder_confirm() {
	$('#popup-quickorder #fastorder_data').prepend('<div class="masked_bg"></div><div class="loading_masked"></div>');
	var success = 'false';
		$('#callback_url').val(window.location.href);
		$.ajax({
			url: 'index.php?route=extension/module/cyber_newfastorder',
			type: 'post',
			data: $('#fastorder_data').serialize() + '&action=send',
			dataType: 'json',
			beforeSend: function() {
				loading_masked(true);
			},
			success: function(json) {
				loading_masked(false);
				$('.masked_bg').remove();
				$('.loading_masked').remove();
				$('.alert').remove();
				$('#popup-quickorder #contact-name').removeClass('error_input');
				$('#popup-quickorder #contact-phone').removeClass('error_input');
				$('#popup-quickorder #contact-comment').removeClass('error_input');
				$('#popup-quickorder #contact-email').removeClass('error_input');
				$('.text-danger').empty();
				if (json['error']) {
					if (json['error']['name_fastorder']) {
						$('#popup-quickorder #contact-name').attr('placeholder',json['error']['name_fastorder']);
						$('#popup-quickorder #contact-name').addClass('error_input');
					}
					if (json['error']['phone']) {
						$('#popup-quickorder #contact-phone').attr('placeholder',json['error']['phone']);
						$('#popup-quickorder #contact-phone').addClass('error_input');
					}
					if (json['error']['comment_buyer']) {
						$('#popup-quickorder #contact-comment').attr('placeholder',json['error']['comment_buyer']);
						$('#popup-quickorder #contact-comment').addClass('error_input');
					}
					if (json['error']['email_error']) {
						$('#popup-quickorder #contact-email').attr('placeholder',json['error']['email_error']);
						$('#popup-quickorder #contact-email').addClass('error_input');
					}
					if (json['error']['error_agree']) {
						$('.error_agree').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['error_agree'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['error']['option']) {
							for (i in json['error']['option']) {
								$('.option-error-'+ i).html(json['error']['option'][i]);
							}
					}

				}

				if (json['success']){
					$('#modal-quickorder').modal('hide');

					html  = '<div id="modal-addquickorder" class="modal fade">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content cs-modal-success">';
					html += '      <div class="modal-body"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"> <div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '    </div>';
					html += '  </div>';
					html += '</div>';

					$('body').append(html);
					setTimeout(function () {
						$('#modal-addquickorder').modal('show');
					}, 500);

				}
			}

		});
}

function quickorder_confirm_checkout() {
	$('#popup-quickorder #fastorder_data').prepend('<div class="masked_bg"></div><div class="loading_masked"></div>');
	$('#quickorder_url').val(window.location.href);
	var success = 'false';
	$.ajax({
		url: 'index.php?route=extension/module/cyber_newfastordercart',
		type: 'post',
		data: $('#fastorder_data').serialize() + '&action=send',
		dataType: 'json',
		beforeSend: function() {
			loading_masked(true);
		},
		success: function(json) {
			loading_masked(false);
			$('.masked_bg').remove();
			$('.loading_masked').remove();
			$('.alert').remove();
			$('#contact-name').removeClass('error_input');
			$('#contact-phone').removeClass('error_input');
			$('#contact-comment').removeClass('error_input');
			$('#contact-email').removeClass('error_input');
			if (json['error']) {
				if (json['error']['name_fastorder']) {
					$('#contact-name').attr('placeholder',json['error']['name_fastorder']);
					$('#contact-name').addClass('error_input');
				}
				if (json['error']['phone']) {
					$('#contact-phone').attr('placeholder',json['error']['phone']);
					$('#contact-phone').addClass('error_input');
				}
				if (json['error']['comment_buyer']) {
					$('#contact-comment').attr('placeholder',json['error']['comment_buyer']);
					$('#contact-comment').addClass('error_input');
				}
				if (json['error']['email_error']) {
					$('#contact-email').attr('placeholder',json['error']['email_error']);
					$('#contact-email').addClass('error_input');
				}
				if (json['error']['error_agree']) {
					$('.error_agree').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['error_agree'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
				if (json['error']['comment_buyer']) {
					$('#error_comment_buyer').html(json['error']['comment_buyer']);
				}
			}

			if (json['success']){
				$('.shopping-cart #cart').load('index.php?route=common/cart/info .shopping-cart #cart');
				$('#modal-quickorder').modal('hide');
				html  = '<div id="modal-addquickorder" class="modal fade">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content cs-modal-success">';
				html += '      <div class="modal-body"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"> <div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div>';

				$('body').append(html);
				setTimeout(function () {
					$('#modal-addquickorder').modal('show');
				}, 500);
			}
		}

		});
}