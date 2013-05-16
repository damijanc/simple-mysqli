<?php

//function borrowed from wordpress core
function is_ssl () {
  if (isset($_SERVER['HTTPS'])) {
    if ('on' == strtolower($_SERVER['HTTPS']))
      return true;
    if ('1' == $_SERVER['HTTPS'])
      return true;
  }
  return false;
}

//if we use symbolic links in our path in apache config :)
$_SERVER['DOCUMENT_ROOT'] = realpath($_SERVER['DOCUMENT_ROOT']);

//put configuration.php in project root
if (!defined('__DOCROOT__')) {
  define('__DOCROOT__', dirname(__FILE__));
}

if (!defined('__SERVERNAME__')) {
  define('__SERVERNAME__', $_SERVER['SERVER_NAME']);
}

//webroot difference between document_root and __DOCROOT__
if (!defined('__WEBROOT__')) {
  define('__WEBROOT__', str_replace($_SERVER['DOCUMENT_ROOT'], '', __DOCROOT__));
}


if (!defined('__PROTOCOL__')) {
  if (is_ssl()) {
    define('__PROTOCOL__', 'https');
  }else {
    define('__PROTOCOL__', 'http');
  }
}

//use localised configuration
$hostname = php_uname('n');
$configuration_folder = dirname(__FILE__);
// local configuration files
if (file_exists($configuration_folder . '/configuration.' . $hostname . '.php')) {
  include($configuration_folder . '/configuration.' . $hostname . '.php');
}

if (!defined('DB_CONNECTION')) {
  define("DB_CONNECTION", serialize(
      array('server' => '10.0.2.10',
        'port' => 3386,
        'database' => 'test',
        'username' => 'test',
        'password' => 'test',
        'encoding' => 'utf8'))
  );
}
