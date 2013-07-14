<?php

namespace Banana\Doctrine\Command;

use Banana\Doctrine\Subscriber\OutputSubscriber;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    protected $app;
    protected $output;
    protected $em;

    protected function configure()
    {
        $this->addOption('show', 's', InputOption::VALUE_NONE, 'Should we show everything ?');
        $this->addOption('truncate', null, InputOption::VALUE_NONE, 'Truncate all this noobs values');
        $this->addOption('no-truncate', 't', InputOption::VALUE_NONE, 'No truncate please !');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getSilexApplication();
        $this->output = $output;
        $this->em = $this->app['db.orm.em'];

        if ($input->getOption('truncate')) {
            $this->truncate();
            return;
        }

        if ($input->getOption('show')) {
            $this->em->getEventManager()->addEventSubscriber(new OutputSubscriber($output, $this->app));
        }

        //Run example
        $this->{$input->getArgument('example')}();

        //Truncate all those craps
        if (!$input->getOption('no-truncate')) {
            $this->truncate();
        }
    }

    protected function getChair($id)
    {
        return $this->em->find('Banana\Doctrine\Entity\Chair', $id);
    }

    protected function getDesk($id)
    {
        return $this->em->find('Banana\Doctrine\Entity\Desk', $id);
    }

    protected function getStudent($id)
    {
        return $this->em->find('Banana\Doctrine\Entity\Student', $id);
    }

    protected function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function printEntity($entity, $message = null)
    {
        if ($message) {
            $this->output->writeln('<comment>'.$message.'</comment>');
        }
        if (isset($entity)) {
            $this->output->writeln('<info>'.$entity.'</info>');
        }
    }

    protected function truncate()
    {
        $connection = $this->em->getConnection();
        $connection->query("SET FOREIGN_KEY_CHECKS = 0");
        $connection->query("TRUNCATE student");
        $connection->query("TRUNCATE chair");
        $connection->query("TRUNCATE desk");
        $connection->query("SET FOREIGN_KEY_CHECKS = 1");
    }
}