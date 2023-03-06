<?php

session_start();
$s = mysqli_connect("localhost", "root", "");
$user_id = $_SESSION['user_id'];
mysqli_select_db($s, "magasin_vin");
$cart = [];

if (isset($_POST['update_cart'])) {
    $quantity = $_POST['quantity'];
    $product_id = $_POST['productID'];
    mysqli_query($s, "UPDATE `cart` SET Quantity = '$quantity' WHERE ProductID = '$product_id' and UserID = '$user_id' and State='In cart'") or die('query failed');
?>
    <script>
        alert("cart quantity updated successfully!");
    </script>
<?php
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($s, "DELETE FROM `cart` WHERE ProductID = '$remove_id' and UserID = '$user_id'") or die('query failed');
    header('location:Cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($s, "DELETE FROM `cart` WHERE UserID = '$user_id'") or die('query failed');
    header('location:Cart.php');
}
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
if (isset($_GET['confirm'])) {
    if ($_GET['total'] != 0) {
        $p = $_GET['confirm'];
        $u = $_GET['total'];
        $z = explode(";", $_GET['confirm']);
        for ($i = 0; $i < count($z); $i++) {
            mysqli_query($s, "UPDATE `cart` set State='Purchased' where ProductID = '$z[$i]' and UserID = '$user_id' and State='In Cart' ");
        }

        $r = mysqli_query($s, "SELECT * from `cart` where UserID = '$user_id' and State='Purchased'");
        $r = mysqli_fetch_all($r);
        if (count($r) == 0) {
            debug_to_console('not null!');
        } else {
            if (count($r) > 1) {
                $o = [];
                foreach ($r as $x) {
                    array_push($o, $x[0]);
                }
                $o = implode(";", $o);
                mysqli_query($s, "INSERT into `orders` (UserID,Total,CartIDs) values ('$user_id','$u','$o') ") or die('query failed');
                $que = mysqli_query($s, "SELECT Quantity,ProductID from `cart` where UserID = '$user_id' and State='Purchased'");
                foreach ($r as $x) {
                    mysqli_query($s, "UPDATE `cart` set State='Sold' where ID = '$x[0]' and UserID = '$user_id' ");
                }
                $que = mysqli_fetch_all($que);
                foreach ($que as $j) {
                    mysqli_query($s, "UPDATE `products` set Quantity = Quantity - '$j[0]' where ID = '$j[1]'");
                }
            } else {
                //mysqli_query($s, "INSERT into `orders` (UserID,Total,CartIDs) values ('$user_id','$u','$r')") or die('query failed');
            }
        }

        //header('location:Cart.php');
    } else {
        echo 'Cart empty!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>

<body>
    <div id="menu">
        <a href="/PHP_Project/MainPage/MainPage.html">Main page</a>
        <a href="/PHP_Project/ProductManagement/Products.php" onclick="return myFunction()"">Products</a>
        <a href=" /PHP_Project/UserLogIn/UserLogIn.php">Log In/Sign Up</a>
        <a id="logOut" href="/PHP_Project/UserLogIn/UserLogOut.php">Log Out</a>
    </div>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>

    <div>

        <h1>Shopping cart</h1>
        <div id="main">
            <table>
                <thead>
                    <th></th>
                    <th id="domain">Domain</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>
                        <?php $user_id ?>
                    </th>
                </thead>
                <tbody>
                    <?php
                    $cart_query = mysqli_query($s, "SELECT * FROM `cart` WHERE UserID = '$user_id' and State = 'In cart'") or die('query failed');
                    $grand_total = 0;

                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                            $l = $fetch_cart['ProductID'];
                            $query = mysqli_query($s, "SELECT * FROM `products` WHERE ID = '$l'") or die('query failed');
                            $product = mysqli_fetch_assoc($query);
                            array_push($cart, $product['ID']);
                    ?>
                            <tr>
                                <td><img src="<?php $product['Image'] ?>"></td>
                                <td>
                                    <?php echo $product['Domain']; ?>
                                </td>
                                <td>$
                                    <?php echo $product['Price']; ?>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="productID" value="<?php echo $product['ID'] ?>">
                                        <input type="number" min="1" name="quantity" value="<?php echo $fetch_cart['Quantity']; ?>">
                                        <input type="submit" name="update_cart" value="update"">
               </form>
            </td>
            <td>$<?php echo $sub_total = ($product['Price'] * $fetch_cart['Quantity']); ?></td>
            <td><a href=" Cart.php?remove=<?php echo $fetch_cart['ProductID']; ?>" class="delete-btn" onclick="return
                                confirm('remove item from cart?');">remove</a>
                                </td>
                            </tr>
                    <?php
                            $grand_total += $sub_total;
                            $grand_total *= 1.15;
                            $grand_total = round($grand_total, 2);
                        }
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
                    }
                    ?>
                </tbody>
            </table>

        </div>
        <div id="bottom">
            <div id="botComp">
                <div>Grand total avec taxes :
                    <?php echo $grand_total; ?>$
                </div>
                <div>
                    <a href="Cart.php?delete_all" onclick="return confirm('delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>"> Delete all</a>
                </div>
            </div>
            <div id="button">
                <button id="checkout" type="button" onclick="return checkout();">Checkout</button>
            </div>
            <div class="popup">
                <?php
                $cart_query = mysqli_query($s, "SELECT * FROM `client` WHERE ID = '$user_id'") or die('query failed');
                $client = mysqli_fetch_assoc($cart_query);
                ?>
                <button id="close">&times;</button>
                <h2>Order Confirmation</h2>
                <table style="width:100%">
                    <tr>
                        <th>First Name</th>
                        <td>
                            <?php echo $client['FirstName'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>
                            <?php echo $client['LastName'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <?php echo $client['EMail'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>
                            <?php echo ($client['AdressNum'] . " " . $client['AdressName'] . " " . $client['AdressZIP']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>
                            <?php echo $grand_total; ?>$
                        </td>
                    </tr>
                </table>
                <a href="Cart.php?confirm=<?php echo implode(" ;", $cart); ?>&total=<?php echo $grand_total; ?>">Confirm purchase</a>
            </div>
        </div>

        <!--Script-->
        <script type="text/javascript">
            function checkout() {
                setTimeout(
                    function open(event) {
                        document.querySelector(".popup").style.display = "block";
                        document.querySelector("#main").style.display = "none";
                        document.querySelector("#botComp").style.display = "none";
                        document.querySelector("#button").style.display = "none";
                    },
                    2
                )
            }


            document.querySelector("#close").addEventListener("click", function() {
                document.querySelector(".popup").style.display = "none";
                document.querySelector("#main").style.display = "flex";
                document.querySelector("#botComp").style.display = "block";
                document.querySelector("#button").style.display = "block";
            });
        </script>
</body>

</html>