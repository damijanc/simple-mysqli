<?php

include_once '../configuration.php';
require_once __DOCROOT__ . '/lib/DB.class.php';

$query = "CREATE TABLE db_test (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `status`  enum('ACCEPTED','GENERATED','UPLOADED','ARCHIVED', 'ERROR') DEFAULT 'ACCEPTED',
  `xml` text,
  `file` varchar(512) COLLATE utf8_slovenian_ci,
  PRIMARY KEY (`id`)
  )ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci";


$database =  new DB();
$database->Connect();
//let us make temporary table
$database->NonQuery($query);


//let us insert a record
$query = sprintf("INSERT INTO db_test (`key`,`status`,`xml`,`file`)
                  values ('%s','%s','%s' , '%s')",
                  'keysdssaddfasdasf','ACCEPTED',
                  addslashes('<?xml version="1.0" encoding="UTF-8" ?><root></root>'),
                  addslashes('/tmp/test.xml'));


//use transacton in out insert
$database->BeginTransaction();
$id = $database->NonQuery($query);
$database->Commit();

$query = sprintf('SELECT * from db_test where id = %s', $id);

$result = $database->Query($query);


//cleanup
$query = 'DROP TABLE db_test;';

$database->NonQuery($query);

$database->Disconnect();

echo '<pre>';
print_r($result);
echo '</pre>';
?>
