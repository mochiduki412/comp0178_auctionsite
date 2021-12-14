<?php  
    use PHPMailer\PHPMailer\PHPMailer;
    require  'vendor/autoload.php';
?>

<?php
    class Mailer {
        private static $instance = null;

        private function __construct($develop)
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
            $this->develop = $develop;
        }
        
        public static function get_mailer($develop = false){
            if (self::$instance == null)
            {
                self::$instance = new Mailer($develop = $develop);
            }
            return self::$instance;
        }

        public function send($to_addr, $recipent, $subject = '', $content = '', $debug = false){
            if($this->develop){
                print $content;
                return;
            }

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