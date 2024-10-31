<?php 
// 禁用修改历史记录
remove_action('pre_post_update', 'wp_save_post_revision');

?>