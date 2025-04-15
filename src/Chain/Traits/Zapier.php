<?php

namespace TwoSolar\Chain\Traits;

trait Zapier
{
    /**
     * MailSettings voor Zapier (via outlook)
     *
     * Zapier can filter/match on specific subject and use body- and/or mailto-data
     *
     * @param array $item
     * @return bool
     */
    public function setZapierChunk(array $item):bool
    {
        $status_id = $this->twoSolar->status;

        $replyTo = $item['email'];

        // TESTING
        if (DEBUG_TEST_MAIL) {
            $this->twoSolar->mailer->setTo(DEBUG_MAIL_ADDRESS, $item['city']);
        } else {
            // city for location in zapier mail, transport via recipients email
            $this->twoSolar->mailer->setTo(ZAPIER_TRANSPORT_MAIL, $item['city']);
        }

        $this->twoSolar->mailer->setReplyTo($replyTo, $item['naam']);
        $this->twoSolar->mailer->setSubject('status:'.$status_id);
        $this->twoSolar->mailer->setBody("Mail for Zapier, status ".$status_id.", naam: ".$item['naam']);
        $this->twoSolar->mailer->setAltBody("Mail for Zapier, status ".$status_id.", naam: ".$item['naam']);

        return true;
    }
}
