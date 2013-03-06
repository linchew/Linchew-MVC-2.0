<?php
/**
 * @category core
 * @name config
 */

//setup autoload function

function __autoload($class_name)
{
	if(file_exists ( stream_resolve_include_path($class_name . '.php' ))){
		require_once $class_name . '.php';
	}
}

//setup inlcude path
//---
$include_path[] = get_include_path();
$include_path[] = COREPATH . 'classes';
$include_path[] = COREPATH . 'plugin';

set_include_path(join(PATH_SEPARATOR, $include_path));

//setup include plugin
//---
$_plugin_array=require_once COREPATH.'config/plugin.php';
foreach($_plugin_array as $folder => $boot_file)
{
	require_once COREPATH.'plugin'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$boot_file;
}