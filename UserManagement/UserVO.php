<?php class UserVO{
    public $fName;
    public $lName;
    public $age;
    public $email;
    public $addNum;
    public $addName;
    public $addZIP;
    public $phoneNum;
    public $sex;
    public $password;

    protected $ID;

    public function setUser($fName,$lName,$age,$email,$AddNum,$AddName,$AddZIP,$PhoneNum,$Sex,$Password){
        $this->fName = $fName;
        $this->lName = $lName;
        $this->age = $age;
        $this->email = $email;
        $this->addNum = $AddNum;
        $this->addName = $AddName;
        $this->addZIP = $AddZIP;
        $this->phoneNum = $PhoneNum;
        $this->sex = $Sex;
        $this->password = $Password;
    }
    public function setID($ID){
        $this->ID = $ID;
    }
    public function getUser(){
        return $this->ID;
    }
}