<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\VisitorHistoryRepository;
use App\State\VisitorHistoryBinaryStateProcessor;
use App\State\VisitorHistoryMultipleChoiceStateProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VisitorHistoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/visitor_histories/binary',
            processor: VisitorHistoryBinaryStateProcessor::class
        ),
        new Post(
            uriTemplate: '/visitor_histories/multiple-choice',
            processor: VisitorHistoryMultipleChoiceStateProcessor::class
        ),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['visitor_history:read']],
    denormalizationContext: ['groups' => ['visitor_history:write']]
)]
class VisitorHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'visitorHistory')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['visitor_history:read', 'visitor_history:write'])]
    private ?Visitor $visitor = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['visitor_history:read', 'visitor_history:write'])]
    private ?Question $question = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['visitor_history:read', 'visitor_history:write'])]
    private ?Answer $answer = null;

    #[ORM\Column]
    private ?bool $isCorrect = null;

    #[Groups(['visitor_history:write'])]
    private ?string $binaryValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    public function setVisitor(?Visitor $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    #[Groups(['visitor_history:read'])]
    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getBinaryValue(): ?string
    {
        return $this->binaryValue;
    }

    public function setBinaryValue(?string $binaryValue): self
    {
        $this->binaryValue = $binaryValue;

        return $this;
    }
}