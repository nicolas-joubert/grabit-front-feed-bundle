<?php

declare(strict_types=1);

namespace NicolasJoubert\GrabitFrontFeedBundle\Manager;

use NicolasJoubert\GrabitBundle\Model\SourceInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

interface CachedFeedManagerInterface
{
    public function invalidateByFeed(FeedInterface $feed): bool;

    public function invalidateBySource(SourceInterface $source): bool;
}
