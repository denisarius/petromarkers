<div class="submenu_container">
	<span class="submenu_title">Объекты</span>
	<ul>
		<?php
		global $_o;
		$menuItems = get_data_array_rs(
			'id, name, image',
			$_o['cms_objects_table'],
			'type=1',
			'order by id asc'
		);

		$mainMenuId = get_menu_item_id();
		$html = '';
		while ($item = $menuItems->next())
		{
			$url = "/objects/$mainMenuId/{$item['id']}.html";

			echo <<<ITEM
		<li>
			<a href="$url">
				<div class="submenu_wrap_img"><img src="{$_o['base_site_objects_images_url']}/{$item['image']}" alt=""></div>
				<span>{$item['name']}</span>
			</a>
		</li>
ITEM;
		}
		?>
	</ul>
	<br>
</div>
