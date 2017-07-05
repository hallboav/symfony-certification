<?php
namespace App\Command\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ProcessHelperCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('helper:process')
            ->setDescription('Show how process helper works')
            ->setHelp('');

        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('process');

        $process = $helper->run(
            $output,
            ['/usr/bin/figlet', sprintf('Hi %s.', $input->getArgument('username'))],
            'That\'s an error.',
            function ($type, $buffer) {
                if (Process::ERR === $type) {
                    // stderr
                } else {
                    // stdout
                }
            }
        );

        $output->writeln($process->getOutput());
    }
}
