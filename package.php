<?php
/**
 * Plugin Name: Qlwz Package
 * Plugin URI: http://www.94qing.com/qlwz-package.html
 * Description: Qlwz Package
 * Author: 情留メ蚊子
 * Author URI: http://www.94qing.com/
 * Version: 1.0.0
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Qlwz_Package {
	var $fileinfo = array();
	function Qlwz_Package() {
		if (is_file(dirname(__FILE__) . '/fileinfo.php')) {
			$this -> fileinfo = require dirname(__FILE__) . '/fileinfo.php';
		} else {
			$this -> fileinfo = array();
		} 
		add_action('admin_menu', array(&$this, 'admin_menu'));
	} 

	function admin_menu() {
		if (current_user_can('manage_options')) {
			add_options_page('Qlwz Package', 'Qlwz Package', 'manage_options', plugin_basename(__FILE__), array(&$this, 'options_page'));
		} 
	} 

	function loadinclude() {
		foreach($this -> fileinfo as $key => $value) {
			if ($value['enable'] == 1 && $value['error'] != 1) {
				$file = dirname(__FILE__) . "/includes/{$key}";
				if (is_file($file)) {
					include_once $file;
				} 
			} 
		} 
	} 

	function post() {
		if (isset($_POST) && strpos($_SERVER['HTTP_REFERER'], "wp-admin/options-general.php?page=qlwz-package/package.php") !== false) {
			$file = $_POST['file'];
			if ($_POST['action'] == 'setenable') {
				$enable = $_POST['enable'];
				if (!array_key_exists($file, $this -> fileinfo)) {
					die("不存在" . $file . "的记录");
				} 
				$this -> fileinfo[$file]['enable'] = $enable;
				$this -> sava();
				die("ok");
			} else if ($_POST['action'] == 'addpackage') {
				if (array_key_exists($file, $this -> fileinfo)) {
					die("已经存在" . $file . "的记录");
				} 
				$ary = array();
				$ary["info"] = $_POST['info'];
				$ary["enable"] = 0;
				$this -> fileinfo[$file] = $ary;
				$this -> sava();
				die("ok");
			} 
		} 
	} 

	function sava() {
		$cachefile = dirname(__FILE__) . '/fileinfo.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= 'return ' . var_export($this -> fileinfo, true) . ";\r\n";
		fwrite($fp, $s);
		fclose($fp);
	} 

	function getincludefile() {
		$adapter_dir = dirname(__FILE__) . "/includes/";
		if (!is_dir($adapter_dir)) {
			return array();
		} 
		$fdir = opendir($adapter_dir);
		$adapter_file = array();
		while ($file = readdir($fdir)) {
			if ($file == '.' || $file == '..') continue;
			if (is_file($adapter_dir . $file)) {
				$adapter_file[] = $file;
			} 
		} 
		closedir($fdir);
		return $adapter_file;
	} 

	function options_page() {

		?>
<br/>
<br/>
<table class="widefat" cellspacing="0">
	<thead>
	<tr>
		<th scope='col' class='manage-column' width='20%'><span>文件名</span></th>
		<th scope='col' class='manage-column' width='65%'><span>文件说明</span></th>
		<th scope='col' class='manage-column' width='15%'><span>状态</span></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope='col' class='manage-column' width='20%'><span>文件名</span></th>
		<th scope='col' class='manage-column' width='65%'><span>文件说明</span></th>
		<th scope='col' class='manage-column' width='15%'><span>状态</span></th>
	</tr>
	</tfoot>

	<tbody id="the-comment-list" class="list:comment">
	<?php
		$files = $this -> getincludefile();
		$i = 0;
		foreach($files as $file) {
			$id = str_replace('.php', '', $file);
			echo '<tr class="' . (++$i % 2) == 0 ? "" : "alternate" . '" valign="top">';
			echo '<td><a href="plugin-editor.php?file=qlwz-package%2Fincludes%2F' . $file . '&plugin=qlwz-package%2Ffileinfo.php">' . $file . '</a></td>';
			if (array_key_exists($file, $this -> fileinfo)) {
				echo '<td id="info' . $id . '">' . $this -> fileinfo[$file]['info'] . '</td>';
				if ($this -> fileinfo[$file]['enable'] == 0) {
					echo '<td id="onclick' . $id . '"><input class="button" style="color:#FF0000" value="开启" type="button" onclick="SetEnable(1, \'' . $file . '\');" /></td>';
				} else {
					echo '<td id="onclick' . $id . '"><input class="button" style="color:#0000FF" value="关闭" type="button" onclick="SetEnable(0, \'' . $file . '\');" /></td>';
				} 
			} else {
				echo '<td id="info' . $id . '">不存在' . $file . '的记录</td>';
				echo '<td id="onclick' . $id . '"><input class="button-primary" value="导入" type="button" onclick="Into(\'' . $file . '\');" /></td>';
			} 
			echo '</tr>';
		} 

		?>
	</tbody>
</table>
<p class="submit">
	<a class='button-primary' href='plugin-editor.php?file=qlwz-package%2Ffileinfo.php&plugin=qlwz-package%2Fpackage.php'>修改配置文件</a>
</p>
<script type="text/javascript">
var pluginpath = "options-general.php?page=qlwz-package/package.php";
function AddPackage(file) {
    var info = jQuery("#text" + file.replace(".php", "")).val();
    if (info.length < 2) {
        alert('请填写说明');
        return false
    }
    jQuery.ajax({
        type: "POST",
        url: pluginpath,
        data: "action=addpackage&info=" + info + "&file=" + file,
        success: function(obj) {
            if (obj == "ok") {
                jQuery("#info" + file.replace(".php", "")).html(info);
                jQuery("#onclick" + file.replace(".php", "")).html('<input class="button" style="color:#FF0000" value="开启" type="button" onclick="SetEnable(1, \'' + file + '\');" />')
            } else {
                alert(obj)
            }
        },
        error: function() {
            alert("导入失败")
        }
    })
}
function Into(file) {
    jQuery("#info" + file.replace(".php", "")).html('<input type="text" id="text' + file.replace(".php", "") + '" value="" class="regular-text" />');
    jQuery("#onclick" + file.replace(".php", "")).html('<input class="button-primary" value="确定" type="button" onclick="AddPackage(\'' + file + '\');" /><input class="button-primary" value="取消" type="button" onclick="NoInto(\'' + file + '\');" /></td>')
}
function NoInto(file) {
    jQuery("#info" + file.replace(".php", "")).html('不存在' + file + '的记录');
    jQuery("#onclick" + file.replace(".php", "")).html('<input class="button-primary" value="导入" type="button" onclick="Into(\'' + file + '\');" />')
}
function SetEnable(enable, file) {
    jQuery.ajax({
        type: "POST",
        url: pluginpath,
        data: "action=setenable&enable=" + enable + "&file=" + file,
        success: function(obj) {
            if (obj == "ok") {
                if (enable == 1) {
                    jQuery("#onclick" + file.replace(".php", "")).html('<input class="button" style="color:#0000FF" value="关闭" type="button" onclick="SetEnable(0, \'' + file + '\');" />')
                } else {
                    jQuery("#onclick" + file.replace(".php", "")).html('<input class="button" style="color:#FF0000" value="开启" type="button" onclick="SetEnable(1, \'' + file + '\');" />')
                }
            } else {
                alert(obj)
            }
        },
        error: function() {
            alert("设置失败")
        }
    })
}
</script>
<?php
	} 
} 

$package = new Qlwz_Package();
$package -> post();
$package -> loadinclude();

?>