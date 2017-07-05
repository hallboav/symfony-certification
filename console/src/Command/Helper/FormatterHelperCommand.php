<?php
namespace App\Command\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FormatterHelperCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('helper:formatter')
            ->setDescription('Show how formatter helper works')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('formatter');

        $style = new OutputFormatterStyle('red');
        $formatter = $output->getFormatter();
        $formatter->setStyle('blood', $style);

        $section = $helper->formatSection('MOTD', 'Symfony is great!', 'blood');
        $output->writeln($section);

        $large = true;
        $messages = ['Aw, Snap!', 'Something went wrong.'];
        $block = $helper->formatBlock($messages, 'error', $large);
        $output->writeln($block);

        $message = 'WAAAAAAAAW!';
        $truncated = $helper->truncate($message, 5, '...');
        $output->writeln($truncated);
    }
}
