$(document).on('click', '.product-thumb .add-up.add-action', function () {
	var $input = $(this).closest('.quantity_plus_minus').find('.quantity-num');
	if($input.prop('disabled') != true){
		var data_min_val = 1;
	} else {
		var data_min_val = parseInt($input.data('minimum'));
	}
	var count = parseInt($input.val()) + data_min_val;
	$input.val(count);
	$input.change();
});

$(document).on('click', '.product-thumb .add-down.add-action', function () {
	var $input = $(this).closest('.quantity_plus_minus').find('.quantity-num');
	if($input.prop('disabled') != true){
		var data_min_val = 1;
	} else {
		var data_min_val = parseInt($input.data('minimum'));
	}
	var count = parseInt($input.val()) - data_min_val;
	count = count < parseInt($input.data('minimum')) ? parseInt($input.data('minimum')) : count;
	$input.val(count);
	$input.change();
});

$(document).on('change', '.product-thumb .quantity-num, .product-thumb .option input[type="checkbox"], .product-thumb .option input[type="radio"], .product-thumb .option select', function() {
	recalcQuantity(this);
});

$(document).on('input', '.product-thumb .quantity-num', function () {
	validateQuantity(this);
});

function validateQuantity(elem){
	input = $(elem);
	var minimum = input.data('minimum');
	var count = $(elem).val().replace(/[^\d]/g, '');
	if (count == '') count = minimum;
	if (count == '0') count = minimum;
	input.val(count);
	input.change();
}

function recalcQuantity(element, autocalc = true) {
	elem = $(element).closest('.product-thumb');

	if(elem.length) {
		var quantity = elem.find('.quantity-num').val();

		if(isNaN(quantity)){
			quantity = '1';
		}
		var minval = elem.find('.quantity-num').data('minimum');
		quantity = quantity.replace(/[^\d]/g, '');
		if (quantity == '') quantity = minval;
		if (quantity == '0') quantity = minval;

		var main_price = parseFloat(elem.find('.price').data('price-no-format'));
		var special = elem.find('.price').data('special-no-format');
		if(special !=''){
			var special_price = parseFloat(special);
		} else {
			var special_price = false;
		}



		var options_price = 0;
		elem.find('input:checked, option:selected').each(function() {
			if ($(this).data('option-prefix') == '=') {
				options_price += Number($(this).data('option-price'));
				main_price = 0;
				special_price = 0;
			}
			if ($(this).data('option-prefix') == '+') {
				options_price += Number($(this).data('option-price'));
			}
			if ($(this).data('option-prefix') == '-') {
				options_price -= Number($(this).data('option-price'));
			}

			if ($(this).data('option-prefix') == '*') {
				 options_price *= Number($(this).data('option-price'));
				 main_price *= Number($(this).data('option-price'));
				 special_price *= Number($(this).data('option-price'));
			}

		});

		main_price += options_price;

		special_price += options_price;

		if(special !=''){
			special_coefficient = parseFloat(elem.find('.price').data('price-no-format'))/parseFloat(elem.find('.price').data('special-no-format'));
			main_price = special_price * special_coefficient;
			special_price *= quantity;
		}

		main_price *= quantity;


		if(autocalc){
			var start_price = parseFloat(elem.find('.price_no_format').html().replace(/[^.\d]+/g,""));
			$({val:start_price}).animate({val:main_price}, {
				duration: 400,
				step: function(val) {
					elem.find('.price_no_format').html(price_format(val));
				}
			});



			if(special !=''){
				var start_price = parseFloat(elem.find('.special_no_format').html().replace(/[^.\d]+/g,""));
				$({val:start_price}).animate({val:special_price}, {
					duration: 400,
					step: function(val) {
						elem.find('.special_no_format').html(price_format(val));
					}
				});
			}
		} else {
			elem.find('.price_no_format').html(price_format(main_price));
			elem.find('.special_no_format').html(price_format(special_price));
		}

	}
}


