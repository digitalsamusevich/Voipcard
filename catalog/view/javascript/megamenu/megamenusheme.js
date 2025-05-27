$(document).ready(function() {
		$('#menu-vsheme #menu-vsheme-list li.dropdown').hover(function() {
			$(this).addClass('menu-open');
		}, function() {
			$(this).removeClass('menu-open');
		});
		$('#menu-vsheme #menu-vsheme-list .dropdown-menu-simple li.nsmenu-issubchild').hover(function() {
			$(this).addClass('menu-open-2level');
		}, function() {
			$(this).removeClass('menu-open-2level');
		});
		$('#menu-vsheme #menu-vsheme-list .dropdown-menu-simple li.nsmenu-ischild-simple').hover(function() {
			$(this).addClass('menu-open-4level');
		}, function() {
			$(this).removeClass('menu-open-4level');
		});

		$("#menu-vsheme .ns-dd").hover(function() {$(this).parent().find('#menu-vsheme .parent-link').toggleClass('hover');});
		$("#menu-vsheme .nsmenu-ischild.nsmenu-ischild-simple").hover(function() {$(this).parent().find('#menu-vsheme > a').toggleClass('hover');});
		$("#menu-vsheme .child_4level_simple").hover(function() {$(this).parent().find('#menu-vsheme > a').toggleClass('hover');});

		// Menu
		$('#menu-vsheme .dropdown-menu-sheme').each(function() {
			var menu = $('#menu-vsheme').offset();
			var dropdown = $(this).parent().offset();

			var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu-vsheme').outerWidth());

			if (i > 0) {
				$(this).css('margin-left', '-' + (i + 5) + 'px');
			}
		});
		$( "#menu-vsheme a.dropdown-toggle" ).bind( "click", function() {
			if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767)){
				window.document.location=$(this).attr('href');
			}
		});

});


