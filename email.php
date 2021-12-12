<?php  
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';
    

    foreach (glob("includes/*.php") as $filename) {
        require_once($filename);}
?>
 
<?php
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    
    $mail->Host       = "smtp.126.com";   
    $mail->SMTPDebug  = 1;                  
    $mail->SMTPAuth   = true;                  
    $mail->Port       = 465;     
    $mail->SMTPSecure = 'ssl';               
    $mail->Username   = "comp0178@126.com";
    $mail->Password   = "BUKHWMBCQFOIEIOF";            
    
    $mail->isHTML(true);                       
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
    $mail->setFrom('comp0178@126.com', 'hiyori');
    $mail->addAddress('comp0178@126.com', 'katase');

    $mail->send();
?>