<?php

declare(strict_types=1);

namespace NicolasJoubert\GrabitFrontFeedBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @phpstan-ignore trait.unused
 */
trait SourceFeedTrait
{
    /**
     * @var Collection<int, FeedInterface>
     */
    protected Collection $feeds;

    public function __construct()
    {
        parent::__construct();

        $this->feeds = new ArrayCollection();
    }

    /**
     * @return Collection<int, FeedInterface>
     */
    public function getFeeds(): Collection
    {
        return $this->feeds;
    }

    public function addFeed(FeedInterface $feed): static
    {
        if (!$this->feeds->contains($feed)) {
            $this->feeds->add($feed);
            $feed->addSource($this);
        }

        return $this;
    }

    public function removeFeed(FeedInterface $feed): static
    {
        if ($this->feeds->removeElement($feed)) {
            $feed->removeSource($this);
        }

        return $this;
    }
}
