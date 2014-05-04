<?php

class MainMenu
{

	public static function getHtml()
	{
		global $_o, $language;
		$menuItems = get_data_array_rs(
			'id, name',
			$_o['cms_menus_items_table'],
			'menu = '.$_o['main_menu_id'].' and parent=0'
		);

		$levelOneMenuId = get_menu_item_id(1);
		$html = '';
		while ($item = $menuItems->next())
		{
			$url = get_menu_url($item['id']);

			// попадаем в путь навигации?
			if ($item['id'] == $levelOneMenuId)
				$class = 'active';
			else
				$class = '';

			$html .= "<li><a href=\"$url\" class=\"$class\">{$item['name']}</a></li>";
		}

		return $html;
	}
} 