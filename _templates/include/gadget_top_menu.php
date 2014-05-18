<?php
	global $pagePath;
	if ($pagePath[0]=='pm') return;
?>
<div class="top_menu_container">
	<ul class="center_container top_menu">
		<?php echo MainMenu::getHtml(); ?>
	</ul>
	<div class="top_menu_line"></div>
</div>