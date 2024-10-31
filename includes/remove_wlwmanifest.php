<?php 
// 移除head中的rel="wlwmanifest"
remove_action('wp_head', 'wlwmanifest_link');

?>