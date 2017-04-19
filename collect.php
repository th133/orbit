<?php

require_once('config.php');

$db_selected = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(!$db_selected) {
    die('Could not connect: '. mysqli_connect_error());
}

$v1 = $_POST['type'];
$v2 = date("Y/m/d");
$v3 = $_POST['title'];
$v4 = $_POST['content'];

$sql = "INSERT INTO uploads (type, date, title, content) VALUES ('$v1', '$v2', '$v3', '$v4')";

if(!mysqli_query($db_selected, $sql)) {
    die('Error:' . mysqli_connect_error());
}
header("LOCATION: new_post.html");
mysqli_close($db_selected);
?>