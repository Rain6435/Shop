<?php
session_start();
session_destroy();
function redirect($url) {
    header('Location: '.$url);
    die();
}
echo "You have been logged out.";
redirect('http://localhost/PHP_Project/MainPage/MainPage.html');

?>