<?php
/**
 * Created by PhpStorm.
 * User: dovoedo
 * Date: 27/11/2019
 * Time: 09:44
 */

// src/EventListener/RequestListener.php
namespace UserBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogListener
{


    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
//        $em = $args->getEntityManager();

//        dump($entity);die();
        $this->logger->info('Mise à jour de l\'utilisateur : '.$entity->getId().'  '.$entity->getFirstName().' '.$entity->getLastName());
        /*if ($entity instanceof EntityA) {
            $entity->getEntityB()->setStatus($newStatus);
            $em->persist($entity);
            $em->flush();
        }*/
    }
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->logger->alert('Suppression de l\'utilisateur : '.$entity->getId().'  '.$entity->getFirstName().' '.$entity->getLastName());

    }
}