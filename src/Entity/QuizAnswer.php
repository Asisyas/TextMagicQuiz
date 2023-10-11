<?php

namespace App\Entity;

use App\Repository\QuizAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: QuizAnswerRepository::class)]
#[Table(uniqueConstraints: [
    new UniqueConstraint(name: "IDX_QUIZ_QUESTION", columns: ["quiz", "question"])
])]
class QuizAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        private readonly Question   $question,

        #[ORM\ManyToOne(inversedBy: 'quizAnswers')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Quiz       $quiz,

        #[ORM\ManyToMany(targetEntity: Answer::class, cascade: ['persist'])]
        private Collection $answers,

        #[ORM\Column(type: 'boolean')]
        private readonly bool       $isCorrect
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }
}
