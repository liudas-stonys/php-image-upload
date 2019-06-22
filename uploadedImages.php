<?php

require_once "controllers/dbController.php";

$conn = getConnection();

$imgTmpTitle = "meow.jpg";

// $sql = "SELECT * FROM images WHERE name='calibration.png'";

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
            <div class="col-8 offset-2 form-div">
                <form action="upImgs" method="post">

                    <?php if (!empty($msg)) : ?>
                        <div class="alert <?php echo $css_class; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif;

                if (count($errors)) : ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif;

                if (count($successes)) : ?>
                        <div class="alert alert-success">
                            <?php foreach ($successes as $success) : ?>
                                <li><?php echo $success; ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <table class="table table-bordered">
                        <thead>
                            <th>Select image</th>
                            <th>Image</th>
                            <th>Image id</th>
                        </thead>

                        <tbody>
                            <?php if (isset($images)) :
                                foreach ($images as $img) : ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="deleteList[]" value="<?php echo $img["name"]; ?>">
                                        </td>
                                        <td>
                                            <img src="getImg?id=<?php echo $img["id"] ?>" width="100%" />
                                        </td>
                                        <td>
                                            <?php echo $img["name"]; ?>
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