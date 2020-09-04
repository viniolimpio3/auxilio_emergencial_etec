<?php namespace Mailer;


use PHPMailer;

if(! require __DIR__ . '/../libs/phpmailer/class.phpmailer.php') require '../libs/phpmailer/class.phpmailer.php';
if(! require 'env.php') require 'env.php';

class Mailer{
    
    private $from;
    private $from_name;

    private $mail;
    private $error;

    function __construct(){
        $this->mail = new PHPMailer();

        $this->mail->IsSMTP();

        $this->mail->Username = USER_MAIL;
        $this->mail->Password = PASS_MAIL;
        $this->mail->Host = SMTP_SERVER;
        $this->mail->Port = SMTP_PORT;
        $this->mail->SetFrom(USER_MAIL, USER_MAIL_NAME);

        $this->mail->SMTPSecure = 'tls';
        $this->mail->IsHTML(true);
        $this->mail->CharSet="UTF-8";
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPDebug=1; // Debugar: 1 = erros e mensagens, 2 = mensagens apenas


        $this->from = USER_MAIL;
        $this->from_name = USER_MAIL_NAME;

    }

    public function send($to, $to_name, $subject, $body){       
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AddAddress($to);
        $this->mail->IsHTML(true);
        if(!$this->mail->Send()) {
            return ['error' => $this->mail->ErrorInfo ];
        } else {
            $this->error = 'Mensagem enviada!';
            return true;
        }
    }
}
?>