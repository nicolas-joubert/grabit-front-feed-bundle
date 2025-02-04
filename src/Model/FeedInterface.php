<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\Model;

use Doctrine\Common\Collections\Collection;
use NicolasJoubert\GrabitBundle\Model\SourceInterface;

interface FeedInterface
{
    public const int ITEMS_PER_PAGE = 20;

    public function getId(): ?int;

    public function getTitle(): string;

    public function setTitle(string $title): static;

    public function getSlug(): string;

    public function setSlug(string $slug): static;

    public function getDescription(): string;

    public function setDescription(string $description): static;

    public function getItemsPerPage(): int;

    public function setItemsPerPage(int $itemsPerPage): static;

    /**
     * @return Collection<int, SourceInterface>
     */
    public function getSources(): Collection;

    public function addSource(SourceInterface $source): static;

    public function removeSource(SourceInterface $source): static;
}
