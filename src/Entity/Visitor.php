<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\VisitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => 'visitor:read'],
    denormalizationContext: ['groups' => 'visitor:write']
)]
#[UniqueEntity('session')]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['visitor:read'])]
    #[ApiProperty(identifier: true)]
    private string $session;

    #[ORM\Column]
    #[Groups(['visitor:read'])]
    private array $settings = [];

    #[ORM\OneToMany(mappedBy: 'visitor', targetEntity: VisitorHistory::class)]
    #[Groups(['visitor:read'])]
    private Collection $visitorHistory;

    public function __construct()
    {
        $this->session = uniqid();
        $this->visitorHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return Collection<int, VisitorHistory>
     */
    public function getVisitorHistory(): Collection
    {
        return $this->visitorHistory;
    }

    public function addVisitorHistory(VisitorHistory $visitorHistory): self
    {
        if (!$this->visitorHistory->contains($visitorHistory)) {
            $this->visitorHistory->add($visitorHistory);
            $visitorHistory->setVisitor($this);
        }

        return $this;
    }

    public function removeVisitorHistory(VisitorHistory $visitorHistory): self
    {
        if ($this->visitorHistory->removeElement($visitorHistory)) {
            // set the owning side to null (unless already changed)
            if ($visitorHistory->getVisitor() === $this) {
                $visitorHistory->setVisitor(null);
            }
        }

        return $this;
    }
}
