<?php

require_once "config/constants.php";

function console_log($data)
{
    echo "<script>";
    echo "console.log(" . json_encode($data) . ")";
    echo "</script>";
}

function alert($data)
{
    echo "<script>";
    echo "alert(" . json_encode($data) . ")";
    echo "</script>";
}

function pre_r($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function getConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!mysqli_connect_errno()) {
        console_log("Connected successfully!");
    } else {
        console_log("Database error: " . mysqli_connect_error());
        exit();
    }

    return $conn;
}

function getSilentConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (mysqli_connect_errno()) {
        exit();
    }

    return $conn;
}

function reArrayFiles($file_post)
{
    $file_ary = array();
    $file_count = count($file_post["name"]);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

// function displayImageFromDB($conn) {
//     $sql = "SELECT * FROM images";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     while ($row = $result->fetch_assoc()) {
//         echo "<img src=data:image;base64," . $row["image"] . " width='25%' />";
//         echo "<img src='data:image/jpeg;base64," . base64_encode($row["imageAlt"]) . "' width='50%' />";
//     }
// }
