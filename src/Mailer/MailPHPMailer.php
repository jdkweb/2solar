<?php

namespace TwoSolar\Mailer;

use TwoSolar\Mailer\MailerFactory;
use TwoSolar\Mailer\Mailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailPHPMailer extends Mailer implements MailerFactory
{
    /**
     *  CONST MAIL_SMTP_HOST
     *
     *  smtp server
     *  @var string
     */

    /**
     *  CONST MAIL_USERNANE
     *
     *  mail loginnaam
     *  @var string
     */

    /**
     *  CONST MAIL_PASSWORD
     *
     *  mail wachtwoord
     *  @var string
     */

    /**
     *  CONST MAIL_SMTP_PORT
     *
     *  smtp poort
     *  @var string
     */

    /**
     *  CONST MAIL_FROM
     *
     *  afzender mailadres
     *  @var string
     */

    /**
     *  CONST MAIL_FROM_NAME
     *
     *  afzender naam
     *  @var string
     */


    private PHPMailer $mail;


    public function __construct()
    {
        //Create an instance; passing `true` enables exceptions
        $this->mail = new PHPMailer(true);
        $this->mail->isHTML(true);
        $this->mail->CharSet = "UTF-8";
        $this->mail->Encoding = 'base64';

        //Server settings
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
        $this->mail->isSMTP();                                         //Send using SMTP
        $this->mail->Host       = MAIL_SMTP_HOST;                      //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                //Enable SMTP authentication
        $this->mail->Username   = MAIL_USERNAME;                       //SMTP username
        $this->mail->Password   = MAIL_PASSWORD;                       //SMTP password
        $this->mail->SMTPSecure = (MAIL_SMTP_PORT==587?
            PHPMailer::ENCRYPTION_STARTTLS:
            PHPMailer::ENCRYPTION_SMTPS
        );                                                             //Enable implicit TLS encryption
        $this->mail->Port       = MAIL_SMTP_PORT;                      //TCP port to connect to; use 587 if you have set
                                                                       // `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    }

    public function config()
    {
        $this->mail->clearAddresses();
        $this->mail->ClearReplyTos();
        $this->mail->ClearAllRecipients();
        $this->mail->ClearAttachments();
        $this->mail->ClearCCs();

        foreach ($this->mailer_to as $to) {
            $this->mail->addAddress($to['email'], $to['name']);
        }

        // Preset
        if (count($this->mailer_from) == 0) {
            $this->mailer_from = ['email' => MAIL_FROM, 'name' => MAIL_FROM_NAME];
        }

        $this->mail->setFrom($this->mailer_from['email'], $this->mailer_from['name']);

        if (count($this->mailer_replyto) > 0) {
            $this->mail->addReplyTo($this->mailer_replyto['email'], $this->mailer_replyto['name']);
        }

        foreach ($this->mailer_cc as $email) {
            $this->mail->addCC($email);
        }

        foreach ($this->mailer_bcc as $email) {
            $this->mail->addBCC($email);
        }

        $this->mail->Subject = $this->mailer_subject;

        $this->mail->Body = $this->mailer_body;

        $this->mail->AltBody = $this->mailer_altbody;
    }

    public function send():bool
    {
        if (DEBUG_SEND_NO_MAIL === true) {
            echo $this->debug();
            return true;
        }

        return $this->mail->send();
    }
}
