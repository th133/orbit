<?php

require_once('config.php');

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

if(!$link) {
    die('Could not connect: '. mysql_error());
}

$db_selected = mysql_select_db(DB_NAME, $link);

if(!$db_selected) {
    die('Can\'t use ' . DB_NAME . ': ' .mysql_error());
}

$v1 = $_POST['type'];
$v2 = date("Y/m/d");
$v3 = $_POST['title'];
$v4 = $_POST['content'];

$sql = "INSERT INTO uploads (type, date, title, content) VALUES ('$v1', '$v2', '$v3', '$v4')";

if(!mysql_query($sql)) {
    die('Error:' . mysql_error());
}

mysql_close();
?>