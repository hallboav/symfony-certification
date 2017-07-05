<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VerbosityCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('verbosity:levels')
            ->setDescription('Show how verbosity levels works')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln('$output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE');
        }

        $output->writeln(
            'Will only be printed in verbose mode or higher',
            OutputInterface::VERBOSITY_VERBOSE
        );

        $output->writeln(sprintf('isQuiet? %s', var_export($output->isQuiet(), true))); // never printed as true
        $output->writeln(sprintf('isVerbose? %s', var_export($output->isVerbose(), true)));
        $output->writeln(sprintf('isVeryVerbose? %s', var_export($output->isVeryVerbose(), true)));
        $output->writeln(sprintf('isDebug? %s', var_export($output->isDebug(), true)));
    }
}