/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});
function getAjaxLiveSearch(request, response){
	$.ajax({
		url: 'index.php?route=extension/module/cyber_autosearch/ajaxLiveSearch&filter_name=' +  encodeURIComponent(request),
		dataType : 'json',
		success : function(json) {
			response($.map(json, function(item) {
				return {
					label: item.name,
					name: item.name1,
					value: item.product_id,
					model: item.model,
					quantity: item.quantity,
					stock_status: item.stock_status,
					image: item.image,
					manufacturer: item.manufacturer,
					price: item.price,
					special: item.special,
					category: item.category,
					rating: item.rating,
					reviews: item.reviews,
					href:item.href,
				}
			}));
		}
	});
}
// autocompleteSerach */
(function($) {
	$.fn.autocompleteSerach = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {

				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {

						if(json[i].product_id!=0){
							html += '<li><a href="'+ json[i].href +'" class="autosearch_link">';
							html += '<div class="ajaxadvance">';
							html += '<div class="image">';
							if(json[i].image){
							html += '<img title="'+json[i].name+'" src="'+json[i].image+'"/>';
							}
							html += '</div>';
							html += '<div class="content">';
							html += 	'<div class="name">'+json[i].label+'</div>';
							if(json[i].stock_status){
								if(json[i].quantity > 0){
									html += 	'<div class="stock-status instock">'+ json[i].stock_status +'</div>';
								} else {
									html += 	'<div class="stock-status outofstock">'+ json[i].stock_status +'</div>';
								}
							}
							if(json[i].model){
							html += 	'<div class="model">' + json[i].model +'</div>';
							}
							if (json[i].rating) {
								html +=		'<div class="ratings"> ';
									for (var k = 1; k <= 5; k++) {
										if (json[i].rating < k) {
											html +='<span class="product-rating-star"><svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 8.75L3.47287 9.81573C2.73924 10.2014 1.88181 9.57846 2.02192 8.76155L2.40907 6.50431L0.769082 4.90572C0.175565 4.32718 0.503075 3.31921 1.3233 3.20002L3.5897 2.87069L4.60326 0.816985C4.97008 0.0737394 6.02992 0.0737402 6.39674 0.816986L7.4103 2.87069L9.67671 3.20002C10.4969 3.31921 10.8244 4.32718 10.2309 4.90572L8.59093 6.50431L8.97808 8.76155C9.11819 9.57846 8.26076 10.2014 7.52713 9.81573L5.5 8.75Z" fill="#EFEFEF"/></svg></span>';
										} else {
											html +='<span class="product-rating-star"><svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 8.75L3.47287 9.81573C2.73924 10.2014 1.88181 9.57846 2.02192 8.76155L2.40907 6.50431L0.769082 4.90572C0.175565 4.32718 0.503075 3.31921 1.3233 3.20002L3.5897 2.87069L4.60326 0.816985C4.97008 0.0737394 6.02992 0.0737402 6.39674 0.816986L7.4103 2.87069L9.67671 3.20002C10.4969 3.31921 10.8244 4.32718 10.2309 4.90572L8.59093 6.50431L8.97808 8.76155C9.11819 9.57846 8.26076 10.2014 7.52713 9.81573L5.5 8.75Z" fill="#E5DB77"/></svg></span>';
										}
									}
								html +=		'</div>';
							}
							if(json[i].manufacturer){
							html += 	'<div class="manufacturer">'+ json[i].manufacturer +'</div>';
							}
							if(json[i].price){
							html += 	'<div class="price"> ';
							if (!json[i].special) {
							html +=			 json[i].price;
							} else {
							html +=			'<span class="price-old">'+ json[i].price +'</span> <span class="price-new">'+ json[i].special +'</span>';
							}
							html +=		'</div>';
							}

							html +='</div>';
							html += '</div></a></li>'
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			if(!$(this).next().hasClass('autosearch')){
				$(this).after('<ul class="dropdown-menu autosearch"></ul>');
			}
			$(this).siblings('ul.dropdown-menu autosearch').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);

$(document).on('click', '#login-popup,.i_am_registered', function (e) {
	e.preventDefault();
	var href = $(e.target).attr('data-load-url');
	$.get(href, function(data) {
		$('<div id="login-form-popup" class="modal fade" role="dialog">' + data + '</div>').modal('show');
	});

});

$(document).on('hide.bs.modal', '.modal.fade', function (e) {
	if(($(this).attr('id') !='login-form') && ($(this).attr('id') !='modal-compare') && ($(this).attr('id') !='modal-wishlist')){
		$('.modal.fade').remove();
	}
});