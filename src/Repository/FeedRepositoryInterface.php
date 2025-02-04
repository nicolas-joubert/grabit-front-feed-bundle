<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Repository;

use Doctrine\Persistence\ObjectRepository;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

/**
 * @extends ObjectRepository<FeedInterface>
 */
interface FeedRepositoryInterface extends ObjectRepository {}
