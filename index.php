<?php
session_start();
//ini_set('display_errors', 1);
//Error_Reporting(E_ALL & ~E_NOTICE);

include "Db.php";

$db = Db::getInstance();

$products = $db->getAllProducts(); //

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
    <style>
        .container {margin-top: 10px}
    </style>

    <style>
        body {
            font-family: 'Nunito';
        }

        .add_button {
            margin-bottom: 20px;
        }
    </style>
</head>
    <body class="antialiased">
    <div class="container">
        <div class="row justify-content-start">
            <div class="row">
                <h3>Products</h3>
                <div class="col-sm-3">
                    <a class="btn btn-primary add_button" href="add_product.php">Add Product</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="product_table" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="companies_info">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" >#</th>
                            <th class="sorting" >Product Name</th>
                            <th>Product Image</th>
                            <th class="sorting">Date of Addition</th>
                            <th class="sorting">Who added product</th>
                            <th class="sorting">Number of comments</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($products as $product) { ?>
                        <tr role="row" class="odd">
                            <td class="dtr-control sorting_1" tabindex="0"><?php echo $product['id'] ?></td>
                            <td><a href="comment.php?product_id=<?php echo $product['id'] ?>"><?php echo $product['name'] ?></a></td>
                            <td><img src="<?php echo $product['img_path'] ?>" width="50px" height="50px" alt="<?php echo $product['name'] ?>"></td>
                            <td><?php echo $product['created_at'] ?></td>
                            <td><?php echo $product['add_name'] ?></td>
                            <td><?php echo $db->numberOfComments($product['id']) ?></td>
                        </tr>
                        <?php } ?>

                        </tbody>
                        <tfoot>
                        <tr role="row">
                            <th class="sorting_asc" >#</th>
                            <th class="sorting" >Product Name</th>
                            <th>Product Image</th>
                            <th class="sorting">Date of Addition</th>
                            <th class="sorting">Who added product</th>
                            <th class="sorting">Number of comments</th>
                        </tr></tfoot>
                    </table>

                </div>
            </div>
        </div>

    </div>

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.23/datatables.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script>
            $(document).ready( function () {
                $('#product_table').DataTable();
            } );
        </script>
    </body>
</html>

