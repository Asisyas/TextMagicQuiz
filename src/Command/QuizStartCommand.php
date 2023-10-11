<?php

declare(strict_types=1);


namespace App\Command;

use App\Quiz\Exception\QuizInitFailedException;
use App\Quiz\Factory\QuizEntityFactoryInterface;
use App\Quiz\QuizFacadeInterfaceAndQuizIdBy;
use App\Shared\Transfer\QuizAnswerVariantTransfer;
use App\Shared\Transfer\QuizInitTransfer;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;
use App\Shared\Transfer\QuizTransfer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class QuizStartCommand extends Command
{
    const CMD_NAME = 'quiz:start';

    const ARG_USERNAME = 'username';

    private QuizTransfer|null $quiz;

    private string $username;

    private int $quizId;

    public function __construct(
        private readonly QuizFacadeInterfaceAndQuizIdBy $quizFacade
    )
    {
        parent::__construct(self::CMD_NAME);

        $this->quiz = null;
    }

    public function configure(): void
    {
        $this->addArgument(self::ARG_USERNAME, InputArgument::REQUIRED, <<<EOF
User identifier.
For example `SomeUserName`
EOF
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = new QuestionHelper();
        while (true) {
            $this->startQuiz($input, $output);
            $questionAgain = new ConfirmationQuestion("Try again (y/N) ? ", false);
            if (!$questionHelper->ask($input, $output, $questionAgain)) {
                $output->writeln('Thank you, bye.');
                break;
            }
        }

        return self::SUCCESS;
    }

    public function startQuiz(InputInterface $input, OutputInterface $output): void
    {
        $this->username = $input->getArgument(self::ARG_USERNAME);
        $this->initQuiz($output);
        $this->quiz = $this->quizFacade->findCurrentQuizByUserId($this->username);
        $questionHelper = new QuestionHelper();

        foreach ($this->quiz->questions as $index => $questionTransfer) {
            $questionIndex = $index + 1;
            if ($questionTransfer->isCorrect !== null) {
                continue;
            }

            $this->displayQuestionsBlock($output);
            $output->writeln("\n<comment>Question " . $questionIndex . ":</comment>");
            $output->writeln("<info>{$questionTransfer->text}</info>\n");

            $availableAnswersAutocomplete = [];
            $chooseQuestionAnswers = array_combine(range(1, count($questionTransfer->variants)),
                array_map(
                    function (QuizAnswerVariantTransfer $variantTransfer) use (&$availableAnswersAutocomplete) {
                        $availableAnswersAutocomplete[] = count($availableAnswersAutocomplete) + 1;
                        return $variantTransfer->text;
                    },
                    $questionTransfer->variants
                )
            );

            $choiceQuestion = new ChoiceQuestion("\nChoose variant (comma separated):", $chooseQuestionAnswers);
            $choiceQuestion
                ->setAutocompleterValues([])
                ->setMultiselect(true)
                ->setValidator(function (?string $value) use ($availableAnswersAutocomplete): array {
                    if (!$value) {
                        throw new \Exception('Choose your answer, please.');
                    }

                    $values = array_map('trim', explode(',', $value));
                    if (count(array_diff($values, $availableAnswersAutocomplete)) > 0) {
                        throw new \Exception('Invalid answer value. Please. Select an answer option from those provided.');
                    }

                    return $values;
                });
            $answersIndex = $questionHelper->ask($input, $output, $choiceQuestion);
            $answers = [];
            foreach ($answersIndex as $aIdx) {
                $answers []= $questionTransfer->variants[$aIdx - 1]->id;
            }

            $this->quizFacade->makeQuestionAnswer(new QuizQuestionAnswerTransfer(
                $answers,
                $questionTransfer->id,
            ), $this->username);

            $this->refreshQuiz();
        }

        $this->displayQuestionsBlock($output);
        $output->writeln('<info>Quiz completed !</info>');
    }

    private function refreshQuiz(): void
    {
        $this->quiz = $this->quizFacade->findQuizByUserAndQuizId($this->username, $this->quizId);
    }

    private function initQuiz(OutputInterface $output): void
    {
        try {
            $quizCreated = $this->quizFacade->quizInit(new QuizInitTransfer($this->username));
            $this->quizId = $quizCreated->quizId;
            $output->writeln(sprintf('New Quiz №%d was created.', $quizCreated->quizId));
        } catch (QuizInitFailedException $exception) {
            $currentQuiz = $this->quizFacade->findCurrentQuizByUserId($this->username);
            $this->quizId = $currentQuiz->quizId;
            $output->writeln(sprintf('You are continuing an unfinished quiz №%s', $currentQuiz->quizId));
        }
    }

    private function redraw(OutputInterface $output): void
    {
        $output->write("\033[2J\033[H");
    }

    private function displayQuestionsBlock(OutputInterface $output): void
    {
        $this->redraw($output);
        $output->writeln('<comment>QUESTIONS LIST:</comment>');

        foreach ($this->quiz->questions as $i => $question) {
            ++$i;
            if ($question->isCorrect) {
                $output->write('<bg=green;fg=black>' . $i . '</>');
            } elseif ($question->isCorrect === false) {
                $output->write('<bg=red;fg=black>' . $i . '</>');
            } else {
                $output->write('<bg=gray;fg=black>' . $i . '</>');
            }
            $output->write('  ');
        }

        $output->writeln('');
    }
}
