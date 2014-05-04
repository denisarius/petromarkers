<?php
	$pmAdminVersion=array(1, 0, 1);

	require_once "{$_SERVER['DOCUMENT_ROOT']}/_config.php";
	if (($p=strrpos(__FILE__ , '/'))===false) $p=strrpos(__FILE__ , '\\');
	require_once substr(__FILE__, 0, $p).'/_config_db.php';
	require_once '_config_widgets.php';

$_o['admin_check_db_once']=$_admin_check_db_once=false;

$_o['admin_root_path']=$_admin_root_path="{$_o['base_site_root_path']}/admin";
$_o['admin_widgets_path']=$_admin_widgets_path="{$_o['admin_root_path']}/widgets";

$_o['admin_root_url']=$_admin_root_url="{$_o['base_site_root_url']}/admin";
$_o['admin_widgets_url']=$_admin_widgets_url="{$_o['admin_root_url']}/widgets";

$_o['admin_css_url']=$_admin_css_url="{$_o['admin_root_url']}/css";
$_o['admin_js_url']=$_admin_js_url="{$_o['admin_root_url']}/js";
$_o['admin_uploader_url']=$_admin_uploader_url="{$_o['admin_root_url']}/uploader/uploads";

$_o['admin_common_proc_path']=$_admin_common_proc_path="{$_o['base_site_root_path']}/proc";
$_o['admin_pmEngine_path']=$_admin_pmEngine_path="{$_o['base_site_root_path']}/pmEngine";
$_o['admin_proc_path']=$_admin_proc_path="{$_o['admin_root_path']}/proc";
$_o['admin_uploader_path']=$_admin_uploader_path="{$_o['admin_root_path']}/uploader/uploads";

$_o['admin_backup_path']=$_admin_backup_path="{$_SERVER['DOCUMENT_ROOT']}/_backups";
$_o['admin_backup_url']=$_admin_backup_url="/_backups";

$_o['admin_messages_signature']=$_admin_messages_signature=<<<stop
<br><br>С уважением,<br>
Администрация сайта<br>
stop;

$_o['cms_texts_admin_list_page_length']=$_cms_texts_admin_list_page_length=20;
$_o['cms_customs_admin_list_page_length']=$_cms_customs_admin_list_page_length=20;
$_o['cms_customers_admin_list_page_length']=$_cms_customers_admin_list_page_length=20;
$_o['cms_users_admin_list_page_length']=$_cms_users_admin_list_page_length=20;
$_o['cms_objects_admin_list_page_length']=$_cms_objects_admin_list_page_length=20;
$_o['cms_admin_walls_list_page_length']=$_cms_admin_walls_list_page_length=20;
$_o['cms_news_admin_list_page_length']=$_cms_news_admin_list_page_length=20;

$_o['cms_images_jpeg_quality']=$_cms_images_jpeg_quality=85;

$_o['admin_menu_selector_tree']=$_admin_menu_selector_tree=true;
?>