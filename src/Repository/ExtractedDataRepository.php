<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use NicolasJoubert\GrabitBundle\Model\ExtractedDataInterface;
use NicolasJoubert\GrabitBundle\Model\SourceInterface;
use NicolasJoubert\GrabitBundle\Repository\ExtractedDataRepository as BaseExtractedDataRepository;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;

/**
 * @template T of ExtractedDataInterface
 *
 * @template-extends BaseExtractedDataRepository<ExtractedDataInterface>
 */
class ExtractedDataRepository extends BaseExtractedDataRepository implements ExtractedDataRepositoryInterface
{
    public function countBySources(Collection $sources): int
    {
        /** @var array{count: int} $result */
        $result = $this->queryBuilderBySources($sources)
            ->select('count(ed.id) as count')
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result['count'];
    }

    public function getBySources(Collection $sources, int $page = 1, int $itemPerPages = FeedInterface::ITEMS_PER_PAGE): array
    {
        // @phpstan-ignore return.type
        return $this->queryBuilderBySources($sources)
            ->orderBy('ed.publishedAt', 'DESC')
            ->addOrderBy('ed.createdAt', 'DESC')
            ->addOrderBy('ed.id', 'DESC')
            ->setFirstResult(($page - 1) * $itemPerPages)
            ->setMaxResults($itemPerPages)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Collection<int, SourceInterface> $sources
     */
    private function queryBuilderBySources(Collection $sources): QueryBuilder
    {
        return $this->createQueryBuilder('ed')
            ->where('ed.source IN (:sources)')
            ->setParameter('sources', $sources)
        ;
    }
}
