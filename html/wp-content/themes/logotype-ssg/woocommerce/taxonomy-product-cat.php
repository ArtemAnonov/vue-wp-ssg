<?php
$uri = $_SERVER['REQUEST_URI'];
$uri = substr($uri, 0, -1);
get_static_html_page($uri);
