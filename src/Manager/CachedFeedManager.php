<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Manager;

use NicolasJoubert\GrabitBundle\Model\SourceInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CachedFeedManager implements FeedManagerInterface, CachedFeedManagerInterface
{
    public function __construct(
        private readonly FeedManagerInterface $inner,
        private readonly AdapterInterface $cache,
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws CacheException
     * @throws NotFoundHttpException
     * @throws \DOMException
     */
    #[\Override]
    public function getAtomFeedAsXml(FeedInterface $feed, string $baseUri, int $page = 1): string
    {
        $item = $this->cache->getItem($this->getCacheKey($feed, $page));
        if (!$item->isHit()) {
            $atomFeedXml = $this->inner->getAtomFeedAsXml($feed, $baseUri, $page);

            $item->set($atomFeedXml);
            if ($this->isTagAwareAdapter()) {
                $item->tag($this->getFeedTag($feed));
                foreach ($feed->getSources() as $source) {
                    $item->tag($this->getSourceTag($source));
                }
            }

            $this->cache->save($item);
        } else {
            /** @var string $atomFeedXml */
            $atomFeedXml = $item->get();
        }

        return $atomFeedXml;
    }

    #[\Override]
    public function invalidateByFeed(FeedInterface $feed): bool
    {
        return $this->invalidateTag($this->getFeedTag($feed));
    }

    #[\Override]
    public function invalidateBySource(SourceInterface $source): bool
    {
        return $this->invalidateTag($this->getSourceTag($source));
    }

    private function isTagAwareAdapter(): bool
    {
        return $this->cache instanceof TagAwareAdapterInterface;
    }

    private function invalidateTag(string $tag): bool
    {
        // @phpstan-ignore method.notFound
        return $this->isTagAwareAdapter() && $this->cache->invalidateTags([$tag]);
    }

    private function getCacheKey(FeedInterface $feed, int $page): string
    {
        return sprintf('feed_%s_%d', $feed->getId(), $page);
    }

    private function getFeedTag(FeedInterface $feed): string
    {
        return sprintf('feed_%s', $feed->getId());
    }

    private function getSourceTag(SourceInterface $source): string
    {
        return sprintf('source_%s', $source->getId());
    }
}
