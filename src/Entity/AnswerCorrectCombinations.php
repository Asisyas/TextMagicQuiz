<?php

namespace App\Entity;

use App\Repository\AnswerCorrectCombinationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerCorrectCombinationsRepository::class)]
class AnswerCorrectCombinations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'answerCorrectCombinations', targetEntity: Answer::class)]
    private Collection $answers;

    public function __construct(
        #[ORM\OneToOne(mappedBy: 'answerCorrectCombinations', targetEntity: Question::class)]
        private readonly Question $question
    )
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
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
            $answer->setAnswerCorrectCombinations($this);
        }

        return $this;
    }

    public function hasAnswer(Answer $answer): bool
    {
        return $this->answers->contains($answer);
    }
}
