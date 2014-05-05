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
		<script type="text/javascript" src="{$_o['base_site_js_url']}/core.js"></script>
	</head>
	<body>
	<@gadget_top_menu>
stop;
// ������ �����:
// '/' - ������ �����. ��� ���������� ������� ���� � �� ����� ��� ������ '/'
// ������ �������� ��������: /objects/<main_menu_item_id>/<cluster_id>.html
// �������� �������: /object/<main_menu_item_id>/<object_id>.html
// /<text_id>.html - ��������� ������
switch ($pagePath[0])
{
	case 'objects':
		echo '<@gadget_submenu><@gadget_objects>';
		break;
	case 'object':
		echo '<@gadget_submenu><@gadget_object>';
		break;
	// ��������� ������
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
