<?php
session_start();
//ini_set('display_errors', 1);
//Error_Reporting(E_ALL & ~E_NOTICE);

include "Db.php";

$db = Db::getInstance();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Netpeak Test</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito';
        }
        .container, .submit_button {margin-top: 10px}
    </style>
</head>
    <body class="antialiased">
    <div class="container">
        <div class="row justify-content-start">
            <div class="row">
                <h3>Add new product</h3>
                <div class="col-sm-3">
                    <a class="btn btn-primary add_button" href="index.php">Back to Products</a>
                </div>
                <?php
                    if (isset($_SESSION['message']) && $_SESSION['message'])
                    {
                    printf('<b>%s</b>', $_SESSION['message']);
                    unset($_SESSION['message']);
                    }
                ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="img">Choose Image</label>
                            <input type="file" class="form-control" id="img" name="img">
                        </div>
<!--                        Or-->
<!--                        <div class="form-group">-->
<!--                            <label for="path">Insert Image Path</label>-->
<!--                            <input type="text" class="form-control" id="path" name="path">-->
<!--                        </div>-->
                        <div class="form-group">
                            <label for="price">Average Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" class="form-control" id="price" name="date" disabled value="<?php echo date('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label for="add_name">Name who added product</label>
                            <input type="text" class="form-control" id="add_name" name="add_name" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary submit_button">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    </body>
</html>

<?php
    $message = '';
    $error = false;
    if(isset($_POST['name'])) {
        $data = array_map('strip_tags', $_POST);
        $data = array_map('trim', $data);

        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['img']['tmp_name'];
            $fileName = $_FILES['img']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // sanitize file-name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // check if file has one of the following extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png');

            if (in_array($fileExtension, $allowedfileExtensions)) {
                // directory in which the uploaded file will be moved
                $uploadFileDir = './img/';
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777);
                }
              $dest_path = $uploadFileDir . $newFileName;

              if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $data['img_path'] = $dest_path;
                $db->insertProduct($data['name'], $data['img_path'], $data['add_name'], $data['price']);
              } else {
                $error = true;
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
              }

            } else {
                $error = true;
                $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            }
        } else {
            $error = true;
            $message = 'There is some error in the file upload. Please check the following error.<br>';
            $message .= 'Error:' . $_FILES['img']['error'];
        }

        if($error) {
            $_SESSION['message'] = $message;
        } else {
            header("Location: index.php");
        }


    }


?>

