<?php

namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestEmailController extends AbstractController
{
    #[Route('/email', name: 'app_test_email')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('9510cb574ad508@sandbox.smtp.mailtrap.io')
            ->to('9510cb574ad508@sandbox.smtp.mailtrap.io')
            ->subject('Test email from Symfony')
            ->text('This is a test email sent from Symfony');

        $mailer->send($email);

        return $this->render('test_email/index.html.twig');
    }
}
