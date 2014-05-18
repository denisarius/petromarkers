<?php
global $_o, $_languages, $language, $pagePath, $html_charset;

require_once pmIncludePath('design.php');
require_once pmIncludePath('_main_menu.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="{$_o['base_site_js_url']}/core.js"></script>
	</head>
	<body>
	<@gadget_top_menu>
stop;
// формат урлов:
// '/' - индекс сайта. для индексного раздела меню в БД задан урл равный '/'
// список объектов кластера: /objects/<main_menu_item_id>/<cluster_id>.html
// описание объекта: /object/<main_menu_item_id>/<object_id>.html
// /<text_id>.html - текстовый раздел
switch ($pagePath[0])
{
	case 'objects':
		echo '<@gadget_submenu><@gadget_objects>';
		break;
	case 'object':
		echo '<@gadget_submenu><@gadget_object>';
		break;
	// текстовый раздел
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
