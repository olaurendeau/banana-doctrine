<?php

namespace Banana\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Banana\Doctrine\Entity\Chair;
use Banana\Doctrine\Entity\Student;

/**
 * @Entity
 * @Table(name="desk")
 */
class Desk
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $shape;

    /**
     * @OneToMany(targetEntity="Chair", mappedBy="desk", cascade={"all"})
     */
    protected $chairs;

    /**
     * @OneToOne(targetEntity="Student", inversedBy="desk", cascade={"all"})
     * @JoinColumn(name="id_student", referencedColumnName="id")
     */
    protected $student;


    public function __construct()
    {
        $this->chairs = new ArrayCollection();
    }

    /**
     * @param Chair $chair
     */
    public function addChair(Chair $chair)
    {
        $chair->setDesk($this);
        $this->chairs[] = $chair;
    }

    /**
     * @param mixed $chairs
     */
    public function setChairs($chairs)
    {
        foreach ($chairs as $chair) {
            $this->addChair($chair);
        }
    }

    /**
     * @return mixed
     */
    public function getChairs()
    {
        return $this->chairs;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $shape
     */
    public function setShape($shape)
    {
        $this->shape = $shape;
    }

    /**
     * @return mixed
     */
    public function getShape()
    {
        return $this->shape;
    }

    /**
     * @param mixed $student
     */
    public function setStudent($student)
    {
        $this->student = $student;
    }

    /**
     * @return mixed
     */
    public function getStudent()
    {
        return $this->student;
    }

    public function __toString()
    {
        $out = sprintf('Desk - id : "%s", shape : "%s"'."\n", $this->id, $this->shape);

        foreach ($this->chairs as $chair) {
            $out .= ' - '.$chair;
        }

        return $out;
    }
}