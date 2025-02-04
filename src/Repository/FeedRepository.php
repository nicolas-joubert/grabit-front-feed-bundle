<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

/**
 * @template T of FeedInterface
 *
 * @template-extends EntityRepository<FeedInterface>
 */
class FeedRepository extends EntityRepository implements FeedRepositoryInterface
{
    /**
     * @param class-string<FeedInterface> $className
     */
    public function __construct(EntityManagerInterface $em, string $className)
    {
        parent::__construct($em, $em->getClassMetadata($className));
    }
}
