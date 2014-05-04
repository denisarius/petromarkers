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
				<input type="button" value="���������" onclick="window.location.href='$detailsUrl'">
			</div>
			<br>
		</div>
NODE;
		}
		?>
		<!--		<div class="node">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">����� ����������� ��������</span>-->
		<!--				<p>��������������� ����� ���������� ���� �� ����������� ������ ������ ������������� � �������� ����� �����, �������� �����������. ������� ���� ������� �����������. ����� ��������� �������� ����� �������� ����� �� ������������ ���������������� ���������� ������� ����� �� ���������� ������.</p>-->
		<!--				<input type="button" value="���������">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
		<!--		<div class="node">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">������������� �����</span>-->
		<!--				<p>��������������� ����� ���������� ���� �� ����������� ������ ������ ������������� � �������� ����� �����, �������� �����������. ������� ���� ������� �����������. ����� ��������� �������� ����� �������� ����� �� ������������ ���������������� ���������� ������� ����� �� ���������� ������.</p>-->
		<!--				<input type="button" value="���������">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
		<!--		<div class="node last">-->
		<!--			<img src="img/pics/pic1.jpg" alt="">-->
		<!--			<div class="node_info">-->
		<!--				<span class="title">����� ����������� ��������</span>-->
		<!--				<p>��������������� ����� ���������� ���� �� ����������� ������ ������ ������������� � �������� ����� �����, �������� �����������. ������� ���� ������� �����������. ����� ��������� �������� ����� �������� ����� �� ������������ ���������������� ���������� ������� ����� �� ���������� ������.</p>-->
		<!--				<input type="button" value="���������">-->
		<!--			</div>-->
		<!--			<br>-->
		<!--		</div>-->
	</div>
</div>

<?php
function getCurrentClusterId()
{
	global $pagePath;
	// ��� ����� ��������� ������������� �������� 1
	return (int)($pagePath[2] ? $pagePath[2] : 1);
}

function getObjectDetailsUrl($objectId)
{
	return '/object/'.get_menu_item_id().'/'.$objectId.'.html';
}

?>