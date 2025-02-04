<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Repository;

use Doctrine\Common\Collections\Collection;
use NicolasJoubert\GrabitBundle\Model\ExtractedDataInterface;
use NicolasJoubert\GrabitBundle\Model\SourceInterface;
use NicolasJoubert\GrabitBundle\Repository\ExtractedDataRepositoryInterface as BaseExtractedDataRepositoryInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

interface ExtractedDataRepositoryInterface extends BaseExtractedDataRepositoryInterface
{
    /**
     * @param Collection<int, SourceInterface> $sources
     */
    public function countBySources(Collection $sources): int;

    /**
     * @param Collection<int, SourceInterface> $sources
     *
     * @return ExtractedDataInterface[]
     */
    public function getBySources(Collection $sources, int $page = 1, int $itemPerPages = FeedInterface::ITEMS_PER_PAGE): array;
}
