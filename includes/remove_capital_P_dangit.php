<?php 
// 禁止自动把'Wordpress'之类的变成'WordPress'
remove_filter('comment_text', 'capital_P_dangit', 31);
remove_filter('the_content', 'capital_P_dangit', 11);
remove_filter('the_title', 'capital_P_dangit', 11);

?>