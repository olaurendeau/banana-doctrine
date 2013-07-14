<?php

namespace Banana\Doctrine\Command;

use Banana\Doctrine\Entity\Chair;
use Banana\Doctrine\Entity\Desk;
use Banana\Doctrine\Entity\Student;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;

class AutoFetchCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('banana:doctrine:auto-fetch')
            ->addArgument('example', InputArgument::REQUIRED, 'Which example should we run ?')
            ->setDescription('Test doctrine auto fetch of relationship');
    }

    protected function autoFetch()
    {
        //Create a desk with associated chairs and student
        $student = new Student();
        $student->setName("John doe");

        $desk = new Desk();
        $desk->setShape('Squared');

        $student->setDesk($desk);

        $i = 1;
        while ($i <= 2) {
            $chair = new Chair();
            $chair->setType("Old chair number ".$i);
            $desk->addChair($chair);
            $i++;
        }

        $this->save($student);

        $this->printEntity($student, "A student with his furnitures");

        $this->em->clear();
        unset($student);

        $student = $this->getStudent(1);
        //$student = $this->getStudentWithoutRelationShip(1);
        //$student = $this->getStudentWithoutRelationShipDQL(1);
        //$student = $this->getStudentWithRelationShip(1);
        $this->printEntity($student, "Student fetched from database");
    }

    public function getStudentWithoutRelationShip($id)
    {
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from('Banana\Doctrine\Entity\Student', 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getSingleResult();
    }

    public function getStudentWithoutRelationShipDQL($id)
    {
        return $this->em->createQuery("select s from Banana\Doctrine\Entity\Student s where s.id = :id")
            ->setParameter('id', $id)
            //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getSingleResult();
    }

    public function getStudentWithRelationShip($id)
    {
        return $this->em->createQueryBuilder()
            ->select('s, d, c')
            ->from('Banana\Doctrine\Entity\Student', 's')
            ->join('s.desk', 'd')
            ->join('d.chairs', 'c')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getSingleResult();
    }
}