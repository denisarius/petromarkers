<?php
global $_languages, $language, $_scripts_libs_url, $pagePath, $html_charset;
global $_base_site_js_url, $_base_site_css_url;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
	</head>
	<body>
		<div class="top_menu_container">
			<ul class="center_container top_menu">
				<li><a href="#" class="active">� �������</a></li>
				<li><a href="#">���������� �������</a></li>
				<li><a href="#">��������</a></li>
			</ul>
		<div class="top_menu_line"></div>
		</div>
stop;
switch ($pagePath[0])
{
	// ������� �����
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
