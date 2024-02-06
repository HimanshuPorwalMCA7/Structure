<?php
    include_once '../../core/DBConnector.php';
    class UserClass extends DBController{
        private $user;
        public function __construct() {
            $this->user = null;
           parent::__construct();
        }

        public function getUserByEmail($email)
        {
            $query = "SELECT * FROM " .DB_PREFIX. "_users WHERE email = :email";
            $params = array(':email'=> $email);
            $stmt = $this->runQuery($query,$params);
            $users= $this->fetchAllRows($stmt);
            return $users;
        }

        public function createUser($formData){
            $full_name =$formData['full_name'];
            $full_name =explode(' ',$full_name);
            $first_name= $full_name[0];
            $last_name=isset($full_name[1])? $full_name[1] : null;

            $email =$formData['email'];
            $password = $formData['password'];
            $query = "insert into " .DB_PREFIX. "_users (status, email,first_name,last_name,password) values (:status, :email,:first_name,:last_name,:password)";
            $params = array(':status'=>2,':email'=> $email ,':first_name'=>$first_name,':last_name'=>$last_name, ':password'=>$password);
            return $this->insertQuery($query,$params);
            

        }
    }
        ?>