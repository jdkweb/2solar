<?php

namespace TwoSolar\Chain\Traits;

trait Mailer
{



    //----------------------------------------------------------------------------------------

    /**
     * Mail voor administratie
     *
     * @param array $item
     * @return bool
     */
    public function setMailerChunk(array $item):bool
    {
        // status
        $status_id = $this->twoSolar->status_id;
        // mail to adressen
        if (!empty($item['options']['mail_to'])) {
            $mail_to = $item['options']['mail_to'];
        } else {
            return false;
        }

        // mail cc adressen
        $mail_cc = [];
        if (!empty($item['options']['mail_cc'])) {
            $mail_cc = $item['options']['mail_cc'];
            $mail_cc = explode(",", $mail_cc);
        }

        // mail bcc adressen
        $mail_bcc = [];
        if (!empty($item['options']['mail_bcc'])) {
            $mail_bcc = $item['options']['mail_bcc'];
            $mail_bcc = explode(",", $mail_bcc);
        }

        // mail subject
        $subject = '';
        if (!empty($item['options']['mail_subject'])) {
            $subject = $item['options']['mail_subject'];
        }

        $replyTo = $item['email'];

        // TESTING
        if (DEBUG_TEST_MAIL) {
            $this->twoSolar->mailer->setTo(DEBUG_MAIL_ADDRESS, $item['city']);
        } else {
            $this->twoSolar->mailer->setTo($mail_to, $item['city']);

            array_walk($mail_cc, function ($value, $key) {
                $this->twoSolar->mailer->setCc($value);
            });

            array_walk($mail_bcc, function ($value, $key) {
                $this->twoSolar->mailer->setBcc($value);
            });
        }

        $bodytxt = $this->getChunk($this->twoSolar->status, $item);

        $this->twoSolar->mailer->setReplyTo($replyTo, $item['naam']);
        $this->twoSolar->mailer->setSubject($subject);
        $this->twoSolar->mailer->setBody($bodytxt);
        $this->twoSolar->mailer->setAltBody(nl2br(strip_tags($bodytxt)));

        return true;
    }
}
