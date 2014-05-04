<?php
global $_o, $_languages, $language, $pagePath, $html_charset;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
		<script type="text/javascript" src="{$_o['base_site_js_url']}/core.js"></script>
	</head>
	<body>
		<div class="top_menu_container">
			<ul class="center_container top_menu">
				<li><a href="#" class="active">О проекте</a></li>
				<li><a href="#">Культурные объекты</a></li>
				<li><a href="#">Контакты</a></li>
			</ul>
		<div class="top_menu_line"></div>
		</div>
stop;
switch ($pagePath[0])
{
	// типовой макет
	default:
		echo '<@gadget_content>';
		break;
}

echo <<<stop
<@gadget_footer>
</body>
</html>
stop;
//------------------------------------------------------------------------------
?>
