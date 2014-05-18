	<?php

	$text = get_content();
	if ($text === false)
		show_content_404();
	else
	{
		global $_o;
		set_page_title(str_replace('|', ' ', $text['title']));
		$text['title']=str_replace('|', '<br>', $text['title']);
		echo <<<stop
		<div class="index_content_container">
			<div class="wrap_info_index_content">
				<span class="title">{$text['title']}</span>
			</div>
			<img src="@!template@/images/content_pic.jpg" alt="">
			<p>{$text['content']}</p>
			<br>
		</div>
stop;
	}
	?>