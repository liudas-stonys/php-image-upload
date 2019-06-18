<?php

require_once "controllers/dbController.php";

$conn = getConnection();

$imgTmpTitle = "meow.jpg";

// $sql = "SELECT * FROM images WHERE title='meow.jpg'";

$sql = "SELECT * FROM images";
$result = $conn->query($sql);
console_log($result);
$images = $result->fetch_all(MYSQLI_ASSOC);

// $sql = "SELECT id FROM images";
// $result = $conn->query($sql);
// $images = $result->fetch_all(MYSQLI_ASSOC);

console_log($images);

if ($conn->close()) {
    console_log("Connection closed!");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <!-- Page's CSS -->
    <link rel="stylesheet" type="text/css" href="css/index.css">

    <title>Uploaded Images</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-4 offset-md-4 form-div">
                <form action="upImgs" method="post">

                    <?php if (!empty($msg)) : ?>
                        <div class="alert <?php echo $css_class; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <table class="table table-bordered">
                        <thead>
                            <th>Select image</th>
                            <th>Image title</th>
                            <th>Image id</th>
                        </thead>

                        <tbody>
                            <?php if (isset($images)) :
                                foreach ($images as $img) : ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="deleteList[]" value="<?php echo $img["id"]; ?>">
                                        </td>
                                        <td>
                                            <img src="getImg?id=<?php echo $img["id"] ?>" width="169" />
                                        </td>
                                        <td>
                                            <?php echo $img["id"]; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>

                    <button type="submit" name="delete-btn" class="btn btn-primary btn-block">Delete images</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>