<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
global $OssnInstall;
if (!isset($OssnInstall)) {
    $OssnInstall = new stdClass;
}
session_start();

if (is_file('DISPLAY_ERRORS')) {
    error_reporting(E_NOTICE ^ ~E_WARNING);
} else {
    ini_set('display_errors', 'off');
}
if (is_file('INSTALLED')) {
    exit('It seems the Open Source Social Network is already installed');
}
require_once(dirname(__FILE__) . '/libraries/ossn.install.php');
require_once(dirname(__FILE__) . '/classes/OssnInstall.php');

//geneate .htaccess file #432
ossn_generate_server_config_setup('apache');

if (!isset($_REQUEST['action'])) {
    ossn_installation_page();
}
if (!isset($_REQUEST['page'])) {
    ossn_installation_actions();
}
  

