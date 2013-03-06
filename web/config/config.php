<?php
/**
 * @category app
 * @name bootstrap
 */

//setup inlcude path
//---
$include_path[] = get_include_path();
$include_path[] = WEBPATH . 'base';
$include_path[] = WEBPATH . 'classes';
$include_path[] = WEBPATH . 'classes'.DIRECTORY_SEPARATOR.'control';
$include_path[] = WEBPATH . 'classes'.DIRECTORY_SEPARATOR.'model';
$include_path[] = WEBPATH . 'classes'.DIRECTORY_SEPARATOR.'view';
$include_path[] = WEBPATH . 'lib';
$include_path[] = WEBPATH . 'template';

set_include_path(join(PATH_SEPARATOR, $include_path));



