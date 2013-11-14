<?php

namespace Banana\Doctrine\Command;

use Banana\Doctrine\Entity\Chair;
use Banana\Doctrine\Entity\Desk;
use Banana\Doctrine\Entity\Student;
use Banana\Doctrine\Entity\StudentDetail;
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

    protected function persistCascade()
    {
        $student = new Student();
        $student->setName('Foo');

        //We have to persist / flush student first to allow StudentDetail to use Student::id
        $this->em->persist($student);
        $this->em->flush($student);

        $studentDetail = new StudentDetail();
        $studentDetail->setAge(48);

        $desk = new Desk();
        $desk->setShape('Squared');

        $student->setStudentDetail($studentDetail);
        $student->setDesk($desk);

        $this->printEntity($student, "Student");

        $this->em->persist($student);
        $this->em->flush($student);

        $this->printEntity($this->getStudent(1), "Modified Student");

        $this->em->clear();

        $student = $this->getStudent(1);

        $student->setName('Foo Bar');
        $student->getDesk()->setShape("Circled");
        $student->getStudentDetail()->setAge(75);

        //Changes will not be updated on database
        $this->em->flush($student);
        
        //$this->em->flush(array($student, $studentDetail, $desk));

        $this->em->clear();
        $this->printEntity($this->getStudent(1), "Modified StudentDetail");
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