<div class="content_container">
	<?php
	global $_o;
	$item = get_data_array(
		'o.id, o.name, o.image, o.note',
		"{$_o['cms_objects_table']} o",
		"id = ".getCurrentObjectId()
	);
	echo <<<ITEM
	<div class="title_image"><img src="{$_o['base_site_objects_images_url']}/{$item['image']}" alt=""></div>
	<h1>{$item['name']}</h1>
	<div class="left_column">
		{$item['note']}
	</div>
	<div class="right_column">
		<div class="contacts">
			<span>�����:</span>
			<span class="bold">�. ������������,</span>
			<span class="bold" style="margin-bottom:20px;">��. ���������� ��������, �. 32</span>

			<span>�������:</span>
			<span class="bold" style="margin-bottom:20px;">8(8142)57-63-71</span>

			<span>����� ������:</span>
			<span class="bold" style="margin-bottom:20px;">���������: � 8:00 �� 20:00</span>
			<a href="#" class="map_info">���������� �� �����</a>

		</div>
	</div>
ITEM;
	?>
	<br>

	<h2>�����������</h2>
	<div class="gallery_container">
		<div class="gallery_item">
			<img src="img/pics/pic2.jpg" alt="">
		</div>
		<div class="gallery_item">
			<img src="img/pics/pic2.jpg" alt="">
		</div>
		<div class="gallery_item">
			<img src="img/pics/pic2.jpg" alt="">
		</div>
		<div class="gallery_item">
			<img src="img/pics/pic2.jpg" alt="">
		</div>
		<br>
	</div>
</div>

<?php
function getCurrentObjectId()
{
	global $pagePath;
	return (int)$pagePath[2];
}

?>