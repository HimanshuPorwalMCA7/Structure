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

        public function updateStage($stage) {
            $id=$_SESSION['authUser']['id'];
            $query = 'UPDATE nexgi_users SET stage = :stage WHERE id = :id';
            $param = array(':stage' => $stage, ':id' => $id );
            $stmt = $this->insertQuery($query, $param);
            
            if ($stmt) {
                $_SESSION['auth_user']['stage'] = $stage;
                return true;
            } else {
                return false;
            }
        }
        
        
        
        
        public function basic_details($formData){
            

            $mother_name =$formData['mother_name'];
            $father_name = $formData['father_name'];
            $dob=$formData['dob'];
            $location=$formData['location'];
            $user_id=$_SESSION['authUser']['id'];
           
            $queryw="select count(user_id) from nexgi_basic_details where user_id = :user_id ";
            $params = array(':user_id'=>$user_id);
            $stmt=$this->insertQuery($queryw,$params);
            $count=$this->fetchAllRows($stmt);
            if($count>0)
            {
                $query = "UPDATE " . DB_PREFIX . "_basic_details SET mother_name = :mother_name, father_name = :father_name, dob = :dob, location = :location WHERE user_id = :user_id";
                $params = array(':user_id'=>$user_id,':mother_name' => $mother_name, ':father_name' => $father_name, ':dob' => $dob, ':location' => $location);
            }
            else{
                $query = "insert into " .DB_PREFIX. "_basic_details(user_id,mother_name,father_name,dob,location) values (:user_id,:mother_name,:father_name,:dob,:location)";
                $params = array(':user_id'=>$user_id,':mother_name' => $mother_name, ':father_name' => $father_name, ':dob' => $dob, ':location' => $location);
            }
            
            $location = $_POST['location'];
            if ($location === "India") {
                header('location:../dashboard/adhar_pan.php');
                $_SESSION['basic_details'] = array(
                    'mother_name' => $_POST['mother_name'],
                    'father_name' => $_POST['father_name'],
                    'dob' => $_POST['dob'],
                    'location' => $_POST['location'],

                );
                return $this->insertQuery($query,$params);
                
            } else {
                
                header('location:../dashboard/education.php');
                $_SESSION['basic_details'] = array(
                    'mother_name' => $_POST['mother_name'],
                    'father_name' => $_POST['father_name'],
                    'dob' => $_POST['dob'],
                    'location' => $_POST['location']
                );
                return $this->insertQuery($query,$params);
                
            }
        }
        
        


        public function aadhar_pan_details($formData) {
            $aadhar_card = $formData['aadhar_card'];
            $pan_card = $formData['pan_card'];
            $user_id=$_SESSION['authUser']['id'];
           
            $queryw="select count(user_id) from nexgi_adhar_pan_details where user_id = :user_id ";
            $params = array(':user_id'=>$user_id);
            $stmt=$this->insertQuery($queryw,$params);
            $count=$this->fetchAllRows($stmt);
            if($count>0)
            {
                $query = "UPDATE " . DB_PREFIX . "_adhar_pan_details SET aadhar_card = :aadhar_card, pan_card = :pan_card WHERE user_id = :user_id";
                $params = array(':aadhar_card' => $aadhar_card, ':pan_card' => $pan_card, ':user_id'=>$user_id);

            }
            else{
                $query = "INSERT INTO " . DB_PREFIX . "_adhar_pan_details (user_id,aadhar_card, pan_card) VALUES (:user_id,:aadhar_card, :pan_card)";
                $params = array('user_id'=>$user_id,':aadhar_card' => $aadhar_card, ':pan_card' => $pan_card);
            }
           
            header('location:../dashboard/education.php');
            $_SESSION['aadhar_pan_details'] = array(
                'aadhar_card' => $_POST['aadhar_card'],
                'pan_card' => $_POST['pan_card'],
                
                
            );
            return $this->insertQuery($query, $params);
        }
    
        public function education_details($formData) {
            $tenth = $formData['tenth'];
            $twelfth = $formData['twelfth'];
            $graduation = $formData['graduation'];
            $user_id=$_SESSION['authUser']['id'];

            $queryw="select count(user_id) from nexgi_education_details where user_id = :user_id ";
            $params = array(':user_id'=>$user_id);
            $stmt=$this->insertQuery($queryw,$params);
            $count=$this->fetchAllRows($stmt);
            if($count>0)
            {
                $query = "UPDATE " . DB_PREFIX . "_education_details SET tenth = :tenth, twelfth = :twelfth, graduation = :graduation WHERE user_id = :user_id";
                $params = array(':tenth' => $tenth, ':twelfth' => $twelfth, ':graduation' => $graduation, ':user_id' => $user_id);

            }
            else{
                $query = "INSERT INTO " . DB_PREFIX . "_education_details (user_id,tenth, twelfth,graduation) VALUES (:user_id,:tenth, :twelfth,:graduation)";
                $params = array('user_id'=>$user_id,':tenth' => $tenth, ':twelfth' => $twelfth,':graduation'=>$graduation);
            }
           
            header('location:../dashboard/final.php');
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $_SESSION['education_details'] = array(
                    'tenth' => $_POST['tenth'],
                    'twelfth' => $_POST['twelfth'],
                    'graduation' => $_POST['graduation']
                );
            return $this->insertQuery($query, $params);
        }

    }



    

        // public function final_submission($formData){
        // $user_id = $_SESSION['authUser']['id'];
        // $mother_name = $formData['mother_name'];
        // $father_name = $formData['father_name'];
        // $dob = $formData['dob'];
        // $location = $formData['location'];
        // $aadhar_card = $formData['aadhar_card'] ?? NULL;
        // $pan_card = $formData['pan_card'] ?? NULL;
        // $tenth = $formData['tenth'];
        // $twelfth = $formData['twelfth'];
        // $graduation = $formData['graduation'];
        // $query = "INSERT INTO " . DB_PREFIX . "_reviewed_information (user_id, mother_name, father_name, dob, location, aadhar_card, pan_card, tenth, twelfth, graduation) VALUES (:user_id, :mother_name, :father_name, :dob, :location, :aadhar_card, :pan_card, :tenth, :twelfth, :graduation)";
        // $params = array(':user_id' => $user_id, ':mother_name' => $mother_name, ':father_name' => $father_name, ':dob' => $dob, ':location' => $location, ':aadhar_card' => $aadhar_card, ':pan_card' => $pan_card, ':tenth' => $tenth, ':twelfth' => $twelfth, ':graduation' => $graduation);
        // header('location:../dashboard/thankyou.php');
        
        // return $this->insertQuery($query, $params);
        // }
    


        

        public function notifyToAdmin(){

        }


        public function notifyToUser(){
            
        }
    }
?>