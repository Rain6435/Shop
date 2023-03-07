<?php
if(session_unset()){
    echo "You must be logged in to log out!";
    echo "Please Log In";
}else{
    session_start();
    session_destroy();
    function redirect($url) {
        header('Location: '.$url);
        die();
    }
    echo "You have been logged out.";
    redirect('http://localhost/PHP_Project/MainPage/MainPage.html');
}
?>