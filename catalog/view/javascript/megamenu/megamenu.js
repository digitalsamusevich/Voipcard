$(document).ready(function() {
		$('#menu-vertical #menu-vertical-list').menuAim({
			activateCallback: activateSubmenu,
			deactivateCallback: deactivateSubmenu,
		});
		function activateSubmenu(row) {
			if($(row).hasClass('dropdown')){
				$(row).addClass('menu-open');
			}	
		}
		function deactivateSubmenu(row) {$(row).removeClass('menu-open');}
		function exitMenu(row) {return true;}
		$('.dropdown-menu-simple .nsmenu-haschild').menuAim({
			activateCallback: activateSubmenu2level,
			deactivateCallback: deactivateSubmenu2level,
		});
		function activateSubmenu2level(row) {
			if($(row).hasClass('nsmenu-issubchild')){
				$(row).addClass('menu-open-2level');
			}
		}
		function deactivateSubmenu2level(row) {$(row).removeClass('menu-open-2level');}
		function exitMenu2level(row) {return true;}	
		$('.dropdown-menu-simple .nsmenu-ischild-simple').menuAim({
			activateCallback: activateSubmenu4level,
			deactivateCallback: deactivateSubmenu4level,
		});
		function activateSubmenu4level(row) {
			
		$(row).addClass('menu-open-4level');}
		function deactivateSubmenu4level(row) {$(row).removeClass('menu-open-4level');}
		function exitMenu4level(row) {return true;}	
		
		$(".ns-dd").hover(function() {$(this).parent().find('.parent-link').toggleClass('hover');});
		$(".child-box").hover(function() {$(this).parent().find('.with-child').toggleClass('hover');});
		$(".nsmenu-ischild.nsmenu-ischild-simple").hover(function() {$(this).parent().find('> a').toggleClass('hover');});
		$(".child_4level_simple").hover(function() {$(this).parent().find('> a').toggleClass('hover');});
		
		
		$('#menu-vertical #menu-vertical-list .toggle-child').on('click', function(e) {
			e.stopPropagation();
			$(this).toggleClass('open');
			$(this).next().next().slideToggle(0);
		});
		
		$('#additional-menu li.dropdown').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(10);
			$(this).addClass('open');
			$(this).find('.dropdown-toggle').attr('aria-expanded', 'true');
		}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(10);
			$(this).removeClass('open');
			$(this).find('.dropdown-toggle').attr('aria-expanded', 'false')
		});
		
	// Menu
	$('#menu-vertical .dropdown-menu').each(function() {
		var menu = $('#menu-vertical').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu-vertical').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});
//MENU 2
nsmenu_menu();
additional_menu();
$( window ).resize(function() {
	nsmenu_menu();
	additional_menu();
});
$( "#additional-menu a.dropdown-toggle" ).bind( "click", function() {
	if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767))
	{
	window.document.location=$(this).attr('href');
	}
});
$( "#horizontal-menu a.dropdown-toggle" ).bind( "click", function() {
	if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767))
	{
	window.document.location=$(this).attr('href');
	}
});
$( "#menu-vertical a.dropdown-toggle" ).bind( "click", function() {
	if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767))
	{
	window.document.location=$(this).attr('href');
	}
});

function additional_menu(){
	$(".nsmenu-bigblock-additional").css('width',$("#additional-menu").outerWidth()-2);
	$('#additional-menu .dropdown-menu').each(function() {
		var menu = $('#additional-menu').offset();
		var dropdown = $(this).parent().offset();		
		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#additional-menu').outerWidth());
		
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 1) + 'px');
		}		
		var l=$(this).outerWidth()-2;			
		$(this).find(".nsmenu-ischild-simple").css('left',l);
		
	});
}
function nsmenu_menu(){
	$(".nsmenu-bigblock").css('width',$("#horizontal-menu .navbar-collapse").outerWidth()-2);
	$('#horizontal-menu .dropdown-menu').each(function() {
		var menu = $('#horizontal-menu .navbar-collapse').offset();
		var dropdown = $(this).parent().offset();		
		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#horizontal-menu .navbar-collapse').outerWidth());
		
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 1) + 'px');
		}		
		var l=$(this).outerWidth()-2;			
		$(this).find(".nsmenu-ischild-simple").css('left',l);
		
	});
}	
});


