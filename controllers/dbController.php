<?php

require_once "config/config.php";
require_once "utils/functions.php";

$errors = array();
$successes = array();

// when upload button is pressed
if (isset($_POST["upload-btn"])) {
    $imgTmpTitle = str_replace(" ", "_", $_POST["title"]);
    $imgTitle = $_FILES["image"]["name"];
    $imgType = $_FILES["image"]["type"];
    $imgTmpName = $_FILES["image"]["tmp_name"];
    $imgSize = $_FILES["image"]["size"];

    $tmp = explode(".", $imgTitle);
    $newImgTitle = $imgTmpTitle . "." . end($tmp);

    console_log($newImgTitle);

    // validation
    if (empty($_FILES["image"]["name"])) {
        $errors["image"] = "Please select an image!";
    } else if ($imgSize > 100) {
        $erros["image"] = "File is to big.";
    }
    if (empty($_POST["title"])) {
        $errors["title"] = "Please enter image's title.";
    } else {
        if (!preg_match("/^([a-zA-Z0-9]+_?)*(\.[a-z0-9]{2,}){1}$/", $newImgTitle)) {
            $errors["title"] = "Please enter a valid title.";
        }
    }

    $conn = getConnection();

    if (!count($errors)) {
        // check if image already exists in DB
        $sql = "SELECT * FROM images WHERE title={$imgTmpTitle} LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows) {
            $errors["image"] = "Image with this name already exists in database!";
            console_log($result->fetch_array()[0]);
        } else {
            // move image to upload dir
            $imgData = file_get_contents(addslashes($imgTmpName));
            $target = "images/" . $newImgTitle;

            if (move_uploaded_file($imgTmpName, $target)) {
                // upload image to db
                $sql = "INSERT INTO images (title, data, mime, size) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $newImgTitle, $imgData, $imgType, $imgSize);

                if ($stmt->execute()) {
                    $successes["image"] = "Image successfully uploaded to database!";
                } else {
                    $errors["image"] = "Database error: Failed to upload image. " . $conn->error;
                    console_log($conn->error);
                }
            } else {
                $errors["image"] = "Failed to move image. " . $conn->error;
                console_log($conn->error);
            }

            if ($conn->close()) {
                console_log("Connection closed!");
            }
        }
    }
}

// when delete button is pressed
if (isset($_POST["delete-btn"])) {
    $conn = getConnection();

    $idsList = $_POST["deleteList"];
    $ids = implode("', '", $idsList);

    $sql = "DELETE FROM images WHERE id IN ('$ids')";

    if ($conn->query($sql)) {
        console_log(sprintf("Image%s with id%s {$ids}, successfully deleted!", (count($idsList) > 1 ? "s" : ""), (count($idsList) > 1 ? "s" : "")));
    };

    if ($conn->close()) {
        console_log("Connection closed!");
    }
}
