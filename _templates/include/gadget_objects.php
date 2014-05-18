<div class="content_container">
	<div class="object_container">
		<?php
		// длина фрагмента описания объекта, отображаемая в списке объектов
		define('OBJECT_BRIEF_LEN', 500);
		global $_o;
		$menuItems = get_data_array_rs(
			'SQL_CALC_FOUND_ROWS o.id, o.name, o.image, o.note',
			"{$_o['cms_objects_table']} o join {$_o['cms_objects_details']} od ON o.id=od.node ",
			"o.type=2 AND od.typeId='cluster' AND od.value=".getCurrentClusterId(),
			'order by id asc'
		);
		$itemsTotal = get_data('FOUND_ROWS()');

		$mainMenuId = get_menu_item_id();
		$html = '';
		$itemNum = 1;
		while ($item = $menuItems->next())
		{
			$info = pmGetNotice($item['note'], OBJECT_BRIEF_LEN);
			if (strlen($info) != strlen(pmGetNotice($item['note'], 100000)))
				$info .= ' ...';
			$imgUrl = $item['image'] ? "{$_o['base_site_objects_images_url']}/{$item['image']}" : '';
			$classLast = $itemNum++ == $itemsTotal ? 'last' : '';
			$detailsUrl = getObjectDetailsUrl($item['id']);

			echo <<<NODE
		<div class="node $classLast">
			<img src="$imgUrl" alt="">
			<div class="node_info">
				<span class="title">{$item['name']}</span>
				<p>$info</p>
				<input type="button" value="Подробнее" onclick="window.location.href='$detailsUrl'">
			</div>
			<br>
		</div>
NODE;
		}
		?>
	</div>
</div>

<?php
function getCurrentClusterId()
{
	global $pagePath;
	// урл корня кластеров соответствует кластеру 1
	return (int)($pagePath[2] ? $pagePath[2] : 1);
}

?>