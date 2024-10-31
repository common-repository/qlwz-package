<?php 
// 禁止自动给文章段落添加<p>标签
remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

?>