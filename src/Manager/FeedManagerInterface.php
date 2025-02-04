<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Manager;

use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface FeedManagerInterface
{
    /**
     * @throws NotFoundHttpException
     */
    public function getAtomFeedAsXml(FeedInterface $feed, string $baseUri, int $page = 1): string;
}
