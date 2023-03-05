<?php
namespace App\EventListener;

use App\Entity\Joueur;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
       
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
         
        $token = $event->getAuthenticatedToken();
        $joueur = $token->getUser();
        $token = $event->getAuthenticatedToken();
       
        
        if ($joueur instanceof Joueur && $joueur->isIsbanned()){
            $response = new RedirectResponse($this->urlGenerator->generate('app_banned'));
        }

        elseif (in_array("ROLE_ADMIN", $joueur->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('joueur_back'));
        }
        else {
            $response = new RedirectResponse($this->urlGenerator->generate('app_index'));
        }
        
        

        $event->setResponse($response);
    }
    
}