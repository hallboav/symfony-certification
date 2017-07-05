<?php
namespace App\Command\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressHelperCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('helper:progress')
            ->setDescription('Show how progress helper works')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ProgressBar::setFormatDefinition('status', '<comment>%current%</>/<info>%max%</> %my_placeholder%');
        ProgressBar::setFormatDefinition('status_nomax', '<comment>%current%</> %my_placeholder%');

        $maxSteps = 100;
        $progress = new ProgressBar($output, $maxSteps);

        $progress->setFormat('%current%/%max%');
        $progress->setFormat('status');

        $progress->setBarCharacter('>');
        $progress->setEmptyBarCharacter(' ');
        $progress->setProgressCharacter(' ');

        $progress->setMessage('PEW!', 'my_placeholder');

        $progress->start();

        for ($i = 0; $i < $maxSteps; ++$i) {
            $progress->advance();

            if ($i === 80 && 0 !== $progress->getMaxSteps()) {
                $progress->setMessage('Almost there.', 'my_placeholder');

                // $progress->clear();
                // $progress->display();
            }

            usleep(50000);
        }

        $progress->finish();
        $output->writeln('');
    }
}
