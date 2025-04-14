<?php
/**
 * TEMPLATE VOOR IMPLEMENTATIE VAN VENDOR MAILER
 *
 * 3th party voor verzenden van de mail
 * Toekenenen van mailadressen en het verzenden van de mail
 * Variabelen en bericht wordt elders aangemaakt / gevuld
 *
 * classname: Mail[NAME] b.v. MailPHPMailer
 *
 * Constant defined in index: MAILER = "PHPMailer"
 */

namespace TwoSolar\Mailer;

use TwoSolar\Mailer\MailerFactory;
use TwoSolar\Mailer\Mailer;

// VENDOR CLASSES
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;


class MailFactoryTemplate extends Mailer implements MailerFactory
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


    private $mail;


    public function __construct()
    {
        // Create an instance
        // $this->mail = new ....
    }

    /**
     * Configure all addresses to send the mail
     * @return void
     */
    public function config()
    {
        /**
         * MAIL TO
         * values:
         * $this->mailer_to[] = [
         *   'email' => example@domain.com
         *   'name'  => 'Jan Jansen
         * ]
         */
        foreach ($this->mailer_to as $to) {
            // $this->mail->setTo.....
        }

        // Preset
        if (count($this->mailer_from) == 0) {
            $this->mailer_from = ['email' => MAIL_FROM, 'name' => MAIL_FROM_NAME];
        }

        /**
         * MAIL FROM
         * value:
         * $this->mailer_from[
         *   'email' => example@domain.com
         *   'name'  => 'Jan Jansen
         * ]
         */
        // $this->mail->setFrom....

        /**
         * REPLY TO
         * value:
         * $this->mailer_replyto[
         *   'email' => example@domain.com
         *   'name'  => 'Jan Jansen
         * ]
         */
        if (count($this->mailer_replyto) > 0) {
            // $this->mail->addReplyTo...
        }

        /**
         * MAIL CC
         * values:
         * $this->mailer_to[] = [
         *   'email' => example@domain.com
         *   'name'  => 'Jan Jansen
         * ]
         */
        foreach ($this->mailer_cc as $email) {
            // $this->mail->addCC...
        }

        /**
         * MAIL BCC
         * values:
         * $this->mailer_to[] = [
         *   'email' => example@domain.com
         *   'name'  => 'Jan Jansen
         * ]
         */
        foreach ($this->mailer_bcc as $email) {
            // $this->mail->addBCC....
        }

        /**
         * SUBJECT
         * value:
         * $this->mailer_subject;
         */
        // $this->mail->setSubject = $this->mailer_subject;

        /**
         * BODY
         * value:
         * $this->mailer_body;
         */
        // $this->mail->setBody = $this->mailer_body;

        /**
         * ALT BODY
         * value:
         * $this->mailer_altbody;
         */
        //$this->mail->AltBody = $this->mailer_altbody;
    }

    /**
     * Send mail
     * @return bool
     */
    public function send():bool
    {
        // test constant set in index.php
        if (DEBUG_SEND_NO_MAIL === true) {
            echo $this->debug();
            return true;
        }

        /**
         * SEND MAIL
         * return boolean
         */
        // return $this->mail->send();
    }
}
