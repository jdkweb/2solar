<?php

namespace TwoSolar\Mailer;

abstract class Mailer
{
    protected $mailer_to = [];
    protected $mailer_from = [];
    protected $mailer_replyto = [];
    protected $mailer_cc = [];
    protected $mailer_bcc = [];
    protected $mailer_subject;
    protected $mailer_body;
    protected $mailer_altbody;

    //----------------------------------------------------------------------------------------
    
    public function reset()
    {
        $this->mailer_to = [];
        $this->mailer_from = [];
        $this->mailer_replyto = [];
        $this->mailer_cc = [];
        $this->mailer_bcc = [];
        $this->mailer_subject = '';
        $this->mailer_body = '';
        $this->mailer_altbody = '';
    }

    //----------------------------------------------------------------------------------------

    public function setTo(string $email, string $name = '')
    {
        array_push($this->mailer_to, ['email'=> $email, 'name' => $name]);
    }

    //----------------------------------------------------------------------------------------

    public function setFrom(string $email, string $name = '')
    {
        $this->mailer_from = ['email' => $email, 'name' => $name];
    }

    //----------------------------------------------------------------------------------------

    public function setReplyTo(string $email, string $name = '')
    {
        $this->mailer_replyto = ['email' => $email, 'name' => $name];
    }

    //----------------------------------------------------------------------------------------

    public function setCc(string $email)
    {
        array_push($this->mailer_cc, $email);
    }

    //----------------------------------------------------------------------------------------

    public function setBcc(string $email)
    {
        array_push($this->mailer_bcc, $email);
    }

    //----------------------------------------------------------------------------------------

    public function setSubject(string $content)
    {
        $this->mailer_subject = $content;
    }

    //----------------------------------------------------------------------------------------

    public function setBody(string $content)
    {
        $this->mailer_body = $content;
    }

    //----------------------------------------------------------------------------------------

    public function setAltBody(string $content)
    {
        $this->mailer_altbody = strip_tags($content);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Debug mail niet verzenden alleen output bekijken
     *
     * @return string
     */
    protected function debug():string
    {
        $output = '';

        $output = "MAIL\n";
        $output .= "From: ".$this->mailer_from['email']." <".$this->mailer_from['name'].">\n";
        $output .= "To: ".$this->mailer_to[0]['email']." <".$this->mailer_to[0]['name'].">\n";
        $output .= "CC: ".implode(",", $this->mailer_cc)."\n";
        if (count($this->mailer_replyto) > 0) {
            $output .= "ReplyTo: ".$this->mailer_replyto['email']." <".$this->mailer_replyto['name'].">\n";
        }
        $output .= "Subject: ".$this->mailer_subject."\n";
        $output .= "Body: ".$this->mailer_body."\n";

        $output = nl2br($output);

        return $output;
    }

    //----------------------------------------------------------------------------------------

    abstract public function config();
    abstract public function send():bool;
}
