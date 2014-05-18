<?php
	global $pagePath;
	if ($pagePath[0]=='pm') return;
?>
<div class="bottom_menu_line"></div>
<div class="footer_container">
	<ul>
		<?php echo MainMenu::getHtml(); ?>
	</ul>
	<a href="#" class="footer_logo"></a>
	<span
		class="footer_info">Проект финансируется Европейским Союзом, Российской Федерацией и Финляндской Республикой</span>
</div>