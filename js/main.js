// -----------------------------------------------------------------------------
$(document).ready(function(){
	$(".content_menu_submenu ul").hover(
		function() {
			$(this).parent(".content_menu_submenu").addClass("current");
		}, function() {
			$(this).parent(".content_menu_submenu").removeClass("current");
		}
	);
});
// -----------------------------------------------------------------------------
function scroll_to_top()
{
	$('body,html').animate({
		scrollTop: 0
	}, 500);
}
// -----------------------------------------------------------------------------
