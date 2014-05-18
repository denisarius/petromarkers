<?php
$_o['cms_objects_table']=$_cms_objects_table='objects';
$_o['cms_objects_details']=$_cms_objects_details='objects_details';

$_o['cms_objects_image_sx']=$_cms_objects_image_sx=800;
$_o['cms_objects_image_sy']=$_cms_objects_image_sy=600;

$_o['base_site_objects_images_path']=$_base_site_objects_images_path="{$_o['base_site_root_path']}/data/objects";
$_o['base_site_objects_images_url']=$_base_site_objects_images_url="{$_o['base_site_root_url']}/data/objects";

// Параметры Объектов
// id		- уникальный id параметра
// name		- название параметра
// type		- тип данных параметра (e(enum)|d(digital)|i(image)|c(checkbox)|s(string)|oo(once from objects)|do(once from dir)|dm(multiply from dir)|ff(features))
//			ff - <dir_id>[<id>,<price_change>]..[<>,<>]| 1[1,0][4,-80]|8[5,450][7,0]
//				- e (enun) перечисление. Options содержит строку с перечисленными вариантами разделенным символом '|'. Например: стол|стул|кресло|табуретка
//				- d (digital) число. Десятичное число с возможной дробной частью
//				- i (image) изображение. Имя файа картинки. Фсе изображения содержаться в /data/object. Limit задает максимальное количество изображений для одного объекта
//				- text текст. Просто блок плайн текста с <textarea>
//				- date - дата. Выбор просиходит только с помощью календаря
//				- c (checkbox) чекбокс. В БД записываются значения 0-выбран, 1-невыбран
//				- s (string) строка
//				- oo (once from objects). Единичная ссылка на объект. Значение - id объекта. Options задает тип объекта
//				- do (once from dir). Единичная ссылка на справочник. Значение - id справочника. Options задает тип справочника
//				- dm (multiply from dir). Множественная ссылка на справочник. Значение - id справочника. Options задает тип справочника. Может быть несколько таких записей связанных с одним полем одного объекта
//				- ff (features). Характеристика объекта связанная со справочником. Options задает список справочников для выбора параметров. Знаяение - строка в виде: <dir_id>[<id>,<price_change>]..[<>,<>]|<dir_id>[<id>,<price_change>]..[<>,<>] ... Например: 1[1,0][4,-80]|8[5,450][7,0]. Означает: справочник 1 - элемент 1 - изменение параметра '0', элемент 4 - изменение параметра '-80'; справочник 8 - элемент 5 - изменение параметра '450', элемент 7 - изменение параметра '0'
//					поле типа 'ff' может быть только одно !!!
//				- st (structured text). Структурированный текст. Ссылка на поле node таблицы text_parts. Параметры: sx-ширина привызанного изображения sy-высота привязанного изображения
//				- tb (table). Таблица. columns - список колонок таблицы (разделитель '|')
// menu_item_id				- список возможных разделов для объекта даннго типа. ID разделов разделяются ','.
// no_object_image			- если определено и true то у объектов этого типа будет отсутствовать возможнось загрузки изображений объекта
// no_object_description	- если определено и true то у объектов этого типа будет отсутствовать возможнось задать описание объекта
// options	- варианты выбора для типа enum (разделитель '|')
//			- ID справочника для do и dm
//			- список ID справочников для ff (разделитель ',')
//			- тип объектов для oo
// need		- если true то это обязательный для ввода параметр
// limit	- количество данных для признака (например количество изображений для типа 'image')
// sx, sy	- размер изображения для типа 'st'
// width	- размер окна редактирования параметра для типа 'tb'
// columns	- список столбцов таблицы (разделитель '|'). Для типа 'tb'
// readonly - свойство не редактируется в админке (если 'readoly'=>true)
// no_row_end	- после этого контрола строка не заканчивается
// no_row_start	- строка не начинается для этого контрола
//					эти два параметра используются для отображения в строке нескольких контролов
//					последовательность должна быть такая:
//						<no_row_end><no_row_start no_row_end>...<no_row_start no_row_end><no_row_start>
// input_width	- ширина поля ввода для типов 'd' и 's'
$_o['cms_objects_types']=$_cms_objects_types=array(
	array(
		'id'=>'1', 'name'=>'Кластеры', 'menu_item_id'=>'2',
		'sx'=>800, 'sy'=>600,
		'details'=>array(
			array('id'=>'', 'name'=>'Координаты (из Yandex.maps)', 'type'=>'header', 'no_row_end'=>true),
			array('id'=>'center', 'name'=>'Центр карты', 'type'=>'s', 'no_row_start'=>true, 'no_row_end'=>true, 'input_width'=>200),
			array('id'=>'object', 'name'=>'Арт-объект', 'type'=>'s', 'no_row_start'=>true, 'input_width'=>200),
			array('id'=>'scale', 'name'=>'Масштаб карты', 'type'=>'d'),
		)),

	array(
		'id'=>'2', 'name'=>'Объекты', 'menu_item_id'=>'2',
		'sx'=>800, 'sy'=>600,
		'details'=>array(
			array('id'=>'cluster', 'name'=>'Кластер', 'type'=>'oo', 'options' => '1'),
			array('id'=>'address', 'name'=>'Адрес', 'type'=>'s'),
			array('id'=>'coordinates', 'name'=>'Координаты (из Yandex.maps)', 'type'=>'s'),
		)),

);
?>