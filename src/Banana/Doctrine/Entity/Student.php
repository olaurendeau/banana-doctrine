<?php


namespace Banana\Doctrine\Entity;


/**
 * @Entity
 * @Table("student")
 */
class Student
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
    protected $name;

    /**
     * @OneToOne(targetEntity="Desk", mappedBy="student", cascade={"all"})
     */
    protected $desk;

    /**
     * @OneToOne(targetEntity="Banana\Doctrine\Entity\StudentDetail", mappedBy="student", cascade={"all"})
     */
    protected $studentDetail;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $desk
     */
    public function setDesk($desk)
    {
        $desk->setStudent($this);
        $this->desk = $desk;
    }

    /**
     * @return mixed
     */
    public function getDesk()
    {
        return $this->desk;
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
     * @param mixed $studentDetail
     */
    public function setStudentDetail($studentDetail)
    {
        $studentDetail->setStudent($this);

        $this->studentDetail = $studentDetail;
    }

    /**
     * @return mixed
     */
    public function getStudentDetail()
    {
        return $this->studentDetail;
    }

    public function __toString()
    {
        $out = sprintf('Student - id : "%s", name : "%s"'."\n", $this->id, $this->name);
        $out .= $this->getStudentDetail();
        $out .= $this->getDesk();

        return $out;
    }
}