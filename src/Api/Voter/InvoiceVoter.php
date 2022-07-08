<?php

declare(strict_types=1);

namespace App\Api\Voter;

use App\Api\Exception\Order\InvoiceNotFoundException;
use App\Entity\Order\Invoice;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class InvoiceVoter extends Voter
{
    private const VIEW = 'view';

    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute != self::VIEW) {
            return false;
        }

        if (!$subject instanceof Invoice) {
            return false;
        }

        return true;
    }

    /**
     * @throws InvoiceNotFoundException
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Invoice $subject */
        if ($subject->getOrder()->getUser()->getId() === $user->getId()) {
            return true;
        }

        throw new InvoiceNotFoundException();
    }
}