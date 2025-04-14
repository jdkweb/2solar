<?php

namespace TwoSolar\Mailer;

use TwoSolar\Mailer\MailerFactory;
use TwoSolar\Mailer\Mailer as APIMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MailSymfony extends APIMailer implements MailerFactory
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

    private Mailer $mailer;

    private Email $mail;


    public function __construct()
    {
        //Create an instance; passing `true` enables exceptions
        $dsn = "smtp://" . MAIL_USERNAME . ":" . urlencode(MAIL_PASSWORD) .
               "@" . MAIL_SMTP_HOST . ":" . MAIL_SMTP_PORT;
        $transport = Transport::fromDsn($dsn);
        $this->mailer = new Mailer($transport);
        $this->mail = new Email();
    }

    public function config()
    {
        foreach ($this->mailer_to as $to) {
            $this->mail->to(new Address($to['email'], $to['name']));
        }

        // Preset
        if (count($this->mailer_from) == 0) {
            $this->mailer_from = ['email' => MAIL_FROM, 'name' => MAIL_FROM_NAME];
        }

        $this->mail->from(new Address($this->mailer_from['email'], $this->mailer_from['name']));

        if (count($this->mailer_replyto) > 0) {
            $this->mail->replyTo(new Address($this->mailer_replyto['email'], $this->mailer_replyto['name']));
        }

        foreach ($this->mailer_cc as $email) {
            $this->mail->cc($email);
        }

        foreach ($this->mailer_bcc as $email) {
            $this->mail->bcc($email);
        }

        $this->mail->subject($this->mailer_subject);

        $this->mail->html($this->mailer_body);

        $this->mail->text($this->mailer_altbody);
    }

    public function send():bool
    {
        if (DEBUG_SEND_NO_MAIL === true) {
            echo $this->debug();
            return true;
        }

        return is_null($this->mailer->send($this->mail));
    }
}
