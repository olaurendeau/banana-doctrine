<?php


namespace Banana\Doctrine\Entity;


/**
 * @Entity
 * @Table("student_detail")
 */
class StudentDetail
{
    /**
     * @Id
     * @OneToOne(targetEntity="Banana\Doctrine\Entity\Student")
     * @JoinColumn(name="id", referencedColumnName="id", nullable=false)
     */
    protected $student;

    /**
     * @Column(type="integer")
     */
    protected $age;

    public function getId()
    {
        if (!$this->student) {
            return "null";
        }


        return $this->student->getId();
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
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
        $out = sprintf('StudentDetail - id : "%s", age : "%s"'."\n", $this->student->getId(), $this->age);

        return $out;
    }
}