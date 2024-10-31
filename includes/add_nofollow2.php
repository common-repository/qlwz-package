<?php 
// 评论跳转链接添加nofollow
function nofollow_compopup_link() {
	return' rel="nofollow"';
} 
add_filter('comments_popup_link_attributes', 'nofollow_compopup_link');

?>