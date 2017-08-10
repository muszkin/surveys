<?php

namespace SurveyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailerTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailer:test')
            ->setDescription('...')
            ->addArgument('to', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $to = $input->getArgument('to');

        $mailer = $this->getContainer()->get('survey_mailer');
        $output->writeln(var_export($mailer->sendTestMail($to),true));

        $output->writeln('Command result.');
    }

}
