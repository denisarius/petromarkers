<?php
// -----------------------------------------------------------------------------
// Обработка пути к pmEngine относительно корня сервера
// $ph - путь от корня сервера
$ph='/pmEngine';
if (($p=mb_strrpos(__FILE__ , '/'))===false) $p=mb_strrpos(__FILE__ , '\\');
$pmPath=mb_substr(__FILE__, 0, $p).$ph;
$pmRootPath=mb_substr(__FILE__, 0, $p).'/';
// -----------------------------------------------------------------------------
$_o['cms_simple']=$_cms_simple=(int)true;

require_once "{$_SERVER['DOCUMENT_ROOT']}/_config_db.php";

$_o['html_charset']=$html_charset='windows-1251';

$_o['site_domain']=$_site_domain='petromarkers.ru';
$_o['site_mail_domain']=$_site_mail_domain=$_o['site_domain'];
$_o['site_name']=$_site_name=array(
	'ru'=>'Petromarkers'
);
$_o['site_mail_admin']=$_site_mail_admin="info@{$_o['site_domain']}";
//$_site_mail_admin="mox33@mail.ru";
$_o['site_mail_admin_name']=$_site_mail_admin_name=array(
	'ru'=>"Администрация сайта '{$_o['site_name']['ru']}'",
);

$_o['scripts_libs_url']=$_scripts_libs_url='http://www.critical.ru/libs/js';

$_o['base_site_root_path']=$_base_site_root_path=$_SERVER['DOCUMENT_ROOT'];
$_o['base_site_proc_path']=$_base_site_proc_path="{$_o['base_site_root_path']}/proc";
$_o['base_site_content_path']=$_base_site_content_path="{$_o['base_site_root_path']}/data/content";
$_o['base_site_content_images_path']=$_base_site_content_images_path="{$_o['base_site_root_path']}/data/content/images";
$_o['base_site_structured_text_images_path']=$_base_site_structured_text_images_path="{$_o['base_site_root_path']}/data/text_parts";
$_o['base_site_uploader_path']=$_base_site_uploader_path="{$_o['base_site_root_path']}/uploader/uploads";

$_o['base_site_root_url']=$_base_site_root_url='';
$_o['base_site_js_url']=$_base_site_js_url="{$_o['base_site_root_url']}/js";
$_o['base_site_css_url']=$_base_site_css_url="{$_o['base_site_root_url']}/css";
$_o['base_site_images_url']=$_base_site_images_url="{$_o['base_site_root_url']}/images";
$_o['base_site_content_url']=$_base_site_content_url="{$_o['base_site_root_url']}/data/content";
$_o['base_site_content_images_url']=$_base_site_content_images_url="{$_o['base_site_root_url']}/data/content/images";
$_o['base_site_structured_text_images_url']=$_base_site_structured_text_images_url="{$_o['base_site_root_url']}/data/text_parts";
$_o['site_main_url']=$_site_main_url="http://{$_SERVER['SERVER_NAME']}/{$_o['base_site_root_url']}";

$confs = glob("{$_o['base_site_root_path']}/configs/config_*.php", GLOB_NOSORT|GLOB_NOESCAPE);
foreach($confs as $conf)
	require_once $conf;

$_o['cms_menus_table']=$_cms_menus_table='menus';
$_o['cms_menus_items_table']=$_cms_menus_items_table='menus_items';
$_o['cms_texts_table']=$_cms_texts_table='texts';
$_o['cms_constants_table']=$_cms_constants_table='constants';
$_o['cms_text_parts']=$_cms_text_parts='text_parts';
$_o['cms_directories']=$_cms_directories='directories';
$_o['cms_directories_data']=$_cms_directories_data='directories_data';

$_o['cms_texts_url_prefix']=$_cms_texts_url_prefix='content';

// ID для различных меню
$_o['main_menu_id']=$_main_menu_id=1;   // Необходимо для simple_mode
// Описание языков сайта
$_languages=array(
	array('id'=>'ru',
		'title'=>'Рус',
		'admin_title'=>'Русский',

	),
);

?>
