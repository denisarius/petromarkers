<div class="content_container">
	<div class="object_container">
		<?php
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
			$url = "/objects/$mainMenuId/{$item['id']}.html";
			$imgUrl = $item['image'] ? "{$_o['base_site_objects_images_url']}/{$item['image']}" : '';
			$classLast = $itemNum++ == $itemsTotal ? 'last' : '';
			$detailsUrl = getObjectDetailsUrl($item['id']);

			echo <<<NODE
		<div class="node $classLast">
			<img src="$imgUrl" alt="">
			<div class="node_info">
				<span class="title">{$item['name']}</span>
				<p>{$item['note']}</p>
				<input type="button" value="Подробнее" onclick="window.location.href='$detailsUrl'">
			</div>
			<br>
		</div>
NODE;
		}
		?>
		<!--		<div class="node">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">Собор «Александра Невского»</span>-->
		<!--				<p>Государственный совет республики Крым на сегодняшней сессии принял постановление о создании Банка Крыма, сообщает «Крыминформ». Решение было принято единогласно. Ранее президент Владимир Путин подписал закон об особенностях функционирования финансовой системы Крыма на переходный период.</p>-->
		<!--				<input type="button" value="Подробнее">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
		<!--		<div class="node">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">Краеведческие музей</span>-->
		<!--				<p>Государственный совет республики Крым на сегодняшней сессии принял постановление о создании Банка Крыма, сообщает «Крыминформ». Решение было принято единогласно. Ранее президент Владимир Путин подписал закон об особенностях функционирования финансовой системы Крыма на переходный период.</p>-->
		<!--				<input type="button" value="Подробнее">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
		<!--		<div class="node last">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">Собор «Александра Невского»</span>-->
		<!--				<p>Государственный совет республики Крым на сегодняшней сессии принял постановление о создании Банка Крыма, сообщает «Крыминформ». Решение было принято единогласно. Ранее президент Владимир Путин подписал закон об особенностях функционирования финансовой системы Крыма на переходный период.</p>-->
		<!--				<input type="button" value="Подробнее">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
	</div>
</div>

<?php
function getCurrentClusterId()
{
	global $pagePath;
	// урл корня кластеров соответствует кластеру 1
	return (int)($pagePath[2] ? $pagePath[2] : 1);
}

function getObjectDetailsUrl($objectId)
{
	return '/object/'.get_menu_item_id().'/'.$objectId.'.html';
}

?>