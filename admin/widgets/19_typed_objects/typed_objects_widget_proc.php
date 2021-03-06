<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once "$path/_config.php";
	require_once "$_admin_widgets_path/common_widget_proc.php";

// -----------------------------------------------------------------------------
function typed_objects_get_detail_type($type_id, $obj_type)
{
	$types=typed_objects_get_object_description($obj_type);
	$types=$types['details'];
	foreach($types as $type)
		if ($type['id']==$type_id) return $type['type'];
	return '';
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_detail($obj_type, $prop_id)
{
	$object_description=typed_objects_get_object_description($obj_type);
	if ($object_description===false) return false;
	$prop=false;
	foreach($object_description['details'] as $d)
		if ($d['id']==$prop_id) $prop=$d;
	return $prop;
}
//------------------------------------------------------------------------------
function typed_objects_get_object_html($object, $description)
{
	global $_base_site_objects_images_url;

	$note=str_replace('</p>', '<br>', $object['note']);
	$note=pmGetNotice(strip_tags($note, '<br>'), 350);
	if ($object['visible']==1) $ch=' checked="checked"';
	else $ch='';

	if ($object['image']!='')
	{
		$img="$_base_site_objects_images_url/{$object['image']}";
		$width=150;
	}
	else
	{
		$img="images/no_image_64.gif";
		$width=64;
	}
	if (isset($description['no_object_image']) && $description['no_object_image']==true) $image='';
	else $image="<div class='typed_objects_object_node_image'><img src='$img' style='width:{$width}px;'/></div>";

	if (widget_exists('gallery')) $gallery_html=<<<stop
<input type="button" class="admin_tool_button" value="����������� �������" onClick="typed_objects_go_gallery({$object['id']})">
stop;
	else $gallery_html='';


	$obj_name_js=str2js($object['name']);
	$html=<<<stop
<div class="typed_objects_object_node" id="typed_objects_list_node_{$object['id']}">
<div class="typed_objects_object_node_title" onClick="typed_objects_object_edit({$object['id']})">{$object['name']} [ID={$object['id']}]</div>
$image
<div class="typed_objects_object_node_note" >$note</div>
<br>
<input type="checkbox" id="typed_object_visible_{$object['id']}" onClick="typed_objects_toggle_visible({$object['id']})" $ch> ���������� �� �����
<hr>
$gallery_html
<input type="button" class="admin_tool_button" style="margin-left: 20px;" value="�����������/����������� ������" onClick="typed_objects_object_move_start({$object['id']})">
<input type="button" class="admin_tool_button" style="float:right" value="������� ������" onClick="typed_objects_delete_object({$object['id']}, '$obj_name_js')">
<br>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_get_objects_list($menu_item, $obj_type, $page)
{
	global $_cms_objects_types, $_cms_objects_table, $_cms_objects_admin_list_page_length;

	$html='';
    if ($obj_type=='')
	{
		// �� ����� ��� ��������
		if (count($_cms_objects_types)>1)
		{
			// ���������� ������������ �������� ������ ������
			$html.=<<<stop
<div class="typed_objects_object_type_selector">
��� ��������:
<select id="typed_objects_object_type">
<option value="-1"></option>
stop;
			$ot=get_data_array('type, count(*) as cnt', $_cms_objects_table, "menu_item='$menu_item' group by type order by cnt desc limit 1");
			$ot=$ot['type'];
			foreach($_cms_objects_types as $type)
			{
		        if ($type['id']==$ot) $sl='selected="selected"';
				else $sl='';
				$html.=<<<stop
<option value="{$type['id']}" $sl>{$type['name']}</option>
stop;
			}
			$html.='</select></div>';
		}
		else	// ��������� ������ ���� ��� ��������
			$html.="<input type='hidden' id='typed_objects_object_type' value='{$_cms_objects_types[0]['id']}'>";
	}
	else	// ����� ������������� ��� ��������
		$html.="<input type='hidden' id='typed_objects_object_type' value='$obj_type'>";
	$html.=<<<stop
<br><input type="button" value="�������� ������" id="typed_objects_object_add" onClick="typed_objects_object_add()">
<input type="button" value="���������� ��������" id="typed_objects_object_sort" onClick="typed_objects_object_sort()" style="float:right;"><br>
stop;
	$start=$page*$_cms_objects_admin_list_page_length;
	$res=query("select SQL_CALC_FOUND_ROWS * from $_cms_objects_table where menu_item='$menu_item' and type='$obj_type' order by sort, id desc limit $start, $_cms_objects_admin_list_page_length");
	$total=get_data('FOUND_ROWS()');
	$html.=get_admin_pager($total, $page, $_cms_objects_admin_list_page_length, 'typed_objects_show_objects_list_page');
	while ($r=mysql_fetch_assoc($res))
	{
		$description=typed_objects_get_object_description($r['type']);
		$html.=typed_objects_get_object_html($r, $description);
	}
	$html.=get_admin_pager($total, $page, $_cms_objects_admin_list_page_length, 'typed_objects_show_objects_list_page');
	return array('html'=>$html, 'page'=>$page);
}
// -----------------------------------------------------------------------------
function typed_objects_get_edit_object_html($id, $type)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $_base_site_structured_text_images_path;
	global $_base_site_objects_images_path, $_base_site_objects_images_url, $_admin_root_url;
    global $_cms_objects_image_sx, $_cms_objects_image_sy;

	if ($id==-1)
	{
		$obj_type=$type;
		$obj_name='';
        $obj_note='';
		$obj_img='';
		$obj_img_src="$_admin_root_url/images/no_image_256.gif";
		$prop_html=typed_objects_get_object_propertis_edit_html($id, $type);
	}
	else
	{
		$obj=get_data_array('*', $_cms_objects_table, "id='$id'");
		$obj_type=$obj['type'];
		$obj_name=$obj['name'];
		$obj_note=$obj['note'];
		$obj_img=$obj['image'];
		if ($obj_img!='') $obj_img_src="$_base_site_objects_images_url/$obj_img";
		else $obj_img_src="$_admin_root_url/images/no_image_256.gif";
		$prop_html=typed_objects_get_object_propertis_edit_html($id, $obj['type']);
		copy_image_to_temp("$_base_site_objects_images_path/$obj_img");
// ��������� ���� ���������� ���� "����������������� ����"
// �������� ����������� ��������� � ������ � temp
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
					copy_image_to_temp("$_base_site_structured_text_images_path/{$r['image']}");
			}
	}
	$object_description=typed_objects_get_object_description($obj_type);
	if (isset($object_description['sx']) && $object_description['sx']!='') $sx=$object_description['sx'];
	else $sx=$_cms_objects_image_sx;
	if (isset($object_description['sy']) && $object_description['sy']!='') $sy=$object_description['sy'];
	else $sy=$_cms_objects_image_sy;
	$html=<<<stop
<input type="hidden" id="typed_objects_edit_object_id" value="$id">
<input type="hidden" id="typed_objects_edit_object_sx" value="$sx">
<input type="hidden" id="typed_objects_edit_object_sy" value="$sy">
stop;

	if (isset($object_description['no_object_image']) && $object_description['no_object_image']==true)
		$html.='<input type="hidden" id="typed_objects_object_image" value="">';
    if (isset($object_description['no_object_description']) && $object_description['no_object_description']==true)
		$html.='<input type="hidden" id="typed_object_description" value="">';

	$html.=<<<stop
<table width='99%'>
<tr><td valign='top' width='120' class='typed_objects_edit_prop_need_title'>�������� �������</td>
<td><input type='text' id='typed_object_name' style='width:100%;' value='$obj_name'></td></tr>
stop;

    if (!isset($object_description['no_object_image']) || $object_description['no_object_image']!=true)
		$html.=<<<stop
<tr><td valign='top'>�����������</td>
<td>
<input type="hidden" id="typed_objects_object_image" value="$obj_img">
<div class="typed_objects_edit_image_container">
<img id="typed_object_image_img" src="$obj_img_src" />
</div>
<div>
<div style=" float:left">
<div style="margin-bottom: 10px;"><input type="button" value="��������� �����������" onClick="typed_objects_object_edit_load_image()"></div>
<div><input type="button" value="������� �����������" onClick="typed_objects_object_edit_delete_image()"></div>
</div>
</div>
</td></tr>
stop;

    if (!isset($object_description['no_object_description']) || $object_description['no_object_description']!=true)
		$html.=<<<stop
<tr><td valign='top'>�������� �������</td>
<td class="typed_object_description_container">
	<textarea id='typed_object_description' style='width:100%;' row='5'>$obj_note</textarea>
	<div class='typed_objects_text_edit_tool_panel' id='typed_objects_text_edit_tool_panel'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='�������� ������ �� ��������' onClick='texts_edit_insert_document_link("texts_link_text_document_select")'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='�������� ���������' onClick='texts_edit_insert_constant("texts_constant_select")'>
		<input type='button' class='admin_tool_button objects_edit_tool_button' value='�������� ���' onClick="texts_edit_edit_pseudotag()">
	</div>
</td></tr>
stop;

	$html.=<<<stop
$prop_html
</table>
<hr>
<input type='button' value='��������� ������' style='margin-right:10px;' onClick='typed_objects_object_edit_save()'>
<input type='button' value='��������' onClick='typed_objects_object_edit_cancel()'>
stop;

    if (!isset($object_description['no_object_description']) || $object_description['no_object_description']!=true)
		$html.=<<<stop
<script type="text/javascript">
    CKEDITOR.config.height= '200px';
	CKEDITOR.config.format_tags = 'p';
	CKEDITOR.config.baseFloatZIndex=100100;
	CKEDITOR.config.forcePasteAsPlainText = true;
	CKEDITOR.config.toolbarCanCollapse = false;
	if (text_editor) CKEDITOR.remove(text_editor);
	text_editor=CKEDITOR.replace('typed_object_description');
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_description($type)
{
	global $_cms_objects_types;
	foreach($_cms_objects_types as $desc)
		if (strtolower($desc['id'])==strtolower($type)) return $desc;
	return false;
}
// -----------------------------------------------------------------------------
function typed_objects_get_object_propertis_edit_html($id, $type)
{
	global $html_charset, $_cms_objects_table, $_cms_objects_details, $_admin_root_url, $_cms_directories_data;

    $html='';
    $object_description=typed_objects_get_object_description($type);
	if ($object_description===false) return '';
	foreach($object_description['details'] as $obj_prop)
	{
        if(isset($obj_prop['noshow']) && $obj_prop['noshow']) continue;
		$obj_prop['type']=strtolower($obj_prop['type']);

		$need='';  $name_style='';
		if ($obj_prop['need'])
		{
			$need="data-need='true'";
			$name_style="class='typed_objects_edit_prop_need_title'";
		}

		if (!isset($obj_prop['no_row_start']) || $obj_prop['no_row_start']!==true)
		{
			if ($obj_prop['type']=='c')
				$html.="<tr><td valign='bottom' $name_style>{$obj_prop['name']}</td><td>";
			elseif ($obj_prop['type']=='t')
				$html.="<tr><td valign='top' $name_style>{$obj_prop['name']}</td><td>";
			else
				$html.="<tr><td valign='center' $name_style>{$obj_prop['name']}</td><td valign='center'>";
		}
		else
        	$html.="<span class='typed_objects_inline_prop_name'>{$obj_prop['name']}</span>";

		if (isset($obj_prop['input_width']) && $obj_prop['input_width']!='') $input_width="{$obj_prop['input_width']}px";
		else $input_width='100%';

        switch($obj_prop['type'])
		{
			case 'header':
				break;
			case 's':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if (!isset($obj_prop['readonly']) || !$obj_prop['readonly'])
					$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='text' style='width:{$input_width};' value='$v' $need>";
				else
					$html.="<span>$v</span><input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='hidden' value='$v'>";
				break;
			case 'd':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if (!isset($obj_prop['readonly']) || !$obj_prop['readonly'])
					$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='text' style='width:{$input_width};' value='$v' data-type='d' $need>";
				else
					$html.="<span>$v</span><input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='hidden' value='$v' data-type='d'>";
				break;
			case 'date':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if ($v!='') $v=mysql2date($v);
				if (!isset($obj_prop['readonly']) || !$obj_prop['readonly'])
					$html.=<<<stop
<input type="text" class="typed_objects_edit_prop_type_date" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" data-type="date" readonly="readonly" value="$v" $need />
<script type="text/javascript">
	$("#prop_{$obj_prop['id']}").datepicker({
		autoSize: true,
		dateFormat: "dd-mm-yy",
		onSelect: function(dateText, inst)
			{
				$("#datepicker").css("display", "none");
			}
	});
</script>
stop;
				else
					$html.="<span>$v</span><input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='hidden' value='$v' data-type='d'>";
				break;
			case 'text':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<textarea id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' style='width:100%;' $need>$v</textarea>";
				break;
			case 'html':
				$v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
                $name=pmAntiXSSVar($obj_prop['name'], $html_charset);
				$vv=htmlspecialchars($v, ENT_QUOTES, $html_charset);
				$html.=<<<stop
<input type="hidden" id="name_{$obj_prop['id']}" value="$name"/>
<input type="hidden" name="{$obj_prop['id']}" id="prop_{$obj_prop['id']}" value="$vv"/>
<div class="typed_objects_html_prop" id="propt_{$obj_prop['id']}" onClick="typed_objects_html_edit('{$obj_prop['id']}')">$v</div>
stop;
				break;
			case 'e':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<select id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' size='1' $need>";
				$opt=explode('|', $obj_prop['options']);
				sort($opt);
				if ($v=='') $sl=" selected='selected'";
				$html.="<option value=''$sl></option>";
				foreach($opt as $o)
				{
					$sl='';
					if ($o==$v) $sl=" selected='selected'";
					$html.="<option value='{$o}'$sl>$o</option>";
				}
				$html.="</select>";
				break;
			case 'c':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$ch='';
				if ($v==1) $ch='checked';
				$html.="<input id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' type='checkbox' class='prop_img_checkbox' value='1' $ch>";
				break;
			case 'do':
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<select id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' size='1' $need>";
				$res=query("select * from $_cms_directories_data where dir='{$obj_prop['options']}'");
				if ($v=='') $sl=" selected='selected'";
				$html.="<option value=''$sl></option>";
                while($r=mysql_fetch_assoc($res))
				{
					$sl='';
					if ($r['id']==$v) $sl="selected=' selected'";
					$html.="<option value='{$r['id']}'$sl>{$r['content']}</option>";
				}
				mysql_free_result($res);
				$html.=<<<stop
</select>
<input type="button" value="+" class="admin_tool_button typed_objects_dir_add_button" onClick="typed_objects_prop_edit_dir_add('{$obj_prop['options']}', {$obj_prop['id']})" />
stop;
				break;
			case 'oo':	// ��������� �������� �� ��������
                $v=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$html.="<select id='prop_{$obj_prop['id']}' name='{$obj_prop['id']}' size='1' $need>";
				$res=query("select * from $_cms_objects_table where type='{$obj_prop['options']}' order by name");
				if ($v=='') $sl=" selected='selected'";
				$html.="<option value=''$sl></option>";
                while($r=mysql_fetch_assoc($res))
				{
					$sl='';
					if ($r['id']==$v) $sl=" selected='selected'";
					$html.="<option value='{$r['id']}'$sl>{$r['name']}</option>";
				}
				mysql_free_result($res);
				$html.="</select>";
				break;
			case 'dm':
                $resV=query("select value from $_cms_objects_details where node='$id' and typeId='{$obj_prop['id']}'");
				$val=array();
				$hval='';
				while($r=mysql_fetch_assoc($resV))
				{
					array_push($val, $r['value']);
					$hval.="{$r['value']}|";
				}
				$hval=substr($hval, 0, -1);
				mysql_free_result($resV);
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval"><div class="typed_objects_property_dir_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_dir_select($type, '{$obj_prop['id']}')">
stop;
				if (count($val))
				{
					foreach($val as $v)
					{
						$name=get_data('content', $_cms_directories_data, "id='$v'");
						$html.="$name, ";
					}
					$html=substr($html, 0, -2);
				}
				else
					$html.='�������� ��������';
				$html.='</div>';
				break;
			case 'st':	// structured text
				global $_cms_text_parts;
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$cnt=get_data('count(*)', $_cms_text_parts, "node='$prop_id' and type=1");
                $hval=typed_objects_get_structured_text_html_value($id, $obj_prop['id']);
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval">
<div class="typed_objects_property_structured_text_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_structured_text('$id', '{$obj_prop['id']}', '$type')">
stop;
				if ($cnt) $html.="���������� ����������: $cnt ";
				$html.='(������� ��� ��������������)';
				$html.='</div>';
				break;
			case 'tb':	// table
				$cnt=get_data('count(*)', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
                $hval=typed_objects_get_table_html_value($id, $obj_prop['id']);
				$td=typed_objects_get_table_description($type, $obj_prop['id']);
				$ew=$td['width'];
                if ($ew=='') $ew=$td['columns_count']*150;
				if ($ew>1000) $ew=1000; if ($ew<300) $ew=300;
				$html.=<<<stop
<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$hval">
<input type="hidden" id="prop_editor_width_{$obj_prop['id']}" value="$ew">
<div class="typed_objects_property_table_values" id="propt_{$obj_prop['id']}" onClick="typed_objects_property_table('$id', '{$obj_prop['id']}', '$type')">
stop;
				if ($cnt) $html.="���������� �����: $cnt ";
				$html.='(������� ��� ��������������)';
				$html.='</div>';
				break;
			case 'file':	// Attached file
				$att_id=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				if ($att_id!='') $att=pmGetAttachment($att_id);
				else $att=array('real_file'=>'', 'name'=>'');
				if ($att['name']=='') $att_name='������� ��� �������� ���������';
				else $att_name="{$att['name']} ({$att['real_file']})";
				$html.=<<<stop
		<input type="hidden" id="prop_{$obj_prop['id']}" name="{$obj_prop['id']}" value="$att_id|{$att['real_file']}|{$att['name']}"/>
		<input type="hidden" id="propf_{$obj_prop['id']}" value="{$att['real_file']}"/>
		<input type="hidden" id="propn_{$obj_prop['id']}" value="{$att['name']}"/>
		<div class="typed_objects_prop_file" id="propt_{$obj_prop['id']}" onClick="typed_objects_prop_edit_attachment_edit('{$obj_prop['id']}')">$att_name</div>
		<div class="typed_objects_prop_edit_attachment_delete"><img src="$_admin_root_url/images/delete_24.png" alt="" onClick="typed_objects_prop_edit_attachment_delete('{$obj_prop['id']}')"/></div>
stop;
				break;
		}
		if (!isset($obj_prop['no_row_end']) || $obj_prop['no_row_end']!==true) $html.='</td></tr>';
	}
	return $html;
}
// -----------------------------------------------------------------------------
// ��������� ���������� ���� ���� "�������"
// -----------------------------------------------------------------------------
function typed_objects_get_table_description($obj_type, $prop_type)
{
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	$desc=array();
	$desc['columns']=explode('|', $prop['columns']);
	$desc['columns_count']=count($desc['columns']);
	if(isset($prop['width']))
	{
		$desc['width']=$prop['width'];
		$desc['cellwidth']=($desc['width']-85)/$desc['columns_count']-18;
	}
	else
	{
		$desc['width']='';
		$desc['cellwidth']='';
	}
	return $desc;
}
// -----------------------------------------------------------------------------
// ��������� HTML ���� ��� ���� � ������� ���� ���� ������
// -----------------------------------------------------------------------------
function typed_objects_get_table_html_value($obj_id, $prop_type)
{
	global $html_charset, $_cms_objects_details;

	$str='';
	$res=query("select * from $_cms_objects_details where node='$obj_id' and typeId='$prop_type' order by id");
	while($r=mysql_fetch_assoc($res))
	{
		$val=htmlspecialchars($r['value'], ENT_QUOTES, $html_charset);
		$str.="$val";
	}
	mysql_free_result($res);
	return $str;
}
// -----------------------------------------------------------------------------
// ��������� HTML ���� ��� �������������� �������
// -----------------------------------------------------------------------------
function typed_objects_table_edit_get_html($id, $prop_type, $prop_value, $obj_type)
{
	$object_description=typed_objects_get_object_description($obj_type);
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	$desc=typed_objects_get_table_description($obj_type, $prop_type);

	$html=<<<stop
<div class="typed_object_table_edit_container">
	<input type="hidden" id="typed_objects_table_edit_object_id" value="$id"/>
	<input type="hidden" id="typed_objects_table_edit_object_prop_type" value="$prop_type"/>
	<input type="hidden" id="typed_objects_table_edit_object_obj_type" value="$obj_type"/>
	<div class="typed_object_table_edit_caption">
stop;
	for($i=0; $i<$desc['columns_count']; $i++)
		$html.=<<<stop
<div class="hdr" style="width: {$desc['cellwidth']}px;">{$desc['columns'][$i]}</div>
stop;
	$html.='</div>';
	$cells=typed_objects_prop_explode_fragments_string($prop_value);
	$html.='<div id="typed_object_table_edit_nodes_sortable">';
	for($i=0; $i<count($cells); $i+=$desc['columns_count'])
		$html.=typed_objects_table_edit_get_row_html($i/$desc['columns_count'], $cells, $i, $desc, $desc['cellwidth'], true);
	$max_row=count($cells)/$desc['columns_count'];
	$html.=typed_objects_table_edit_get_row_html($max_row, array_fill(0, $desc['columns_count'], ''), 0, $desc, $desc['cellwidth'], false);
	$html.=<<<stop
	</div>
	<input type="hidden" id="typed_objects_table_edit_max_row" value="$max_row"/>
	<hr>
	<input type="button" class="_left" value="���������" onClick="typed_objects_table_edit_save()"/>
	<input type="button" class="_right" value="������" onClick="typed_objects_table_edit_cancel()"/>
</div>
<script type="text/javascript">
	typed_objects_table_edit_refresh_sortable();
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// ��������� HTML ���� ��� �������������� ������ �������
// -----------------------------------------------------------------------------
function typed_objects_table_edit_get_row_html($row_id, $cells, $pos, $desc, $cellwidth, $can_delete)
{
	global $html_charset, $_admin_root_url;

	$row_idx=$row_id+1;
	$html=<<<stop
<div class="typed_object_table_edit_node" id="typed_object_table_edit_row_{$row_id}">
<div class="typed_object_table_edit_nod_number">$row_idx.</div>
stop;
    $empty=true;
	for($i=$pos; $i<$pos+$desc['columns_count']; $i++)
	{
		if ($cells[$i]!='') $empty=false;
		$cell=htmlspecialchars(strtr($cells[$i], array('\\['=>'[', '\\]'=>']')), ENT_QUOTES, $html_charset);
		$html.=<<<stop
<input class="typed_object_table_edit_node_cell" type="text" value="$cell" style="width: {$cellwidth}px;" onKeyUp="typed_object_table_edit_row_key_control($row_id)"/>
stop;
	}
    if(!$empty && $can_delete) $img_sl='';
	else $img_sl=' style="display: none;"';
	$html.=<<<stop
<img src="$_admin_root_url/images/delete_24.png" alt="" title="������� ������" onClick="typed_object_table_edit_row_delete($row_id)" $img_sl/>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// ��������� HTML ���� ��� ���� � ������� ���� ���� ����������������� �����
// -----------------------------------------------------------------------------
function typed_objects_get_structured_text_html_value($obj_id, $obj_prop_id)
{
	global $html_charset, $_cms_objects_details, $_cms_text_parts;

	if ($obj_id==-1) return '';
	$prop_id=get_data('id', $_cms_objects_details, "node='$obj_id' and typeId='$obj_prop_id'");
	$res=query("select * from $_cms_text_parts where node='$prop_id' and type=1 order by sort");
	$val='';
	while($r=mysql_fetch_assoc($res))
	{
		$title=strtr($r['title'], array('['=>'\\[', ']'=>'\\]'));
		$title=htmlspecialchars($title, ENT_QUOTES, $html_charset);
		$content=strtr($r['content'], array('['=>'\\[', ']'=>'\\]'));
		$content=htmlspecialchars($content, ENT_QUOTES, $html_charset);
		$val.="[{$r['id']}][$title][{$r['image']}][$content]";
	}
	return $val;
}
// -----------------------------------------------------------------------------
// ������ ������ � ��������� ������������������ ������ � ������.
// �������� ������� ����������� ��������� �������
// ��� ������������������ ������:
// 	+0 - id ���������
//	+1 - ��������� ���������
//	+2 - ��� ����� � ������������
//	+3 - ����� ���������
// ��� �������:
//	+0 - �������� ������� 1
//	+1 - �������� ������� 2
//		...
//	+n - �������� ������� n+1
// -----------------------------------------------------------------------------
function typed_objects_prop_explode_fragments_string($text)
{
	$len=strlen($text);
	$in_braces=true;
	$fragments=array();
	$fragmet_start=1;
    for($pos=1; $pos<$len; $pos++)
	{
		if ($in_braces)
		{
			if ($text[$pos]==']' && $text[$pos-1]!='\\')
			{
				$in_braces=false;
				$fr=substr($text, $fragmet_start, $pos-$fragmet_start);
				array_push($fragments, trim($fr));
			}
		}
		else
		{
			if ($text[$pos]=='[' && $text[$pos-1]!='\\')
			{
				$in_braces=true;
				$fragmet_start=$pos+1;
			}

		}
	}
	return $fragments;
}
// -----------------------------------------------------------------------------
// ��������� HTML ���� ��� �������������� ������������������ ������
// -----------------------------------------------------------------------------
function typed_objects_structured_text_edit_get_html($id, $prop_type, $text, $obj_type)
{
	global $_cms_objects_image_sx, $_cms_objects_image_sy;

    $object_description=typed_objects_get_object_description($obj_type);
	$prop=typed_objects_get_object_detail($obj_type, $prop_type);
	if (isset($prop['sx']) && $prop['sx']!='') $sx=$prop['sx'];
	elseif (isset($object_description['sx']) && $object_description['sx']!='') $sx=$object_description['sx'];
	else $sx=$_cms_objects_image_sx;

	if (isset($prop['sy']) && $prop['sy']!='') $sy=$prop['sy'];
	elseif (isset($object_description['sy']) && $object_description['sy']!='') $sy=$object_description['sy'];
	else $sy=$_cms_objects_image_sy;

	$html=<<<stop
<div class="typed_object_structure_text_edit_container">
	<input type="hidden" id="typed_objects_structured_text_edit_object_id" value="$id"/>
	<input type="hidden" id="typed_objects_structured_text_edit_object_prop_type" value="$prop_type"/>
	<input type="hidden" id="typed_objects_structured_text_edit_object_obj_type" value="$obj_type"/>
	<input type="hidden" id="typed_objects_structured_text_edit_current_fragment_id" value=""/>
	<input type="hidden" id="typed_objects_edit_object_text_sx" value="$sx">
	<input type="hidden" id="typed_objects_edit_object_text_sy" value="$sy">
stop;
	$fragments=typed_objects_prop_explode_fragments_string($text);
	$html.='<div id="typed_object_structure_text_edit_nodes_sortable">';
	for($i=0; $i<count($fragments); $i+=4)
		$html.=typed_objects_structured_text_edit_get_fragment_html($fragments[$i], $fragments[$i+1], $fragments[$i+2], $fragments[$i+3], $obj_type);
	$html.=<<<stop
	</div>
	<input type="button" value="�������� ��������" onClick="typed_objects_structure_text_add_fragment()" id="typed_objects_structure_text_add_fragment"/>
	<hr>
	<input type="button" class="_left" value="���������" onClick="typed_objects_structure_text_edit_save()"/>
	<input type="button" class="_right" value="������" onClick="typed_objects_structure_text_edit_cancel()"/>
</div>
<script type="text/javascript">
	typed_objects_structure_text_edit_refresh_sortable();
	$(".typed_object_structure_text_edit_node").show();
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_structured_text_edit_get_fragment_html($id, $title, $image, $content, $obj_type)
{
	global $_admin_uploader_url;

	$content=stripcslashes($content);
	$html=<<<stop
<div class="typed_object_structure_text_edit_node" id="fragment_id_{$id}">
<div class="typed_object_structure_text_edit_node_image_container">
	<h2>�����������</h2>
	<input type="hidden" id="fragment_image_name_{$id}" value="$image" />
	<img src="$_admin_uploader_url/temp/$image" id="fragment_image_{$id}" alt="" />
	<input class="admin_tool_button" type="button" value="���������" onClick="typed_objects_structure_text_edit_image_load('$id')"/>
	<input class="admin_tool_button" type="button" value="�������" onClick="typed_objects_structure_text_edit_image_clear('$id')"/>
</div>
<div class="typed_object_structure_text_edit_node_info_container">
	<div class="typed_object_structure_text_edit_node_info_title"><span>���������:</span><input type="text" id="fragment_title_{$id}" value="$title"/></div>
	<div class="typed_object_structure_text_edit_node_info_text" name="fragment_content_{$id}" id="fragment_content_{$id}" onClick="typed_objects_structure_text_edit_fragment_edit_text('$id')">$content</div>
	<div class="typed_object_structure_text_edit_node_info_text_controls" id="fragment_text_controls_{$id}"></div>
</div>
<hr>
<input class="admin_tool_button _left" type="button" value="������� ��������" onClick="typed_objects_structure_text_edit_fragment_delete('$id')"/>
<br>
stop;

	$html.='</div>';
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_save_object_data($id, $obj_type, $menu_item, $name, $note, $img, $gallery, $props)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $html_charset;
	global $_admin_uploader_path, $_base_site_objects_images_path, $_base_site_structured_text_images_path;

	if ($gallery=='') $gallery=0;
	if ($id!=-1)
	{
		$obj_type=get_data('type', $_cms_objects_table, "id='$id'");
		$old_img=get_data('image', $_cms_objects_table, "id='$id'");
		if ($img!='')
		{
			if ($old_img!='')
				@unlink("$_base_site_objects_images_path/$old_img");
			$dest=create_unique_file_name($_base_site_objects_images_path, $img);
			@rename("$_admin_uploader_path/temp/$img", $dest);
			$pp=pathinfo($dest);
			$img=$pp['basename'];
			query("update $_cms_objects_table set image='$img' where id='$id'");
		}
		else
		{
			if ($old_img!='')
			{
				@unlink("$_base_site_objects_images_path/$old_img");
				query("update $_cms_objects_table set image='' where id='$id'");
			}
		}
		query("update $_cms_objects_table set name='$name', note='$note' where id='$id'");

// ��������� ���� ���������� ���� "����������������� ����"
// ������� ������ � ������� $_cms_text_parts � ��������� �����������
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
                	@unlink("$_base_site_structured_text_images_path/{$r['image']}");
				query("delete from $_cms_text_parts where node='$prop_id' and type=1");
			}
	}
	else
	{
		if ($img!='')
		{
			$dest=create_unique_file_name($_base_site_objects_images_path, $img);
			rename("$_admin_uploader_path/temp/$img", $dest);
			$pp=pathinfo($dest);
			$img=$pp['basename'];
		}
		query("insert into $_cms_objects_table (menu_item, type, name, note, date, visible, image, gallery) values ('$menu_item', '$obj_type', '$name', '$note', CURDATE(), 0, '$img', '$gallery')");
		$id=mysql_insert_id();
	}
	query("delete from $_cms_objects_details where node='$id' and type<>'i'");
   	parse_str($props, $details);
	foreach($details as $k=>$v)
	{
		$v=iconv('utf-8', $html_charset, $v);
		$type=typed_objects_get_detail_type($k, $obj_type);
		if ($v!='' && $type!='')
		{
			switch($type)
			{
				case 'tb':
					$object_description=typed_objects_get_object_description($obj_type);
					$desc=typed_objects_get_table_description($obj_type, $k);
                    $fr=typed_objects_prop_explode_fragments_string($v);
					for($i=0; $i<count($fr); $i+=$desc['columns_count'])
					{
						$str='';
                        for($j=0; $j<$desc['columns_count']; $j++)
							$str.="[{$fr[$i+$j]}]";
						$str=mysql_real_escape_string($str);
						query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$str')");
					}
					break;
				case 'st':
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '')");
					$node_id=mysql_insert_id();
                    $fr=typed_objects_prop_explode_fragments_string($v);
					for($i=0; $i<count($fr); $i+=4)
					{
						if ($fr[$i+2]!='')
						{
							$dest=create_unique_file_name("$_base_site_structured_text_images_path", $fr[$i+2]);
							$src="$_admin_uploader_path/temp/{$fr[$i+2]}";
							@rename($src, $dest);
							@unlink($src);
							$pp=pathinfo($dest);
                            $fr[$i+2]=$pp['basename'];
						}
						$title=mysql_real_escape_string(strtr($fr[$i+1], array('\\['=>'[', '\\]'=>']')));
						$content=mysql_real_escape_string(strtr($fr[$i+3], array('\\['=>'[', '\\]'=>']')));
						query("insert into $_cms_text_parts (type, node, date, title, image, content, sort, visible) values (1, '$node_id', CURDATE(), '$title', '{$fr[$i+2]}', '$content', '{$fr[$i]}', 1)");
					}
					break;
				case 'dm': // ������������� ����� �� �����������
	    			$ve=explode('|', $v);
					foreach($ve as $v)
						query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					break;
				case 'date':	// string of date
					$d=substr($v, 0, 2);
					$m=substr($v, 3, 2);
					$y=substr($v, 6, 4);
					$date_v="$y-$m-$d";
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$date_v')");
					break;
				case 'html':
					$v=htmlspecialchars_decode($v, ENT_QUOTES);
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					break;
				case 'file':
					$att=explode('|', $v);
					$att[1]=iconv ($html_charset, 'utf-8', $att[1]);    // ���������� ��� ����� � UTF-8
					$attachment_id=common_attachment_save($att[0], $att[1], $att[2]);
					if ($attachment_id!==false)
						query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$attachment_id')");
					break;
				default:
					query("insert into $_cms_objects_details (node, typeId, type, value) values ('$id', '$k', '$type', '$v')");
					break;
			}
    	}
	}
}
// -----------------------------------------------------------------------------
function typed_objects_get_dir_values_html($type, $id, $vals)
{
	global $object_description, $_cms_directories_data;
	$object_description=typed_objects_get_object_description($type);
	$prop='';
	foreach($object_description['details'] as $d)
		if ($d['id']==$id) $prop=$d;
	if ($prop=='') return;
	$html=<<<stop
<h2>{$prop['name']}</h2>
stop;
	$vals=explode('|', $vals);
	$res=query("select * from $_cms_directories_data where dir='{$prop['options']}' order by content");
	while($r=mysql_fetch_assoc($res))
	{
		if (in_array($r['id'], $vals)) $ch='checked="checked"';
		else $ch='';
		$html.=<<<stop
<div class="typed_objects_dir_select_node">
<input type="checkbox" id="dir_m_{$r['id']}" name="dir_m_{$r['id']}" value="1" $ch/> {$r['content']}
</div>
stop;
	}
	mysql_free_result($res);
	$html.=<<<stop
<hr>
<input type="button" value="���������" onClick="typed_objects_property_dir_save($type, '$id')"/>
<script type="text/javascript">
$("input[type=checkbox][id ^= 'dir_m_']").imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent: true});
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function typed_objects_dir_values_save($id, $props)
{
	global $_cms_directories_data;

   	parse_str($props, $values);
	$data='';
	$text='';
	foreach($values as $k=>$v)
	{
		$k=substr($k, 6);
		$data.="$k|";
		$val=get_data('content', $_cms_directories_data, "id='$k'");
		$text.="$val, ";
	}
	$data=substr($data, 0, -1);
	$text=substr($text, 0, -2);
	return serialize_data('data|text', $data, $text);
}
// -----------------------------------------------------------------------------
function typed_objects_get_sort_html($menu_item)
{
	global $_cms_objects_table;

    $res=query("select id, name from $_cms_objects_table where menu_item='$menu_item' order by sort");
	$html='<div class="typed_object_object_sort_container"><ul class="typed_object_object_sort_list" id="typed_object_object_sort_list">';
	while($r=mysql_fetch_assoc($res))
	{
		$html.=<<<stop
<li id="obj_sort_{$r['id']}">{$r['name']}</li>
stop;
	}
	$html.=<<<stop
</ul></div>
<input type="button" value="���������" onClick="typed_objects_sort_save()"/>
stop;
	mysql_free_result($res);
	return $html;
}
// -----------------------------------------------------------------------------
// Object copy routine
// id			- ID object to copy
// menu			- ID of menu_item (table $_cms_menus_items_table) to copy
// copy_mode    - mode of operation (0-move; 1-copy)
// -----------------------------------------------------------------------------
function typed_objects_object_move($id, $menu, $copy_mode)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_gallery_table;
	global $_base_site_galleries_path, $_cms_gallery_data_table;
	global $_cms_text_parts, $_base_site_structured_text_images_path;

	if(!$copy_mode)
    	query("update $_cms_objects_table set menu_item='$menu' where id='$id'");
	else
	{
        // Create the new record in the objects table
		$fields_list=mysql_get_fields_list($_cms_objects_table, 'id|menu_item');
		query("insert into $_cms_objects_table (menu_item, $fields_list) select '$menu' as menu_item, $fields_list from $_cms_objects_table where id='$id'");
		$new_id=mysql_insert_id();
		// Creating the new records in the object details table
        $fields_list=mysql_get_fields_list($_cms_objects_details, 'id|node');
        query("insert into $_cms_objects_details (node, $fields_list) select $new_id as node, $fields_list from $_cms_objects_details where node='$id'");
		if (isset($_cms_gallery_table) && $_cms_gallery_table!='')
		{
			// Copy linked gallery images and creating the new records in gallery table
			$res=query("select * from $_cms_gallery_table where menu_item='$id' and link_type=1");
			while ($r=mysql_fetch_assoc($res))
			{
				$dest=create_unique_file_name($_base_site_galleries_path, $r['file']);
				$pp=pathinfo($dest);
				$dest_thumb="$_base_site_galleries_path/thumbs/{$pp['basename']}";
	        	copy("$_base_site_galleries_path/{$r['file']}", $dest);
	        	copy("$_base_site_galleries_path/thumbs/{$r['file']}", $dest_thumb);
				query("insert into $_cms_gallery_table (menu_item, file, title, comment, sort, visible, link_type) values ('$new_id', '{$pp['basename']}', '{$r['title']}', '{$r['comment']}', '{$r['sort']}', '{$r['visible']}', '{$r['link_type']}')");
			}
			mysql_free_result($res);

			// Copy the gallery data record and link to new gallery (object)
	        $fields_list=mysql_get_fields_list($_cms_gallery_data_table, 'id|menu_item');
	        query("insert into $_cms_gallery_data_table (menu_item, $fields_list) select $new_id as menu_item, $fields_list from $_cms_gallery_data_table where menu_item='$id'");
		}
		// Copy linked text parts images and change link to images in new text parts records
		$resT=query("select * from $_cms_objects_details where node='$new_id' and type='st'");
//		echo "new ID[$new_id] ".mysql_num_rows($resT)."\n";
        $fields_list=mysql_get_fields_list($_cms_text_parts, 'id|node');
		while ($rt=mysql_fetch_assoc($resT))
		{
	    	// Copy text parts subobjects
			$old_node=get_data('id', $_cms_objects_details, "node='$id' and typeID='{$rt['typeId']}'");
	        query("insert into $_cms_text_parts (node, $fields_list) select {$rt['id']} as node, $fields_list from $_cms_text_parts where node='$old_node' and type=1");

			// TODO: ��������� ����������� ����������� ��� ����������� ��������
			$res=query("select * from $_cms_text_parts where node='{$rt['id']}'");
//			echo "text part ID[{$rt['id']}] ".mysql_num_rows($res)."\n";
			while ($r=mysql_fetch_assoc($res))
			{
				$dest=create_unique_file_name($_base_site_structured_text_images_path, $r['image']);
				$pp=pathinfo($dest);
//				echo "[{$r['id']}] $_base_site_structured_text_images_path/{$r['image']} -> $dest\n";
	        	copy("$_base_site_structured_text_images_path/{$r['image']}", $dest);
				query("update $_cms_text_parts set image='{$pp['basename']}' where id='{$r['id']}'");
			}
			mysql_free_result($res);
		}
		mysql_free_result($resT);

		// �������� ��� ����������
		$res=query("select * from $_cms_objects_details where node='$new_id' and type='file'");
		while($r=mysql_fetch_assoc($res))
		{
			$new_attachment=common_attachment_duplicate($r['value']);
			if ($new_attachment===false) continue;
			query("update $_cms_objects_details set value='$new_attachment' where id='{$r['id']}'");
		}
		mysql_free_result($res);
	}
}
// -----------------------------------------------------------------------------
function typed_objects_object_delete($id)
{
	global $_cms_objects_table, $_cms_objects_details, $_cms_text_parts, $_cms_gallery_table;
	global $_base_site_galleries_path, $_cms_gallery_data_table, $_base_site_structured_text_images_path;

    $obj_type=get_data('type', $_cms_objects_table, "id='$id'");
	if ($obj_type!==false)
	{
		$object_description=typed_objects_get_object_description($obj_type);
		foreach($object_description['details'] as $obj_prop)
		{
// �������� ���� ���������� ���� "����������������� ����"
// ������� ������ � ������� $_cms_text_parts � ��������� �����������
			if($obj_prop['type']=='st')
			{
				$prop_id=get_data('id', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				$res=query("select id, image from $_cms_text_parts where node='$prop_id' and type=1");
				while($r=mysql_fetch_assoc($res))
	               	@unlink("$_base_site_structured_text_images_path/{$r['image']}");
				query("delete from $_cms_text_parts where node='$prop_id' and type=1");
			}
// �������� ���� ���������� ���� file
// ������� ��� ������������� �����
			if($obj_prop['type']=='file')
			{
				$attachment_id=get_data('value', $_cms_objects_details, "node='$id' and typeId='{$obj_prop['id']}'");
				common_attachment_delete($attachment_id);
			}
		}
    }

	query("delete from $_cms_objects_details where node='$id'");
	query("delete from $_cms_objects_table where id='$id'");

	if (isset($_cms_gallery_table) && $_cms_gallery_table!='')
	{
		$res=query("select * from $_cms_gallery_table where menu_item='$id' and link_type=1");
		while($r=mysql_fetch_assoc($res))
		{
	       	@unlink("$_base_site_galleries_path/{$r['file']}");
	       	@unlink("$_base_site_galleries_path/thumbs/{$r['file']}");
		}
		mysql_free_result($res);
		query("delete from $_cms_gallery_table where menu_item='$id' and link_type=1");
		query("delete from $_cms_gallery_data_table where menu_item='$id' and link_type=1");
	}
}
// -----------------------------------------------------------------------------
function typed_objects_dir_add_get_html($dir_id, $prop_id)
{

}
// -----------------------------------------------------------------------------
?>
