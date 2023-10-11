<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'answers')]
    private ?AnswerCorrectCombinations $answerCorrectCombinations;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Question::class,inversedBy: 'answers')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Question $question,

        #[ORM\Column(type: Types::TEXT)]
        private readonly string   $text
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getAnswerCorrectCombinations(): ?AnswerCorrectCombinations
    {
        return $this->answerCorrectCombinations;
    }

    public function setAnswerCorrectCombinations(AnswerCorrectCombinations $answerCorrectCombinations): self
    {
        $this->answerCorrectCombinations = $answerCorrectCombinations;

        return $this;
    }
}
