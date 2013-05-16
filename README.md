simple-mysqli
=============

Simple wrapper for PHP MySqli driver


This lib simplifies usage of mysqli. Simple use case:
```php
$database =  new DB();
$database->Connect();
$query = sprintf("INSERT INTO db_test (`key`,`status`,`xml`,`file`)
                  values ('%s','%s','%s' , '%s')",
                  'keysdssaddfasdasf','ACCEPTED',
                  addslashes('<?xml version="1.0" encoding="UTF-8" ?><root></root>'),
                  addslashes('/tmp/test.xml'));

$database->BeginTransaction();
$id = $database->NonQuery($query);
$database->Commit();

$query = sprintf('SELECT * from db_test where id = %s', $id);
$result = $database->Query($query);
$database->Disconnect();
```

Check test for full use case.

TODO
----
* Ability to pass configuration as paramaeter instead of reading it from configuration.php
* Object binding
* ... 

