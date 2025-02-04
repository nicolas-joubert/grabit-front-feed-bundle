<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Manager;

use NicolasJoubert\GrabitBundle\Model\ExtractedDataInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Repository\ExtractedDataRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedManager implements FeedManagerInterface
{
    public function __construct(private readonly ExtractedDataRepositoryInterface $extractedDataRepository) {}

    /**
     * @throws NotFoundHttpException
     * @throws \DOMException
     */
    #[\Override]
    public function getAtomFeedAsXml(FeedInterface $feed, string $baseUri, int $page = 1): string
    {
        $countItems = $this->countExtractedDatas($feed);
        $maxPage = ceil($countItems / $feed->getItemsPerPage());
        if ($page > $maxPage || 0 === $countItems) {
            throw new NotFoundHttpException('Page not found');
        }

        $selfUri = $baseUri.'?page='.$page;
        if ($maxPage > 1) {
            $firstUri = $baseUri;
            if ($page - 1 > 0) {
                $previousUri = $baseUri.'?page='.($page - 1);
            }
            if ($page + 1 <= $maxPage) {
                $nextUri = $baseUri.'?page='.($page + 1);
            }
            $lastUri = $baseUri.'?page='.$maxPage;
        }

        $atomFeed = new \DOMDocument('1.0', 'UTF-8');

        $rss = $atomFeed->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $rss->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rss->setAttribute('xmlns:media', 'http://search.yahoo.com/mrss/');
        $rss->setAttribute('xmlns:content', 'http://purl.org/rss/1.0/modules/content/');

        $channel = $atomFeed->createElement('channel');

        $title = $atomFeed->createElement('title', '');
        $title->appendChild($atomFeed->createCDATASection($feed->getTitle()));
        $channel->appendChild($title);

        $description = $atomFeed->createElement('description', '');
        $description->appendChild($atomFeed->createCDATASection($feed->getDescription()));
        $channel->appendChild($description);

        $channel->appendChild($atomFeed->createElement('link', $selfUri));
        $channel->appendChild($atomFeed->createElement('pubDate', (new \DateTime())->format('r')));

        $atomLink = $atomFeed->createElement('atom:link');
        $atomLink->setAttribute('href', $selfUri);
        $atomLink->setAttribute('rel', 'self');
        $atomLink->setAttribute('type', 'application/rss+xml');
        $channel->appendChild($atomLink);

        if (isset($firstUri)) {
            $atomLink = $atomFeed->createElement('atom:link');
            $atomLink->setAttribute('href', $firstUri);
            $atomLink->setAttribute('rel', 'first');
            $atomLink->setAttribute('type', 'application/rss+xml');
            $channel->appendChild($atomLink);
        }
        if (isset($previousUri)) {
            $atomLink = $atomFeed->createElement('atom:link');
            $atomLink->setAttribute('href', $previousUri);
            $atomLink->setAttribute('rel', 'previous');
            $atomLink->setAttribute('type', 'application/rss+xml');
            $channel->appendChild($atomLink);
        }
        if (isset($nextUri)) {
            $atomLink = $atomFeed->createElement('atom:link');
            $atomLink->setAttribute('href', $nextUri);
            $atomLink->setAttribute('rel', 'next');
            $atomLink->setAttribute('type', 'application/rss+xml');
            $channel->appendChild($atomLink);
        }
        if (isset($lastUri)) {
            $atomLink = $atomFeed->createElement('atom:link');
            $atomLink->setAttribute('href', $lastUri);
            $atomLink->setAttribute('rel', 'last');
            $atomLink->setAttribute('type', 'application/rss+xml');
            $channel->appendChild($atomLink);
        }

        foreach ($this->getExtractedDatas($feed, $page) as $extractedData) {
            $extractedDataContent = $extractedData->getContent();
            if (null === $extractedDataContent) {
                continue;
            }
            $item = $atomFeed->createElement('item');

            $title = $atomFeed->createElement('title', '');
            $title->appendChild($atomFeed->createCDATASection($extractedDataContent->getTitle()));
            $item->appendChild($title);

            if (null !== $extractedDataContent->getPublicationDate()) {
                $item->appendChild($atomFeed->createElement(
                    'pubDate',
                    $extractedDataContent->getPublicationDate()->format('r')
                ));
            }

            $description = $atomFeed->createElement('description', '');
            $description->appendChild($atomFeed->createCDATASection($extractedDataContent->getDescription()));
            $item->appendChild($description);

            /** @var array{
             *     scheme: string,
             *     host: string,
             *     port: int,
             *     user: string,
             *     pass: string,
             *     path: string,
             *     query: string,
             *     fragment: string
             * } $linkElements
             */
            $linkElements = parse_url($extractedDataContent->getLink());
            $cleanedLink = $linkElements['scheme'].'://'.$linkElements['host'].$linkElements['path'];

            $guid = $atomFeed->createElement('guid', $cleanedLink);
            $guid->setAttribute('isPermaLink', 'true');
            $item->appendChild($guid);

            $item->appendChild($atomFeed->createElement('link', $cleanedLink));
            if (!empty($extractedDataContent->getImage())) {
                $mediaContent = $atomFeed->createElement('media:content', '');
                $mediaContent->setAttribute('url', $extractedDataContent->getImage());
                $item->appendChild($mediaContent);
            }

            $channel->appendChild($item);
        }

        $rss->appendChild($channel);
        $atomFeed->appendChild($rss);

        $xml = $atomFeed->saveXML();

        return false !== $xml ? $xml : '';
    }

    private function countExtractedDatas(FeedInterface $feed): int
    {
        return $this->extractedDataRepository->countBySources($feed->getSources());
    }

    /**
     * @return ExtractedDataInterface[]
     */
    private function getExtractedDatas(FeedInterface $feed, int $page = 1): array
    {
        return $this->extractedDataRepository->getBySources(
            $feed->getSources(),
            $page,
            $feed->getItemsPerPage(),
        );
    }
}
