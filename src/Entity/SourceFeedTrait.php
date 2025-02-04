<?php

declare(strict_types=1);

namespace NicolasJoubert\GrabitFrontFeedBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\SourceFeedTrait as BaseSourceFeedTrait;

/**
 * @phpstan-ignore trait.unused
 */
trait SourceFeedTrait
{
    use BaseSourceFeedTrait;

    /**
     * @var Collection<int, FeedInterface>
     */
    #[ORM\ManyToMany(targetEntity: FeedInterface::class, mappedBy: 'sources')]
    protected Collection $feeds;
}
