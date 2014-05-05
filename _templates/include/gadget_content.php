	<?php

	$text = get_content();
	if ($text === false)
		show_content_404();
	else
	{
		global $_o;
		set_page_title($text['title']);
		echo <<<stop
		<div class="index_content_container">
			<div class="wrap_info_index_content">
				<span class="title">{$text['title']}</span>
				<p>{$text['content']}</p>
			</div>
			<img src="@!template@/images/content_pic.jpg" alt="">
			<br>
		</div>
stop;
	}
	?>