<?php

namespace Banana\Doctrine\Command;

use Banana\Doctrine\Entity\Chair;
use Banana\Doctrine\Entity\Desk;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class PersistenceCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('banana:doctrine:persistence')
            ->addArgument('example', InputArgument::REQUIRED, 'Which example should we run ?')
            ->setDescription('Test doctrine persistence');
    }

    protected function accidentalPersistence()
    {
        //Create a new chair
        $chair = new Chair();
        $chair->setType("Old");

        $this->save($chair);

        $this->printEntity($chair, "Flushed chair");

        //Clean any reference
        $this->em->clear();
        unset($chair);

        //Fetch our good old chair from database
        $chair = $this->getChair(1);
        //$this->em->detach($chair);

        //And modified it a bit
        $chair->setType("New");

        $this->printEntity($chair, "Modified chair");

        $desk = new Desk();
        $desk->setShape('Squared');

        $this->printEntity($desk, "Not yet flushed desk");

        $this->save($desk);

        $this->printEntity($desk, "Flushed desk");

        //Clean any reference
        $this->em->clear();
        unset($desk);
        unset($chair);

        //Fetch again our chair who should still be old
        $chair = $this->getChair(1);
        $this->printEntity($chair, "Damn ! Modified chair have been flushed !");
    }
}