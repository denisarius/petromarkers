	<?php

	$text = get_content();
	if ($text === false)
		show_content_404();
	else
	{
		set_page_title($text['title']);
//		���������� ���:
//		$text['title']
//	$text['content']
		echo <<<stop
		<div class="index_content_container">
			<div class="wrap_info_index_content">
				<span class="title">����������� ������<br> �����</span>
				<p>���� ��������������� ��������� ���������� ���������� ��� �������������. �� ������ ��������� ������ ���������-������������� ���������� ��������� ���� ����������, �������� ���������  DDoS-����� ���������� ��� �����. ������ ������������ ������� ����� ����������������� ������.</p>
				<input type="button" value="���������">
			</div>
			<img src="img/pics/pic1.jpg" alt="">
			<br>
		</div>
stop;
	}
	?>