<?php
namespace App\Service;

use App\Entity\Affiliate;
use Twig\Environment;
use Swift_Mailer;

class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;
    /** @var Environment */
    private $twig;


    /**
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer,Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    /**
     * @param Affiliate $affiliate
     */
    public function sendActivationEmail(Affiliate $affiliate): void
    {
          $message = (new \Swift_Message('Hello Email'))
            ->setFrom('merrahi@gmail.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['affiliate' => $affiliate]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}