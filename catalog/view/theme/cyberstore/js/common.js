function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
function viewport() {
	var e = window, a = 'inner';
	if (!('innerWidth' in window )) {
		a = 'client';
		e = document.documentElement || document.body;
	}
	return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}
function owlSliderModule(selector, row_items = false) {
	let breakpoint = 350;
	if(row_items == 1){
		breakpoint = 500;
	}
	$(selector).owlCarousel({
		responsiveBaseWidth: $(selector),
		itemsCustom: [[0, 1],[breakpoint, 2],[750, 3],[950, 4],[1200, 5]],
		itemsMobile: false,
		slideSpeed: 200,
		paginationSpeed: 300,
		navigation: true,
		stopOnHover: true,
		mouseDrag: false,
		pagination: false,
		autoPlay: false,
		navigationText: ['<div class="btn btn-carousel-module next-prod"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="9" fill="none" viewBox="0 0 5 9"><path fill="#000" fill-rule="evenodd" d="M1.935 4.209a.5.5 0 00.004.65l2.439 2.814a.5.5 0 01-.756.655L1.183 5.514a1.5 1.5 0 01-.01-1.952L3.618.677a.5.5 0 11.763.646L1.935 4.21z" clip-rule="evenodd"></path></svg></div>', '<div class="btn btn-carousel-module prev-prod"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="9" fill="none" viewBox="0 0 5 9"><path fill="#000" fill-rule="evenodd" d="M3.065 4.791a.5.5 0 00-.004-.65L.622 1.327a.5.5 0 01.756-.655l2.439 2.814a1.5 1.5 0 01.01 1.952L1.382 8.323a.5.5 0 11-.763-.646L3.065 4.79z" clip-rule="evenodd"></path></svg></div>'],
	});
}

function addStyleHeader(styles) {
    var css = document.createElement('style');
    if (css.styleSheet) {
        css.styleSheet.cssText = styles;
    } else {
        css.appendChild(document.createTextNode(styles));
    }
    document.getElementsByTagName("head")[0].appendChild(css);
}

function heightblockauto() {
	max_height_div('.product-thumb .option.featured-opt');
	max_height_div('.product-thumb .option.latest-opt');
	max_height_div('.product-thumb .option.latest-gv-opt');
	max_height_div('.product-thumb .option.bestseller-opt');
	max_height_div('.product-thumb .option.special-opt');
	max_height_div('.product-thumb .option.productany-opt');
	max_height_div('.product-thumb .option.productviewed-opt');
	max_height_div('.product-thumb .option.tablatest-opt');
	max_height_div('.product-thumb .option.tabspecial-opt');
	max_height_div('.product-thumb .option.tabfeatured-opt');
	max_height_div('.product-thumb .option.tabbestseller-opt');
	max_height_div('.product-thumb .option.tabpopular-opt');
	max_height_div('.product-grid .product-thumb .option.productpage-opt');
}

