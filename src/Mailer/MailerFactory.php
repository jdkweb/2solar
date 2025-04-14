<?php

namespace TwoSolar\Mailer;

interface MailerFactory
{
    public function __construct();
    public function config();
    public function send():bool;
}
