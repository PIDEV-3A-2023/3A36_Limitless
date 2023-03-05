<?php
namespace App\Voter;

use App\Entity\Joueur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BannedVoter extends Voter
{
    const BANNED = 'banned';

    protected function supports($attribute, $subject)
    {
        return $attribute === self::BANNED;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Joueur) {
            return false;
        }

        return !$user->isIsbanned();
    }
}

?>