<?php  
    use PHPMailer\PHPMailer\PHPMailer;
    require '../vendor/autoload.php';
?>

<?php
    class DefaultMailer {
        private static $instance = null;

        private function __construct()
        {
            $from_addr = "comp0178@126.com"; 
            $password = "BUKHWMBCQFOIEIOF";
            $sender = 'hiyori';

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            
            $mail->Host       = "smtp.126.com";
            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = true;
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->Username   = $from_addr;
            $mail->Password   = $password;
            $mail->setFrom($from_addr, $sender);
            $this->mail = $mail;
        }
        
        public static function get_mailer(){
            if (self::$instance == null)
            {
                self::$instance = new DefaultMailer();
            }
            return self::$instance;
        }

        # the no_action is to prevent spamming the make-up address in reality. Remove it in production !!!
        public function send($to_addr, $recipent, $subject = '', $content = '', $debug = false, $no_action = True){
            if($no_action) return;

            $this->reset();
            $mail = $this->mail;
            $mail->SMTPDebug  = $debug;
            $mail->Subject = $subject;
            $mail->Body    = $content;
            $mail->addAddress($to_addr, $recipent);
            $mail->send();
        }

        private function reset(){
            $this->mail->Subject = null;
            $this->mail->Body    = null;
            $this->mail->SMTPDebug  = 0;
            $this->mail->clearAllRecipients();
        }
    }

    // $mailer = DefaultMailer::get_mailer();
    // $subject = 'Here is the subject';
    // $content = 'This is the HTML message body in bold!';
    // $mailer->send('comp0178@126.com', 'katase', $subject, $content, $debug=true);
    // print 'sent.\n';
?>