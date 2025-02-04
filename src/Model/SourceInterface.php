<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Model;

use Doctrine\Common\Collections\Collection;
use NicolasJoubert\GrabitBundle\Model\SourceInterface as BaseSourceInterface;

interface SourceInterface extends BaseSourceInterface
{
    /**
     * @return Collection<int, FeedInterface>
     */
    public function getFeeds(): Collection;

    public function addFeed(FeedInterface $feed): static;

    public function removeFeed(FeedInterface $feed): static;
}
