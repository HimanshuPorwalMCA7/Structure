<?php




require_once __DIR__.'/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
class Emailer{
    public function sendEmail($subject ,$body){
        $mailObject = new PHPMailer();
        // configure an SMTP
        $mailObject->isSMTP();
        $mailObject->Host = 'sandbox.smtp.mailtrap.io';
        $mailObject->SMTPAuth = true;
        $mailObject->Username = '6661277bd1e2b8';
        $mailObject->Password = '2fa80e2a9e8b69';
        $mailObject->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailObject->Port = 2525;
        $mailObject->setFrom('confirmation@hotel.com', 'Your Hotel');
        $mailObject->addAddress('me@gmail.com', 'Me');
        $mailObject->Subject = $subject;
        // Set HTML 
        $mailObject->isHTML(TRUE);
        $mailObject->Body =$body;
          

        return $mailObject->send(); 
            }
        }
