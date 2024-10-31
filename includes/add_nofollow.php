<?php
/**
 * 回复某人链接添加nofollow
 * 这个理应是原生的, 可是在wp某次改版后被改动了,
 * 现在是仅当开启注册回复时才有nofollow,否则需要自己手动了
 */
function nofollow_comreply_link($link) {
	return str_replace('<a', '<a rel="nofollow"', $link);
} 
get_option('comment_registration') ||
add_filter('comment_reply_link', 'nofollow_comreply_link');

?>