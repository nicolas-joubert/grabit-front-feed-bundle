<?php

declare(strict_types=1);

namespace NicolasJoubert\GrabitFrontFeedBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use NicolasJoubert\GrabitBundle\Model\SourceInterface;

abstract class Feed implements \Stringable, FeedInterface
{
    protected ?int $id = null;

    protected string $title = '';

    protected string $slug = '';

    protected string $description = '';

    protected int $itemsPerPage = self::ITEMS_PER_PAGE;

    /**
     * @var Collection<int, SourceInterface>
     */
    protected Collection $sources;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage(int $itemsPerPage): static
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(SourceInterface $source): static
    {
        if (!$this->sources->contains($source)) {
            $this->sources->add($source);
        }

        return $this;
    }

    public function removeSource(SourceInterface $source): static
    {
        $this->sources->removeElement($source);

        return $this;
    }
}
