<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $answers;

    #[ORM\OneToOne(inversedBy: 'question', cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnswerCorrectCombinations $answerCorrectCombinations;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->answerCorrectCombinations = new AnswerCorrectCombinations($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }

        return $this;
    }

    public function getAnswerCorrectCombinations(): ?AnswerCorrectCombinations
    {
        return $this->answerCorrectCombinations;
    }
}
