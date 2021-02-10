<?php
session_start();
//ini_set('display_errors', 1);
//Error_Reporting(E_ALL & ~E_NOTICE);

include "Db.php";

$db = Db::getInstance();
$product_id = $_REQUEST['product_id'];
$product = $db->getOneProduct($_REQUEST['product_id']);
$comments =$db->getComments($_REQUEST['product_id']);
$avgRating = $db->averageRating($_REQUEST['product_id']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Netpeak Test</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!--Datatables-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.23/datatables.min.css"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    </style>

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
            <h3>Comments</h3>
            <div class="col-sm-3">
                <a class="btn btn-primary add_button" href="index.php">Back to Products</a>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h5><?php echo $product['name'] ?></h5>
                    <img src="<?php echo $product['img_path'] ?>" width="200px" height="200px" alt="<?php echo $product['name'] ?>">
                    <h6>Average rating - <?php echo $avgRating ? $avgRating : 'no ratings yet' ?></h6>
                </div>
                <div class="col-sm-12">
                    <table id="comments_table" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="companies_info">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" >#</th>
                            <th class="sorting" >Name who left comment</th>
                            <th class="sorting">Rating</th>
                            <th class="sorting">Comment</th>
                            <th class="sorting">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($comments as $comment) { ?>
                            <tr role="row" class="odd">
                                <td class="dtr-control sorting_1" tabindex="0"><?php echo $comment['id'] ?></td>
                                <td><?php echo $comment['add_name'] ?></a></td>
                                <td><?php echo $comment['rating'] ?></td>
                                <td><?php echo $comment['text'] ?></td>
                                <td><?php echo $comment['created_at'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr role="row">
                            <th class="sorting_asc" >#</th>
                            <th class="sorting" >Name who left comment</th>
                            <th class="sorting">Rating</th>
                            <th class="sorting">Comment</th>
                            <th class="sorting">Date</th>
                        </tr></tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <h3>Add new comment</h3>
            <?php
            if (isset($_SESSION['message']) && $_SESSION['message'])
            {
                printf('<b>%s</b>', $_SESSION['message']);
                unset($_SESSION['message']);
            }
            ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="add_name">Name who added comment</label>
                    <input type="text" class="form-control" id="add_name" name="add_name" required>
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <input type="number" class="form-control" min="1" max="10" id="rating" name="rating" placeholder="Rating from 1 to 10" required>
                </div>
                <div class="input-group">
                    <label for="text">Comment</label>
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="text" name="text"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary submit_button">Submit</button>
                </div>
            </form>
        </div>

    </div>

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.23/datatables.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script>
            $(document).ready( function () {
                $('#comments_table').DataTable();
            } );
        </script>
    </body>
</html>

<?php
    $message_error = ''; //message error
    $message_ok = '';
    $error = false;
    if(isset($_POST['add_name'])) {
        $data = array_map('strip_tags', $_POST);
        $data = array_map('trim', $data);

        $db->insertComment($product_id, $data['rating'], $data['add_name'], $data['text']);
        $message = 'Comment saved';
    } else {
        $error = true;
        $message_error = 'Fill the fields';
    }

    if($error) {
        $_SESSION['message'] = $message_error;
    } else {
        $_SESSION['message'] = $message_ok;
    }

?>