function max_height_div(div) {
	var maxheight = 0;
	$(div).each(function(){
	$(this).removeAttr('style');
		if($(this).height() > maxheight) {
			maxheight = $(this).height();
		}
	});
	$(div).height(maxheight);
}
function list_view(){
	$('.product-thumb .option').removeAttr('style');
	$('#content .product-layout > .clearfix').remove();
	$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');

	$('#list-view').addClass('active');
   $('#grid-view').removeClass('active');
   $('#grid-view4').removeClass('active');
	$('#price-view').removeClass('active');
	localStorage.setItem('display', 'list');
	localStorage.removeItem('display_old');
}
function grid_view() {
	$('#content .product-layout > .clearfix').remove();
	$('#content .row-price > .clearfix').remove();
	// What a shame bootstrap does not take into account dynamically loaded columns
	cols = $('#column-right, #column-left').length;
	if (cols == 2) {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12');
	} else if (cols == 1) {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-6 col-sm-6');
	} else {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-4 col-sm-6');
	}

	if(!$('#content .row-price').length){
		if (cols == 2) {
			$('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
		} else if (cols == 1) {
			$('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
		} else {
			$('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix visible-lg"></div>');
		}
	}

	$('#grid-view').addClass('active');
   $('#grid-view4').removeClass('active');
   $('#list-view').removeClass('active');
	$('#price-view').removeClass('active');

	localStorage.setItem('display', 'grid');
	max_height_div('.product-grid .product-thumb .option.productpage-opt');
}
function grid_view4() {
	$('#content .product-layout > .clearfix').remove();
	// What a shame bootstrap does not take into account dynamically loaded columns
	cols = $('#column-right, #column-left').length;
	if (cols == 2) {
		$('#content .product-layout').attr('class', 'product-layout product-grid grid4 col-1 col-lg-6 col-md-6 col-sm-12 col-xs-12');
	} else if (cols == 1) {
		$('#content .product-layout').attr('class', 'product-layout product-grid grid4 col-1 col-lg-3 col-md-4 col-sm-6 col-xs-12');
	} else {
		$('#content .product-layout').attr('class', 'product-layout product-grid grid4 col-lg-1-5 col-md-3 col-sm-6 col-xs-12');
	}
	if(!$('#content .row-price').length){
		if (cols == 2) {
			$('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
		} else if (cols == 1) {
			$('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix visible-lg"></div>');
		} else {
			$('#content .product-layout:nth-child(5n+5)').after('<div class="clearfix visible-lg"></div>');
		}
	}
   $('#grid-view').removeClass('active');
   $('#list-view').removeClass('active');
	$('#price-view').removeClass('active');
	$('#grid-view4').addClass('active');
	localStorage.setItem('display', 'grid4');
	max_height_div('.product-grid .product-thumb .option.productpage-opt');
}
function price_view() {
	$('#content .product-layout > .clearfix').remove();
	$('#content .product-layout').attr('class', 'product-layout product-price col-xs-12');

	localStorage.setItem('display', 'price');
	localStorage.removeItem('display_old');
	$('#price-view').addClass('active');
	$('#list-view').removeClass('active');
	$('#grid-view').removeClass('active');
	$('#grid-view4').removeClass('active');
}

$(function() {
	if (localStorage.getItem('display') == 'list') {
		list_view();
	} else if (localStorage.getItem('display') == 'grid') {
		grid_view();
	} else if (localStorage.getItem('display') == 'grid4') {
		grid_view4();
	} else if (localStorage.getItem('display') == 'price'){
		price_view();
	} else {
		grid_view();
	}
});

$(window).resize(function(){
	if (localStorage.getItem('display') != 'grid'){
		if (viewport().width < 1200) {
			localStorage.setItem('display_old', localStorage.getItem('display'));
			$('#grid-view').trigger('click');
		}
	}
	if (localStorage.getItem('display') == 'grid4' && (viewport().width < 1500)){
		$('#grid-view').trigger('click');
		localStorage.setItem('display_old', 'grid4');

	}
	if (localStorage.getItem('display_old') == 'price' && (viewport().width > 1200)){
		$('#price-view').trigger('click');
	}
	if (localStorage.getItem('display_old') == 'grid4' && (viewport().width > 1500)){
		$('#grid-view4').trigger('click');
	}
})

function loading_masked(action) {
	if (action) {
	$('.loading_masked').html(loading_masked_img);
	$('.loading_masked').show();
	} else {
	$('.loading_masked').html('');
	$('.loading_masked').hide();
	}
}

function creatOverlayLoadPage(action) {
	if (action) {
		$('body').prepend('<div id="messageLoadPage"></div>');
		$('#messageLoadPage').html(loading_masked_img);
		$('#messageLoadPage').show();
	} else {
		$('#messageLoadPage').html('');
		$('#messageLoadPage').hide();
		$('#messageLoadPage').remove();
	}
}

function popupFormReviewStore() {
	$.ajax({
		type:'get',
		url:'index.php?route=product/cyber_reviews_store/popupFormReviewStore',
		beforeSend: function() {
			creatOverlayLoadPage(true);
		},
		complete: function() {
			creatOverlayLoadPage(false);
		},
		success:function (data) {
			html  = '<div id="modal-review-store" class="modal fade" role="dialog">';
			html += '	<div class="modal-dialog">';
			html += '		<div class="modal-content">';
			html += '			<div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+ data +'</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';

			$('html body').append(html);
			$('#modal-review-store').modal('show');
		}
	});
}

function quickview_open(id,all_prod) {
	$.ajax({
		type:'post',
		data:'quickview29=1&all_prod='+all_prod,
		url:'index.php?route=product/product&product_id='+id,
		beforeSend: function() {
			creatOverlayLoadPage(true);
		},
		complete: function() {
			creatOverlayLoadPage(false);
		},

		success:function (data) {
			html  = '<div id="modal-quickview" class="modal fade" role="dialog">';
			html += '	<div class="modal-dialog modal-dialog-centered modal-qv">';
			html += '		<div class="modal-content">';
			html += '			<div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+ data +'</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';

			$('html body').append(html);
			$('#modal-quickview').modal('show');
		}
	});
}

function getNextPrevProduct(id,all_prod) {
	$.ajax({
		type:'post',
		data:'quickview29=1&all_prod='+all_prod,
		url:'index.php?route=product/product&product_id='+id,
		beforeSend: function() {
			$('#popup-quickview').append('<span class="loading_quick_order"><img src="catalog/view/theme/cyberstore/image/ring-alt-1.svg" /></span>');
		},
		complete: function() {
			$('.loading_quick_order').remove();
		},
		success:function (data) {
			$('#modal-quickview .modal-content').html('<div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+ data +'</div>');
		}
});
}

function banner_link_open(link) {
	$('#modal-desc-banner').remove();
	creatOverlayLoadPage(true);
	$.ajax({
		url: link,
		type: 'get',
		dataType: 'html',
		success: function(data) {
			creatOverlayLoadPage(false);
			var data = $(data);

			h1 = data.find('h1').text();

			description = data.find('#content .p-content').html();

			html  = '<div id="modal-desc-banner" class="modal fade" role="dialog">';
			html += '	<div class="modal-dialog modal-lg">';
			html += '		<div class="modal-content">';
			html += '			<div class="modal-header">';
			html += '				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '				<h2 class="modal-title">'+ h1 +'</h2>';
			html += '			</div>';
			html += '			<div class="modal-body">'+ description +'</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';

			$('html body').append(html);
			$('#modal-desc-banner').modal('show');
		}
	});
}


$(document).ready(function () {
	$("#back-top").hide();
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 150) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
	});

	$(document).on("click.bs.dropdown.data-api", "#cart", function (e) { e.stopPropagation() });
	setTimeout(function () {
		heightblockauto();
	}, 150);
	$(window).resize(function(){
		setTimeout(function () {
			heightblockauto();
		}, 150);
	});
	var width_doc = viewport().width;
	if (width_doc >= 992) {
	$('#phone .contact-header').hover(function() {
	  $(this).find('.drop-contacts').stop(true, true).delay(10).fadeIn(10);
	  $(this).addClass('open');
	}, function() {
	   $(this).find('.drop-contacts').stop(true, true).delay(10).fadeOut(10);
	    $(this).removeClass('open');
	});
	}
	$(document).on('click', '#phone .drop-icon-info', function () {
		 $(this).parent().parent().toggleClass('open');
	});
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#currency input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#currency').submit();
	});

	// Language
	$('#language a').on('click', function(e) {
		e.preventDefault();

		$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

		$('#language').submit();
	});

	/* Search */

	$('.btn-search').on('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		} else {
			url += '&search=';
		}

		var category_id = $('input[name=\'category_id\']').prop('value');
		if (category_id > 0) {
			url += '&category_id=' + encodeURIComponent(category_id) + '&sub_category=true';
		}
		location = url;
	});
	$('#search_word a').on('click', function() {
		$(this).parent().prev().find('.form-control.input-lg').val($(this).text());
		$(this).parent().prev().find('button.btn.btn-search').trigger('click');
	});
	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('input[name=\'search\']').parent().find('button.btn.btn-search').trigger('click');
		}
	});
	// Menu
	$('#menu-ocp .dropdown-menu').each(function() {
		var menu = $('#menu-ocp').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu-ocp').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		list_view();
		if (typeof ProStickerLoad === 'function') {
		  setTimeout ('ProStickerLoad()', 1500);
		}
	});
	// Product Grid 3
	$('#grid-view').click(function() {
		grid_view();
		if (typeof ProStickerLoad === 'function') {
		  setTimeout ('ProStickerLoad()', 1500);
		}
	});
	// Product Grid 4
	$('#grid-view4').click(function() {
		grid_view4();
		if (typeof ProStickerLoad === 'function') {
		  setTimeout ('ProStickerLoad()', 1500);
		}
	});

	$('#price-view').click(function() {
		price_view();
		if (typeof ProStickerLoad === 'function') {
		  setTimeout ('ProStickerLoad()', 1500);
		}
	});

	// tooltips on hover
	setTimeout(function () {
	$('a > img').each(function () {
		if ($( document ).width()>767) {
			if ($(this).attr('data-additional-hover')) {
				var img_src = $(this).attr('data-additional-hover');
				$(this).addClass('main-img');
				$(this).after('<img src="'+img_src+'" class="additional-img-hover img-responsive" />');
			}
		}
	});
	},3000);

	$('[data-toggle=\'tooltip\']').tooltip({container: 'body',trigger: 'hover'});
	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body',trigger: 'hover'});
	});
	if(viewport().width > 768){
		$('body').click(function(){
		  $('[data-toggle="tooltip"]').tooltip('hide');
		});
	}
});

