<?php

namespace App\EventListener;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserMutationResolver implements MutationResolverInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function __invoke($item, array $context): ?object
    {
        $params = $context['args']['input'];
        if ($item && isset($params['plainPassword'])) {
            $item->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $item,
                        $params['plainPassword']
                    )
                );
        }
        return $item;
    }
}