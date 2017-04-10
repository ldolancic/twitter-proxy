<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareTableForFulltextSearchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fulltext-search:prepare-tweets')
            ->setDescription('Prepare your tweets table for fulltext search')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbName = $this->getContainer()->getParameter('database_name');
        $dbHost = $this->getContainer()->getParameter('database_host');
        $dbUser = $this->getContainer()->getParameter('database_user');
        $dbPassword = $this->getContainer()->getParameter('database_password');

        $dsn = 'mysql:dbname=' . $dbName . ';host=' . $dbHost;

        try {
            $mysql = new \PDO($dsn, $dbUser, $dbPassword);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        $alter = $mysql->prepare("ALTER TABLE tweets ADD FULLTEXT INDEX search_index (content)");

        $alter->execute();

        $output->writeln('Index successfully generated :)');
    }

}
