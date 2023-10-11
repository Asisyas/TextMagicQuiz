<?php

declare(strict_types=1);


namespace App\Command;

use App\Question\QuestionFacadeInterface;
use App\Shared\Transfer\AnswerAddTransfer;
use App\Shared\Transfer\QuestionAddTransfer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question as QuestionCli;

final class QuestionAddCommand extends Command
{
    public function __construct(
        private readonly QuestionFacadeInterface $questionFacade
    )
    {
        parent::__construct('question:add');
    }

    public function configure()
    {
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');

        $questionCli = new QuestionCli('Enter question text: ');
        $questionText = $helper->ask($input, $output, $questionCli);
        if(!$questionText) {
            $output->writeln('The question body cannot be empty');

            return self::INVALID;
        }

        $answers = [];
        while (true) {
            $questionCli = new QuestionCli('Enter answer text: ', null);
            $answerText = $helper->ask($input, $output, $questionCli);
            if($answerText === null) {
                break;
            }

            $isCorrectQuestion = new ConfirmationQuestion('Is it correct answer ? (Y/n): ', true);
            $isCorrect = $helper->ask($input, $output, $isCorrectQuestion);
            $answers[] = new AnswerAddTransfer(
                $answerText,
                $isCorrect,
            );
        }

        $this->questionFacade->createQuestion(new QuestionAddTransfer(
            $questionText,
            ...$answers,
        ));

        return self::SUCCESS;
    }
}
