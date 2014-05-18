<div class="content_container">
	<?php
	global $_o;
	$item = get_data_array(
		'id, type, name, image, note',
		"{$_o['cms_objects_table']}",
		"id = ".getCurrentObjectId()
	);
	$address = cms_get_objects_details($item['type'], $item['id'], 'address');
	echo <<<ITEM
	<div class="title_image"><img src="{$_o['base_site_objects_images_url']}/{$item['image']}" alt=""></div>
	<h1>{$item['name']}</h1>
	<div class="left_column">
		{$item['note']}
	</div>
	<div class="right_column">
		<div class="contacts">
		<span>Адрес:</span>
			$address
		</div>
	</div>
ITEM;
	?>
	<br>

	<!--	<h2>Фотогалерея</h2>-->
	<!--	<div class="gallery_container">-->
	<!--		<div class="gallery_item">-->
	<!--			<img src="img/pics/pic2.jpg" alt="">-->
	<!--		</div>-->
	<!--		<div class="gallery_item">-->
	<!--			<img src="img/pics/pic2.jpg" alt="">-->
	<!--		</div>-->
	<!--		<div class="gallery_item">-->
	<!--			<img src="img/pics/pic2.jpg" alt="">-->
	<!--		</div>-->
	<!--		<div class="gallery_item">-->
	<!--			<img src="img/pics/pic2.jpg" alt="">-->
	<!--		</div>-->
	<!--		<br>-->
	<!--	</div>-->
</div>

<?php
function getCurrentObjectId()
{
	global $pagePath;
	return (int)$pagePath[2];
}

?>