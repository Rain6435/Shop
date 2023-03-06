<?php
include("UserVO.php");

$uservo = new UserVO();

$s = mysqli_connect("localhost", "root", "");


if (isset($_POST['fName'], $_POST['lName'], $_POST['age'], $_POST['email'], $_POST['addNum'], $_POST['addName'], $_POST['addZIP'], $_POST['phoneNum'], $_POST['sex'],$_POST['password'])) {
    $encrPswd;
    $bday = new DateTime($_POST['age']);
    $today = new Datetime(date('Y-m-d'));
    $diff = $today->diff($bday);
    $uservo->setUser($_POST['fName'], $_POST['lName'], $diff->y , $_POST['email'], $_POST['addNum'], $_POST['addName'], $_POST['addZIP'], $_POST['phoneNum'], $_POST['sex'], password_hash($_POST['password'],PASSWORD_DEFAULT));
    if ($s->connect_error) {
        die("Connection failed: " . $s->connect_error);
    }
    if($diff->y >18){
        try {
            mysqli_select_db($s, "magasin_vin");
            mysqli_query($s, "insert into client(FirstName,LastName,Age,EMail,AdressNum,AdressName,AdressZIP,PhoneNum,Sex,Password) values('$uservo->fName','$uservo->lName','$uservo->age','$uservo->email','$uservo->addNum','$uservo->addName','$uservo->addZIP','$uservo->phoneNum','$uservo->sex','$uservo->password');");
            mysqli_close($s);
        } catch (Exception $e) {
            echo 'Connection did not succeed', $e->getMessage();
        }
        echo 'Your account has been created. ';
        echo 'Please log in <a href="http://localhost/PHP_Project/ProductManagement/Products.php">now</a>.';
    }else{
        echo 'You must be 18 years old to log in. Please go back to the user creation page.';
    }
    
}



?>