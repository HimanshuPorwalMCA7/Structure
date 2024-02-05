<?php
    include_once '../../core/DBConnector.php';
    class AuthClass extends DBController{
        private $user;
        public function __construct() {
            $this->user = null;
           parent::__construct();
        }

        public function verifyLogin($formData){
            $email =$formData['email'];
            $password = $formData['password'];
            $query = "SELECT * FROM " . DB_PREFIX . "_users WHERE email = :email AND password = :password";
            $params = array(':email'=> $email , ':password'=>$password);
            $stmt = $this->runQuery($query,$params);
            $users= $this->fetchAllRows($stmt);
            if(!empty($users)){
                $this->user = [
                        'id'=> $users[0]['id'],
                        'firstname'=> $users[0]['firstname'],
                        'lastname'=> $users[0]['lastname'],
                        'email'=> $users[0]['email'],
                        
                        
                ];
            }
        }

        public function createSession(){
            $_SESSION['authUser']=$this->user;
        }

        public function getCurrentUser(){
            return $this->user;
        }
    }

   