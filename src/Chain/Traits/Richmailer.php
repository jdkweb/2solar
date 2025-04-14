<?php

namespace TwoSolar\Chain\Traits;

trait Richmailer
{

    /**
     * Mail i.p.v. Zapier lokaal afhandelen
     *
     * @param array $item
     * @return bool
     */
    public function setRichmailerChunk(array $item):bool
    {

        // mail to adressen
        $mail_to =  $item['email'];

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

        // TESTING
        if (DEBUG_TEST_MAIL) {
            $this->twoSolar->mailer->setTo(DEBUG_MAIL_ADDRESS, $item['naam']);
        } else {
            $this->twoSolar->mailer->setTo($mail_to, $item['naam']);

            foreach ($mail_cc as $value) {
                $this->twoSolar->mailer->setCc($value);
            }

            foreach ($mail_bcc as $value) {
                $this->twoSolar->mailer->setBcc($value);
            }
        }

        $bodytxt = $this->getChunk("richmailer_".$this->twoSolar->status, [
            'NAME' => $item['naam'],
            'CITY' => $item['city']
        ]);

        $this->twoSolar->mailer->setReplyTo($item['email'], $item['naam']);
        $this->twoSolar->mailer->setSubject($subject);
        $this->twoSolar->mailer->setBody($bodytxt);
        $this->twoSolar->mailer->setAltBody(nl2br(strip_tags($bodytxt)));

        return true;
    }
}
