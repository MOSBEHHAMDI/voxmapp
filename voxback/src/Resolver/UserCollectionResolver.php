<?php

namespace App\Resolver;
use ApiPlatform\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\User;
final class UserCollectionResolver implements QueryCollectionResolverInterface
{
    /**
     * @param iterable<User> $collection
     *
     * @return iterable<User>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        // Query arguments are in $context['args'].
        foreach ($collection as $user) {
            // Do something with the user.
        }
        return $collection;
    }
}