// Cart add remove functions
var cart = {
	'add': function(product_id, element) {
		var $elem = $(element).closest('.product-thumb'),
		quantity = $elem.find('.quantity-num').val(),
		options = $elem.find('.option input[type=\'radio\']:checked, .option input[type=\'checkbox\']:checked, .option select'),
		data = 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1);

		if (options.length) {
			data += '&'+options.serialize();
		}

		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(json) {
				$('.option-danger, .add_product_alert, .alert, .text-danger').remove();

				if (json['redirect'] && !$elem.find('.option input, .option select').length) {
					location = json['redirect'];
				}
				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							var element = $('#input-option' + i.replace('_', '-'));
							if (element.parent().hasClass('input-group')) {
								element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							} else {
								element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							}
							setTimeout(function () {
							  $('.option-danger, .alert, .text-danger').remove();
							}, 7000);
							$('#top').before('<div class="alert option-danger"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/warning-icon.svg"><div class="text-modal-block">' + json['error']['option'][i] + '</div><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					}
				}

				if (json['success']) {
					if(json['popup_design']=='1'){
						fastorder_open_cart();
					} else if(json['popup_design']=='0') {
						html  = '<div id="modal-addcart" class="modal fade" role="dialog">';
						html += '  <div class="modal-dialog" style="overflow:hidden">';
						html += '    <div class="modal-content">';
						if(json['show_onepcheckout']=='1'){
						html += '      	<div class="modal-body"><div class="text-center">' + json['success'] + '<br><img style="margin:10px 0px;" src="'+ json['image_cart'] +'"  /><br></div><div><a href=' + link_onepcheckout + ' class="btn-checkout">'+ button_checkout +'</a><button data-dismiss="modal" class="btn-shopping">'+ button_shopping +'</button></div></div>';
						} else {
						html += '      	<div class="modal-body"><div class="text-center">' + json['success'] + '<br><img style="margin:10px 0px;" src="'+ json['image_cart'] +'"  /><br></div><div><a href=' + link_checkout + ' class="btn-checkout">'+ button_checkout +'</a><button data-dismiss="modal" class="btn-shopping">'+ button_shopping +'</button></div></div>';
						}
						html += '    </div>';
						html += '  </div>';
						html += '</div>';
						$('body').append(html);
						$('#modal-addcart').modal('show');
					} else {
						$('#top').before('<div class="alert add_product_alert"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"><div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					 setTimeout(function () {
						$('.option-danger, .alert, .text-danger,.add_product_alert').remove();
					  }, 7000);
					setTimeout(function () {
						$('.cart-total').html(json['total']);
					}, 100);

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
				$('#modal-addcart').on('hide.bs.modal', function (e) {
					$('#modal-addcart').remove();
				});
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('.cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout' || location.pathname == '/cart/' || location.pathname == '/checkout/') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('.cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout' || location.pathname == '/cart/' || location.pathname == '/checkout/') {
					location = 'index.php?route=checkout/cart';
				} else if (getURLVar('route') == 'checkout/onepcheckout') {
					update_checkout();
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('.cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout' || location.pathname == '/cart/' || location.pathname == '/checkout/') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}
var wishlist = {
	'add': function(product_id) {
		$('#modal-wishlist').remove();
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				html  = '<div id="modal-wishlist" class="modal fade">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content cs-modal-success">';
				html += '      <div class="modal-body"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"> <div class="text-modal-block">' + json['success'] + '</div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div>';

				$('body').append(html);

				$('#modal-wishlist').modal('show');
				if($(".wishlist-h a span").length==0) {
					$('.wishlist-h a').append('<span>'+ json['total_wishlist']+ '</span>');
				} else {
					$('.wishlist-h a span').html(json['total_wishlist']);
				}
			}

		});
	},
	'remove': function() {

	}
}
var compare = {
	'add': function(product_id) {
		$('#modal-compare').remove();
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				html  = '<div id="modal-compare" class="modal fade">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content cs-modal-success">';
				html += '      <div class="modal-body"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"> <div class="text-modal-block"> ' + json['success'] + '</div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div>';

				$('body').append(html);
				$('#modal-compare').modal('show');
				if($(".compare-h a span").length==0) {
					$('.compare-h a').append('<span>'+ json['total_compare']+ '</span>');
				} else {
					$('.compare-h a span').html(json['total_compare']);
				}

			}

		});
	},
	'remove': function() {

	}
};


// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
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
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
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

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
