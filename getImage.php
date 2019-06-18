<?php

require_once "utils/functions.php";

$conn = getSilentConnection();

$id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;

// Get image from DB
$sql = "SELECT data, mime FROM images WHERE id IN ($id)";
$result = $conn->query($sql);
$image = $result->fetch_assoc();

$conn->close();

header("content-type: " . $image["mime"]);
echo $image["data"];
