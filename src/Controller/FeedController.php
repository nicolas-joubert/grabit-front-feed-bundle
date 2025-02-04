<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Controller;

use NicolasJoubert\GrabitFrontFeedBundle\Manager\FeedManagerInterface;
use NicolasJoubert\GrabitFrontFeedBundle\Repository\FeedRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedController
{
    public function __construct(
        private readonly FeedRepositoryInterface $feedRepository,
        private readonly FeedManagerInterface $feedManager,
    ) {}

    public function index(
        string $slug,
        Request $request
    ): Response {
        $feed = $this->feedRepository->findOneBy(['slug' => $slug]);

        if (!$feed) {
            throw new NotFoundHttpException('Feed not found');
        }

        $page = $request->query->getInt('page', 1);
        $baseUri = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo();

        return new Response($this->feedManager->getAtomFeedAsXml($feed, $baseUri, $page));
    }
}
