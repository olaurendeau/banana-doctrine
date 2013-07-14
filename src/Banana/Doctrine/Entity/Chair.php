<?php

namespace Banana\Doctrine\Entity;

use Banana\Doctrine\Entity\Desk;

/**
 * @Entity
 * @Table(name="chair")
 */
class Chair extends OutputableEntity
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
    protected $type;

    /**
     * @ManyToOne(targetEntity="Desk", inversedBy="chairs")
     * @JoinColumn(name="id_desk", referencedColumnName="id")
     */
    protected $desk;

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
     * @param mixed $desk
     */
    public function setDesk(Desk $desk)
    {
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
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return sprintf('Chair - id : "%s", type : "%s"'."\n", $this->id, $this->type);
    }
}