<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailSender
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMessage(TemplatedEmail $mail)
    {
        $this->mailer->send($mail);
    }

    public function createConfirmation(string $from, string $to, string $code): TemplatedEmail
    {
        $mail = new TemplatedEmail();
        $mail
            ->from($from)
            ->to($to)
            ->subject('Confirm registration')
            ->htmlTemplate('registration/confirmation_message.html.twig')
            ->context([
                'confirmationCode' => $code
            ]);
        return $mail;
    }
}