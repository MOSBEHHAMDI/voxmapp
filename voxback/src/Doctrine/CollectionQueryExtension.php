<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Chat;
use App\Entity\User;
use App\Service\Utils;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class CollectionQueryExtension implements QueryCollectionExtensionInterface
{
    private Security $security;
    private Utils $utils;

    public function __construct(Security $security, Utils $utils)
    {
        $this->security = $security;
        $this->utils = $utils;
    }

    public function applyToCollection(QueryBuilder                $queryBuilder,
                                      QueryNameGeneratorInterface $queryNameGenerator,
                                                                  $resourceClass, $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass, $context);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, array $context = []): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        //$this->utils->logFile($user->getId(), 'CollectionQueryExtension');

        if (Chat::class !== $resourceClass) {
            return;
        }
        if (array_key_exists('chat', $context['filters'])) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $or = $queryBuilder->expr()->orX();
        $or->add($rootAlias . '.createdBy = :user');
        $or->add(":user MEMBER OF $rootAlias.receivers");
        $queryBuilder->andWhere($or);
        $queryBuilder->setParameter('user', $user->getId());
        //$this->utils->logFile($queryBuilder, 'CollectionQueryExtension');
    }
}