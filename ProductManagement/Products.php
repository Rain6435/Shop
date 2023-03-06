<?php

session_start();
$s = mysqli_connect("localhost", "root", "");
$user_id = $_SESSION['user_id'];
mysqli_select_db($s, "magasin_vin");

if(!isset($user_id)){
   header('location:http://localhost/PHP_Project/UserLogIn/UserLogIn.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:http://localhost/PHP_Project/UserLogIn/UserLogIn.php');
};

if(isset($_POST['add_to_cart'])){

   $product_id = $_POST['id'];
   $query = mysqli_query($s, "SELECT * FROM `products` WHERE id = '$product_id'") or die('query failed');
   $checkIfInCart = mysqli_query($s, "SELECT * FROM `cart` WHERE ProductID = '$product_id' and UserID='$user_id' and State = 'In Cart'") or die('query failed');
   $row = mysqli_fetch_assoc($query);
   $b = $row['ID'];

   if(mysqli_num_rows($checkIfInCart) > 0){
      $message[] = 'product already added to cart!';
   }else{
      mysqli_query($s, "INSERT INTO `cart`(UserID, ProductID, Quantity,State) VALUES('$user_id', '$b' ,'1','In cart')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="products.css">

</head>
<body>
<div id="menu">
        <a href="/PHP_Project/MainPage/MainPage.html">Main page</a>
        <a href="/PHP_Project/ProductManagement/Products.php">Products</a>
        <a href="/PHP_Project/UserLogIn/UserLogIn.php">Log In/Sign Up</a>
        <a id="logOut" href="/PHP_Project/UserLogIn/UserLogOut.php">Log Out</a>
    </div>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div id="main">
   <div class="products">

   <div class="box-container">

   <?php
      $products = mysqli_query($s, "SELECT * FROM `products`") or die('query failed');
      if(mysqli_num_rows($products) > 0){
         while($fetch_products = mysqli_fetch_assoc($products)){
   ?>
      <form method="post" class="box" action="" id="form">
         <img src="<?php echo $fetch_products['Image'] ?>">
         <div id="domain" style="max-width:400px;"><?php echo $fetch_products['Domain']; ?></div>
         <div id="price">$<?php echo $fetch_products['Price']; ?></div>
         <input type="hidden" name="id" value="<?php echo $fetch_products['ID']; ?>">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
   <?php
      };
   };
   ?>
   </div>
   <div id="cart">
      <img src="https://static.vecteezy.com/system/resources/thumbnails/004/798/846/small/shopping-cart-logo-or-icon-design-vector.jpg">
      <a href="http://localhost/PHP_Project/Cart/Cart.php">Cart</a>
   </div>

</div>




</div>
</div>

</body>
</html>