<?php

require_once "controllers/dbController.php";

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

    <title>Image Upload</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-4 offset-md-4 form-div">
                <form method="post" enctype="multipart/form-data">

                    <h3 class="text-center">Image Upload</h3>

                    <?php
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

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" value="<?php if (isset($title)) {
                                                                    echo $title;
                                                                } ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="upload-btn" class="btn btn-primary btn-block">Upload Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>