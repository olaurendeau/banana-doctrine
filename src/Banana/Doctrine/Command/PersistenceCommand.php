<?php

namespace Banana\Doctrine\Command;

use Banana\Doctrine\Entity\Chair;
use Banana\Doctrine\Entity\Desk;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PersistenceCommand extends Command
{
    protected $app;
    protected $output;
    protected $em;

    protected function configure()
    {
        $this
            ->setName('banana:doctrine:persistence')
            ->addArgument('example', InputArgument::REQUIRED, 'Which example should we run ?')
            ->setDescription('Test doctrine persistence');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getSilexApplication();
        $this->output = $output;
        $this->em = $this->app['db.orm.em'];

        //Run example
        $this->{$input->getArgument('example')}();

        //Truncate all those craps
        $this->truncate();
    }

    private function accidentalPersistence()
    {
        //Create a new chair
        $chair = new Chair();
        //$chair->setOutput($this->output);
        $chair->setType("Old");

        $this->save($chair);

        $this->printEntity($chair, "Flushed chair");

        //Clean any reference
        $this->em->clear();
        unset($chair);

        //Retrieve our good old chair from database
        $chair = $this->getChair(1);
        //$this->em->detach($chair);

        //And modified it a bit
        $chair->setType("New");

        $this->printEntity($chair, "Modified chair");

        $desk = new Desk();
        //$desk->setOutput($this->output);
        $desk->setShape('Squared');

        $this->printEntity($desk, "Not yet flushed desk");

        $this->save($desk);

        $this->printEntity($desk, "Flushed desk");

        //Clean any reference
        $this->em->clear();
        unset($desk);
        unset($chair);

        //Retrieve again our chair who should still be old
        $chair = $this->getChair(1);
        $this->printEntity($chair, "Damn ! Modified chair have been flushed !");
    }

    private function getChair($id)
    {
        $chair = $this->em->find('Banana\Doctrine\Entity\Chair', $id);
        //$chair->setOutput($this->output);

        return $chair;
    }

    private function getDesk($id)
    {
        $desk = $this->em->find('Banana\Doctrine\Entity\Desk', $id);
        //$desk->setOutput($this->output);

        return desk;
    }

    private function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    private function printEntity($entity, $message = null)
    {
        if ($message) {
            $this->output->writeln('<comment>'.$message.'</comment>');
        }
        if (isset($entity)) {
            $this->output->writeln('<info>'.$entity.'</info>');
        }
    }

    private function truncate()
    {
        $connection = $this->em->getConnection();
        $connection->query("SET FOREIGN_KEY_CHECKS = 0");
        $connection->query("TRUNCATE chair");
        $connection->query("TRUNCATE desk");
        $connection->query("SET FOREIGN_KEY_CHECKS = 1");
    }
}