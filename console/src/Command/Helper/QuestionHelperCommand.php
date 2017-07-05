<?php
namespace App\Command\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class QuestionHelperCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('helper:question')
            ->setDescription('Show how question helper works')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // The stty command is used to get and set properties of the command line (such as getting the number
        // of rows and columns or hiding the input text). On Windows systems, this stty command may
        // generate gibberish output and mangle the input text. If that's your case, disable it.
        // $helper->disableStty();

        $default = '/etc/php/7.0/cli/php.ini';
        $question = new Question('Where is the php.ini file? ', $default);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // setAutocompleterValues
        $choices = ['red', 'blue', 'yellow'];
        $default = $choices[0];
        $question = new Question('Select your favorite colors (red, blue or yellow) ', $default);
        $question->setAutocompleterValues($choices);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // ConfirmationQuestion
        $default = false;
        $trueAnswerRegex = '/^y/i';
        $question = new ConfirmationQuestion('Continue with this action? [y/N] ', $default, $trueAnswerRegex);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln(var_export($answer, true));

        // setErrorMessage (disable validator)
        $choices = ['red', 'blue', 'yellow'];
        $default = array_search('red', $choices);
        $question = new ChoiceQuestion('Select your favorite color (defaults to red)', $choices, $default);
        $question->setErrorMessage('Color %s is invalid.');
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // setMultiselect & multiple choices
        $default = '0,1';
        $choices = ['red', 'blue', 'yellow'];
        $question = new ChoiceQuestion(
            'Select your favorite colors (comma separated, defaults to red and blue)',
            $choices,
            $default
        );

        $question->setMultiselect(true);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // setHidden & setHiddenFallback
        // When you ask for a hidden response, Symfony will use either a binary, change stty mode or
        // use another trick to hide the response. If none is available, it will fallback and allow
        // the response to be visible unless you set this behavior to false using setHiddenFallback().
        // In this case, a RuntimeException would be thrown.
        $question = new Question('What is the database password? ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // setNormalizer
        $question = new Question('Please enter the name of the bundle ', 'AppBundle');
        $question->setNormalizer(function ($value) {
            // $value can be null here
            return $value ? trim($value) : '';
        });

        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        // setValidator & setMaxAttempts
        $question = new Question('Please enter your username ');
        $question->setValidator(function ($value) {
            if ('' === trim($value)) {
                throw new \RuntimeException('The username cannot be empty');
            }

            return $value;
        });

        $question->setMaxAttempts(2);
        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);
    }
}
