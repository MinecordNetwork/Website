<?php
$content = file_get_contents('https://surv.minecord.net/index.html');
$content = str_replace('src="', 'src="https://surv.minecord.net/', $content);
$content = str_replace('href="', 'href="https://surv.minecord.net/', $content);
echo $content;
