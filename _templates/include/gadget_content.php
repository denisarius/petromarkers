	<?php

	$text = get_content();
	if ($text === false)
		show_content_404();
	else
	{
		set_page_title($text['title']);
//		используем так:
//		$text['title']
//	$text['content']
		echo <<<stop
		<div class="index_content_container">
			<div class="wrap_info_index_content">
				<span class="title">Современный старый<br> город</span>
				<p>Сайт информационного агентства «Интерфакс» недоступен для пользователей. По словам директора службы финансово-экономической информации агентства Юрия Погорелого, причиной послужили  DDoS-атаки начавшиеся еще ночью. Сейчас техподдержка ресурса ведет восстановительные работы.</p>
				<input type="button" value="Подробнее">
			</div>
			<img src="img/pics/pic1.jpg" alt="">
			<br>
		</div>
stop;
	}
	?>