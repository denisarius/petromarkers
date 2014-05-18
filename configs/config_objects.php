<?php
$_o['cms_objects_table']=$_cms_objects_table='objects';
$_o['cms_objects_details']=$_cms_objects_details='objects_details';

$_o['cms_objects_image_sx']=$_cms_objects_image_sx=800;
$_o['cms_objects_image_sy']=$_cms_objects_image_sy=600;

$_o['base_site_objects_images_path']=$_base_site_objects_images_path="{$_o['base_site_root_path']}/data/objects";
$_o['base_site_objects_images_url']=$_base_site_objects_images_url="{$_o['base_site_root_url']}/data/objects";

// ��������� ��������
// id		- ���������� id ���������
// name		- �������� ���������
// type		- ��� ������ ��������� (e(enum)|d(digital)|i(image)|c(checkbox)|s(string)|oo(once from objects)|do(once from dir)|dm(multiply from dir)|ff(features))
//			ff - <dir_id>[<id>,<price_change>]..[<>,<>]| 1[1,0][4,-80]|8[5,450][7,0]
//				- e (enun) ������������. Options �������� ������ � �������������� ���������� ����������� �������� '|'. ��������: ����|����|������|���������
//				- d (digital) �����. ���������� ����� � ��������� ������� ������
//				- i (image) �����������. ��� ���� ��������. ��� ����������� ����������� � /data/object. Limit ������ ������������ ���������� ����������� ��� ������ �������
//				- text �����. ������ ���� ����� ������ � <textarea>
//				- date - ����. ����� ���������� ������ � ������� ���������
//				- c (checkbox) �������. � �� ������������ �������� 0-������, 1-��������
//				- s (string) ������
//				- oo (once from objects). ��������� ������ �� ������. �������� - id �������. Options ������ ��� �������
//				- do (once from dir). ��������� ������ �� ����������. �������� - id �����������. Options ������ ��� �����������
//				- dm (multiply from dir). ������������� ������ �� ����������. �������� - id �����������. Options ������ ��� �����������. ����� ���� ��������� ����� ������� ��������� � ����� ����� ������ �������
//				- ff (features). �������������� ������� ��������� �� ������������. Options ������ ������ ������������ ��� ������ ����������. �������� - ������ � ����: <dir_id>[<id>,<price_change>]..[<>,<>]|<dir_id>[<id>,<price_change>]..[<>,<>] ... ��������: 1[1,0][4,-80]|8[5,450][7,0]. ��������: ���������� 1 - ������� 1 - ��������� ��������� '0', ������� 4 - ��������� ��������� '-80'; ���������� 8 - ������� 5 - ��������� ��������� '450', ������� 7 - ��������� ��������� '0'
//					���� ���� 'ff' ����� ���� ������ ���� !!!
//				- st (structured text). ����������������� �����. ������ �� ���� node ������� text_parts. ���������: sx-������ ������������ ����������� sy-������ ������������ �����������
//				- tb (table). �������. columns - ������ ������� ������� (����������� '|')
// menu_item_id				- ������ ��������� �������� ��� ������� ������ ����. ID �������� ����������� ','.
// no_object_image			- ���� ���������� � true �� � �������� ����� ���� ����� ������������� ���������� �������� ����������� �������
// no_object_description	- ���� ���������� � true �� � �������� ����� ���� ����� ������������� ���������� ������ �������� �������
// options	- �������� ������ ��� ���� enum (����������� '|')
//			- ID ����������� ��� do � dm
//			- ������ ID ������������ ��� ff (����������� ',')
//			- ��� �������� ��� oo
// need		- ���� true �� ��� ������������ ��� ����� ��������
// limit	- ���������� ������ ��� �������� (�������� ���������� ����������� ��� ���� 'image')
// sx, sy	- ������ ����������� ��� ���� 'st'
// width	- ������ ���� �������������� ��������� ��� ���� 'tb'
// columns	- ������ �������� ������� (����������� '|'). ��� ���� 'tb'
// readonly - �������� �� ������������� � ������� (���� 'readoly'=>true)
// no_row_end	- ����� ����� �������� ������ �� �������������
// no_row_start	- ������ �� ���������� ��� ����� ��������
//					��� ��� ��������� ������������ ��� ����������� � ������ ���������� ���������
//					������������������ ������ ���� �����:
//						<no_row_end><no_row_start no_row_end>...<no_row_start no_row_end><no_row_start>
// input_width	- ������ ���� ����� ��� ����� 'd' � 's'
$_o['cms_objects_types']=$_cms_objects_types=array(
	array(
		'id'=>'1', 'name'=>'��������', 'menu_item_id'=>'2',
		'sx'=>800, 'sy'=>600,
		'details'=>array(
			array('id'=>'', 'name'=>'���������� (�� Yandex.maps)', 'type'=>'header', 'no_row_end'=>true),
			array('id'=>'center', 'name'=>'����� �����', 'type'=>'s', 'no_row_start'=>true, 'no_row_end'=>true, 'input_width'=>200),
			array('id'=>'object', 'name'=>'���-������', 'type'=>'s', 'no_row_start'=>true, 'input_width'=>200),
			array('id'=>'scale', 'name'=>'������� �����', 'type'=>'d'),
		)),

	array(
		'id'=>'2', 'name'=>'�������', 'menu_item_id'=>'2',
		'sx'=>800, 'sy'=>600,
		'details'=>array(
			array('id'=>'cluster', 'name'=>'�������', 'type'=>'oo', 'options' => '1'),
			array('id'=>'address', 'name'=>'�����', 'type'=>'s'),
			array('id'=>'coordinates', 'name'=>'���������� (�� Yandex.maps)', 'type'=>'s'),
		)),

);
?>