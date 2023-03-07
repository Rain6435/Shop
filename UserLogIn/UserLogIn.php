<?php
session_start();
function redirect($url)
{
    header('Refresh:2; URL='. $url);
    die();
}

if (isset($_SESSION['user_id'])) {
    echo 'Already logged in.<br>';
    echo 'Please log out to change accounts.';
    redirect('http://localhost/PHP_Project/ProductManagement/Products.php');
} else {
    $s = mysqli_connect("localhost", "root", "");

    $existing = false;

    if (isset($_POST['email'], $_POST['password'])) {
        if ($s->connect_error) {
            die("Connection failed: " . $s->connect_error);
        }
        try {
            mysqli_select_db($s, "wineshop");
            $query = mysqli_query($s, "select * from client where EMail ='" . $_POST['email'] . "';");
            $row = mysqli_fetch_assoc($query);
            if (empty($row)) {
                session_abort();
                $existing = false;
            } else if (password_verify($_POST['password'], $row['Password']) == false) {
                $existing = false;
            } else {
                $existing = true;
                $_SESSION['user_id'] = $row['ID'];
            }
            mysqli_close($s);
        } catch (Exception $e) {
            echo 'Connection did not succeed', $e->getMessage();
        }
        if ($existing == true) {
            redirect('http://localhost/PHP_Project/ProductManagement/Products.php');
        } else {
            echo ("<p style='   display: flex;
            justify-content: center; /* center horizontally */
            align-items: center; /* center vertically */
            text-align: center;
            margin:2em;'> Wrong password or email. Please go back to log in page and try again. Thank you! </p>");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<HEAD>
    <TITLE>Shopping</TITLE>
    <link href="login.css" type="text/css" rel="stylesheet" />
</HEAD>
<main>
    <div id="menu">
        <a href="/PHP_Project/MainPage/MainPage.html">Main page</a>
        <a href="/PHP_Project/ProductManagement/Products.php">Products</a>
        <a href="/PHP_Project/UserLogIn/UserLogIn.php">Log In/Sign Up</a>
    </div>

    <h1>Log In</h1>

    <form id="UserLogInForm" method="POST">
        <div id="formElement">
            <label>Email</label>
            <input id="firstName" type="email" name="email" required>
        </div>

        <div id="formElement">
            <label>Password</label>
            <input id="password" type="password" name="password" maxlength="15" required>

        </div>
        <input type="submit" value="Log In" name="submit">
        <div>
            <p>If you don't have an account with us, sign up <a href="http://localhost/PHP_Project/UserManagement/UserCreation.html">here</a></p>
        </div>
    </form>
</main>