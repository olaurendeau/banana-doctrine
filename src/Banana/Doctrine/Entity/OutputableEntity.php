<?php

namespace Banana\Doctrine\Entity;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class OutputableEntity
{
    protected $output;

    public function setOutput(OutputInterface $output = null)
    {
        $this->output = $output;
    }

    /**
     * @PostUpdate
     */
    public function postUpdate()
    {
        if (!$this->output) {
            return;
        }

        $reflectionClass = new \ReflectionClass($this);

        $this->output->writeln(sprintf('<error>%s with id "%s" have been updated</error>', $reflectionClass->getShortName(), $this->id));
    }

    /**
     * @PostPersist
     */
    public function postPersist()
    {
        if (!$this->output) {
            return;
        }

        $reflectionClass = new \ReflectionClass($this);

        $this->output->writeln(sprintf('<error>%s with id "%s" have been persisted</error>', $reflectionClass->getShortName(), $this->id));
    }

}