<?php 
// 禁止半角符号自动变全角
foreach(array('comment_text', 'the_content', 'the_excerpt', 'the_title') as $xx) {
	remove_filter($xx, 'wptexturize');
} 

?>