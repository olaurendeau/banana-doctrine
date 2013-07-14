<?php

namespace Banana\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Console\Output\OutputInterface;

class OutputSubscriber implements EventSubscriber
{
    protected $output;

    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postUpdate,
            Events::postPersist,
            Events::postLoad,
            Events::preFlush,
            Events::postFlush
        );
    }

    public function preFlush()
    {
        $this->output->writeln("<error>Flush start</error>");
    }

    public function postFlush()
    {
        $this->output->writeln("<error>Flush end</error>");
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->printEntity($args->getEntity(), 'updated');
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->printEntity($args->getEntity(), 'persisted');
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->printEntity($args->getEntity(), 'loaded');
    }

    protected function printEntity($entity, $message)
    {
        $reflectionClass = new \ReflectionClass($entity);

        $this->output->writeln(sprintf('<comment>%s with id "%s" have been</comment> <error>%s</error>', $reflectionClass->getShortName(), $entity->getId(), $message));
    }
}