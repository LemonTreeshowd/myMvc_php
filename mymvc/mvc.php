<?php
/**
 *	MVC模型核心类库
 *	author: love_shift
 *  date: 2016/04/14
 */


//设置时区为中国时区: PRC
date_default_timezone_set('PRC');

/**
 * 定义项目环境变量
 */

//项目跟路径，且绝对路径.
define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']));
var_dump(APP_PATH);
//站点路径，相对路径
define('SITE_PATH',dirname($_SERVER['SCRIPT_NAME']));
//系统配置路径
define('APP_SYS_PATH',dirname(__FILE__));
// define('APP_SITE_PATH',dirname(dirname(__FILE__)));


//引用全局函数
require_once(APP_SYS_PATH."/functions.php");


//加载配置文件 config.php 
$configFile = dirname(APP_SYS_PATH)."/config.php";
// var_dump($configFile);

$_config = array();

if (file_exists($configFile)) {
	$_config = require_once($configFile);

}

var_dump('The $_config is '.json_encode($_config));
// 加载命名空间
$namespaces = C("namespaces");
var_dump($namespaces);
spl_autoload_register("loader");


$tempPath = C("temp_path");
if(!isset($tempPath)){
    //如果没有配置缓存目录，默认是根目录下的runtime文件夹
    $tempPath = dirname(APP_SYS_PATH)."/runtime/";
}
define("TEMP_PATH",$tempPath);
//定义缓存目录
define('CACHE_PATH',$tempPath."cache/");
//定义日志存放目录
// define('LOG_PATH',$cachePath."log/");



//获取控制器 && 方法
$route = getRoute();
$controller = $route['c'];
$action = $route['a'];

$controllerName = $controller.'Controller';
$controllerFile = sprintf("%s/app/controller/%s.php",APP_PATH,$controllerName);

// var_dump($controllerFile);

if (file_exists($controllerFile)) {
	// 引用控制器.
	require_once APP_SYS_PATH."/controller.php";
	require_once $controllerFile;
	// 申明类的实例 && 调用方法.
	$myins = new $controllerName();
	$myins->_before_action();
	$myins->{$action}();
	$myins->_after_action();

} else {
	echo "Page not found. 404";

}


?>