<?php 
// 禁止在head泄露wordpress版本号
remove_action('wp_head', 'wp_generator');

?>