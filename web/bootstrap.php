<?php
/**
 * App bootstrap
 */

// Boot the app system
require_once WEBPATH.'config/path.php';
require_once WEBPATH_CONFIG.'config.php';

// Route the path
MVCSystem::debug("MVC basic structure boot up Success");
$System=new MVCSystem();





