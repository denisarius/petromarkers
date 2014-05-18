<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
<?php
	global $_o, $language, $pagePath;

	if (!isset($pagePath[1])) $cluster_id=-1;
	else $cluster_id=(int)$pagePath[1];

	$cluster=get_data_array('*', $_o['cms_objects_table'], "type=1 and id='$cluster_id'");
	if ($cluster===false)
	{
		set_page_title('%%cluster_not_found_page_title%%');
		echo <<<stop
<h2>%%cluster_not_found%%</h2>
stop;
		return;
	}

	$q=<<<stop
select obj.*
from {$_o['cms_objects_table']} as obj
left join {$_o['cms_objects_details']} as d0
	on d0.node=obj.id and
	d0.typeId='cluster'
where
	obj.type=2 and
	d0.value='$cluster_id' and
	visible=1
stop;
	$res=query($q);

	$center=cms_get_objects_details(1, $cluster['id'], 'center');
	$center=str2coordinates($center);
	$art_object=cms_get_objects_details(1, $cluster['id'], 'object');
	$art_object=str2coordinates($art_object);
	$scale=cms_get_objects_details(1, $cluster['id'], 'scale');

	$map=objects_get_map_description($res);
	echo <<<stop
<div id="map"></div>
<script type="text/javascript">
$(document).ready(function(){
	w=$(window).width();
	h=$(window).height();
	$("#map").width(w).height(h);
	ymaps.ready(mapInit);
});
var map;
function mapInit()
{
	map_el=$("#map")
	w=map_el.width();
	h=map_el.height();
	dw=w/690;
	dh=h/450;
	if (dw>dh) d=dh;
	else d=dw;
	scale=Math.log(Math.ceil( Math.pow(2, $scale) * d)) / Math.LN2;
	if(scale>18) scale=18;
	map = new ymaps.Map ("map", {
		center: [{$center[0]}, {$center[1]}],
		zoom: scale,
		{$map['options']}
	});
	map.controls.add( new ymaps.control.ZoomControl() );
//	map.controls.add('typeSelector');
	{$map['objects_scripts']}
	{$map['post_script']}

	obj = new ymaps.Placemark([{$art_object[0]}, {$art_object[1]}], { balloonContentHeader: 'Вы здесь'});
	map.geoObjects.add(obj);
}
</script>
stop;
	mysql_free_result($res);


// ---------------------------------------------------------------------------------------------------------------------
function objects_get_map_description($res)
{
	$frame=array('options'=>'', 'objects_scripts'=>'');
	while($r=mysql_fetch_assoc($res))
	{
		$coords=cms_get_objects_details(2, $r['id'], 'coordinates');
		$coords=str2coordinates($coords);
		$address=cms_get_objects_details(2, $r['id'], 'address');
		if ($address!='') $address="<b>Адрес:</b> $address<br>";
		$url=getObjectDetailsUrl($r['id']);
		$frame['objects_scripts'].=<<<stop
obj = new ymaps.Placemark([{$coords[0]}, {$coords[1]}], { balloonContentHeader: '<a href="$url">{$r['name']}</a>', balloonContentBody: '{$address}' });
map.geoObjects.add(obj);
stop;
	}
	return $frame;
}
// ---------------------------------------------------------------------------------------------------------------------
function str2coordinates($coords)
{
	$coords=explode(',', $coords);
	if ($coords[0]<$coords[1]) list($coords[0], $coords[1]) = array($coords[1], $coords[0]);
	return $coords;
}
// ---------------------------------------------------------------------------------------------------------------------
?>