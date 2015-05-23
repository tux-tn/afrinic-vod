<?php
require_once "connection.php";
$dbh = new PDO($dsn, $username, $dbpassword, $options);
$query = "CREATE TABLE IF NOT EXISTS `video` (
  `id` int(10) unsigned NOT NULL,
  `url` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `views` smallint(5) unsigned NOT NULL DEFAULT '0',
  `speaker` varchar(128) DEFAULT NULL,
  `thumbnail` varchar(256) DEFAULT NULL,
  `presentation` varchar(256) DEFAULT NULL,
  `day` smallint(5) unsigned DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `starttime` smallint(5) unsigned DEFAULT NULL,
  `endtime` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$st = $dbh->prepare($query);
$st->execute();
if($st->errorCode() != "0000")
  echo "Error : " . $st->errorInfo()[2] ;
else echo "Done";
?>
