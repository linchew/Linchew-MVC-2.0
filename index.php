<?php 
/**
 * Linchew MVC Framework
 *
 * @package    Linchew MVC
 * @version    2.0
 * @author     Linchew
 * @license    No Licencse
 * @copyright  2012 - 2013 Linchew
 * @link       http://Not.yet
 * 
 * PHP Ver.	   5.3+
 */

/**
 * Set error reporting and display errors settings.  
 * You will want to change these when in production.
 */
error_reporting(-1);
ini_set('display_errors', 1);

/**
 * Website root, Basic Two kinds of path
 * (1) CORE_PATH
 * (2) APP_PATH
 */

define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);
define('COREPATH', 	realpath(DOCROOT.'/core/').DIRECTORY_SEPARATOR);
define('WEBPATH', 	realpath(DOCROOT.'/web/').DIRECTORY_SEPARATOR);

// Boot the core system
require_once COREPATH."bootstrap.php";

// Boot the web system
require_once WEBPATH."bootstrap.php";



