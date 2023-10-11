<?php

namespace App\Entity;

use App\Quiz\Status\StatusEnum;
use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $started_at = null;

    #[ORM\ManyToMany(targetEntity: Question::class)]
    private Collection $questions;

    #[ORM\Column(length: 255)]
    private ?string $userId = null;

    #[ORM\Column]
    private int $status;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuizAnswer::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $quizAnswers;

    public function __construct()
    {
        $this->quizAnswers = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->status = StatusEnum::IN_PROGRESS->value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeImmutable $started_at): static
    {
        $this->started_at = $started_at;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function setStatus(StatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return StatusEnum::from($this->status);
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    /**
     * @return Collection<int, QuizAnswer>
     */
    public function getQuizAnswers(): Collection
    {
        return $this->quizAnswers;
    }

    public function addQuizAnswer(QuizAnswer $quizAnswer): static
    {
        if (!$this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers->add($quizAnswer);
        }

        return $this;
    }
}
