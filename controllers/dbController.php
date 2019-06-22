<?php

require_once "config/config.php";
require_once "utils/functions.php";

console_log("Memory limit: " . ini_get("memory_limit"));
console_log("Post limit: " . ini_get("post_max_size"));
console_log("Max filesize: " . ini_get("upload_max_filesize"));
console_log("Display errors: " . ini_get("display_errors"));

$phpFileUploadErrors = array(
    0 => "There is no error, the file uploaded with success",
    1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2 => "The uploaded file exceeds the max_file-size directive that was specified in the HTML form",
    3 => "The uploaded file was only partially uploaded",
    4 => "No file was uploaded",
    6 => "Missing a temporary folder",
    7 => "Failed to write file to disk",
    8 => "A PHP extension stopped the file upload"
);

$errors = array();
$successes = array();

// when upload button is pressed
if (isset($_POST["upload-btn"])) {

    // validation
    if (empty($_FILES["images"]["name"][0])) {
        $errors["image"] = "Please select an image!";
        // } else if ($imgSize > 100) {
        //     $erros["image"] = "File is to big.";
        // }
        // if (empty($_POST["title"])) {
        //     $errors["title"] = "Please enter image's title.";
        // } else {
        //     if (!preg_match("/^([a-zA-Z0-9]+_?)*(\.[a-z0-9]{2,}){1}$/", $newImgTitle)) {
        //         $errors["title"] = "Please enter a valid title.";
        //     }
    }

    if (empty($errors)) {
        $file_array = reArrayFiles($_FILES["images"]);
        // pre_r($file_array);

        $conn = getConnection();

        foreach ($file_array as $file) {
            if ($file["error"]) {
                array_push($errors, $file["name"] . " - " . $phpFileUploadErrors[$file["error"]]);
            } else {
                $imgData = file_get_contents(addslashes($file["tmp_name"]));

                // check if image already exists in DB
                $sql = "SELECT * FROM images WHERE name='{$file["name"]}' LIMIT 1";
                $result = $conn->query($sql);

                if ($result->num_rows) {
                    array_push($errors, $file["name"] . " - Image with this name already exists in database!");
                } else {

                    // move image to upload dir
                    $extensions = array("jpeg", "jpg", "png", "gif");
                    $file_ext = explode(".", $file["name"]);
                    $file_ext = end($file_ext);

                    if (!in_array($file_ext, $extensions)) {
                        array_push($errors, $file["name"] . " - Invalid file extension!");
                    } else if (move_uploaded_file($file["tmp_name"], "images/" . $file["name"])) {

                        // upload image to db
                        $sql = "INSERT INTO images (data, name, mime, size) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssi", $imgData, $file["name"], $file["type"], $file["size"]);

                        if ($stmt->execute()) {
                            array_push($successes, $file["name"] . " - " . $phpFileUploadErrors[$file["error"]]);
                        } else {
                            array_push($errors, $file["name"] . " - Database error: failed to upload image. " . $conn->error);
                        }
                    } else {
                        array_push($errors, $file["name"] . " - Failed to move image. " . $conn->error);
                    }
                }
            }
        }
    }
}

// when delete button is pressed
if (isset($_POST["delete-btn"])) {

    if (empty($_POST["deleteList"])) {
        $errors["delete"] = "Please select an images to delete!";
    }

    if (empty($errors)) {
        $conn = getConnection();

        $delList = $_POST["deleteList"];
        foreach ($delList as $img) {
            unlink("images/{$img}");
        }

        $delImgs = implode("', '", $delList);
        $sql = "DELETE FROM images WHERE name IN ('$delImgs')";

        if ($conn->query($sql)) {
            $successes["delete"] = sprintf("Image%s '{$delImgs}' deleted successfully!", (count($delList) ? "s" : ""));
        } else {
            $errors["delete"] = sprintf("Failed to delete image%s '{$delImgs}'! {$conn->error}", (count($delList) ? "s" : ""));
        }

        if ($conn->close()) {
            console_log("Connection closed!");
        }
    }
}
