<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\EventListener;

use NicolasJoubert\GrabitBundle\Model\ExtractedDataInterface;
use NicolasJoubert\GrabitBundle\Model\SourceInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Manager\CachedFeedManagerInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Manager\FeedManagerInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

class CacheInvalidate
{
    public function __construct(private readonly FeedManagerInterface $feedManager) {}

    public function invalidateCache(object $entity): void
    {
        if (!$this->feedManager instanceof CachedFeedManagerInterface) {
            return;
        }

        if ($entity instanceof FeedInterface) {
            $this->feedManager->invalidateByFeed($entity);
        } elseif ($entity instanceof SourceInterface) {
            $this->feedManager->invalidateBySource($entity);
        } elseif ($entity instanceof ExtractedDataInterface) {
            $this->feedManager->invalidateBySource($entity->getSource());
        }
    }
}
