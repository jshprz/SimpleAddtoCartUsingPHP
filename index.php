<?php 
include_once("connection.php");
session_start();
if($_GET['action'] == "add")
{
    if(isset($_SESSION['cart']))
    {
        $item_array_id = array_column($_SESSION['cart'],"id");
        if(!in_array($_GET['id'],$item_array_id))
        {
            $count = count($_SESSION['cart']);
            $items_array = array(
                'id' => $_GET['id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_quantity' => $_POST['quantity']
            );
            $_SESSION['cart'][$count] = $items_array;
        }
        else
        {
            echo "<script>alert('This item is already in the cart')</script>";
            echo "<script>window.location='index.php'</script>";
        }
    }
    else
    {
        $items_array = array(
            'id' => $_GET['id'],
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price'],
            'product_quantity' => $_POST['quantity']
        );
        $_SESSION['cart'][0] = $items_array;
    }
}
if($_GET['action'] == "remove")
{
    foreach($_SESSION['cart'] as $key => $value)
    {
        if($_GET['id'] == $value['id'])
        {
            unset($_SESSION['cart'][$key]);
            echo '<script>alert("1 item in the cart has been removed")</script>';
            echo '<script>window.location="index.php"</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        #products{
            border:1px solid blue;
            backgroud-color: #f1f1f1;
            border-radius: 10px;
        }
        table,th,td{
            text-align:center;
        }
    </style>
</head>
<body>
<br>
<div class="container" style="width:65%;">
<h3 align="center">Simple Cart</h3>

<?php
$query = "SELECT * FROM products ORDER BY id ASC";
$result = mysqli_query($con,$query);
if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_array($result))
    {
 ?>
        <div class="col-md-3">
            <form action="index.php?action=add&id=<?=$row['id']?>" method="post">
                <div style="border:1px solid #f1f1f1; background-color:#f1f1f1; border-radius:10px; padding:10px 10px 10px 30px;">
                    <img src="<?=$row['product_image']?>" class="img-responsive" width="100px"/>
                    <h5><?=$row['product_name']?></h5>
                    <h5>$<?=$row['product_price']?></h5>
                    <input type="number" name="quantity" value="1" required/>
                    <input hidden name="product_name" value="<?=$row['product_name']?>"/>
                    <input hidden name="product_price" value="<?=$row['product_price']?>"/><br>
                    <br>
                    <input type="submit" class="btn btn-success" name="add-cart" value="Add to Cart"/>
                </div>
            </form>
        </div>       
<?php
    }
}
?>

<table class="table table-bordered">
<tr>
    <th>Product Name</th>
    <th>Product Price</th>
    <th>Product Quantity</th>
    <th>Total Price</th>
    <th>Remove Item</th>
</tr>
<?php
if(!empty($_SESSION['cart']))
{
    $total = 0;
    foreach($_SESSION['cart'] as $key => $value)
    {
?>
<tr>
    <td><?=$value['product_name']?></td>
    <td><?=$value['product_price']?></td>
    <td><?=$value['product_quantity']?></td>
    <td><?=number_format($value['product_quantity'] * $value['product_price'],2)?></td>
    <td><a href="index.php?action=remove&id=<?=$value['id']?>">remove</a></td>
</tr>
<?php
$total = $total + ($value['product_quantity'] * $value['product_price']);
?>

<?php
    }
}
?>
</table>
<h3>Total Amount: <?=number_format($total,2)?></h3>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>