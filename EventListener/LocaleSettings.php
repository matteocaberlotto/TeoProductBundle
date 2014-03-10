<?php

namespace Teo\ProductBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Teo\ProductBundle\Entity\Product;
use Teo\ProductBundle\Entity\Category;

class LocaleSettings implements EventSubscriber
{
    protected $session;

    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
        );
    }

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Product or $entity instanceof Category) {
            $entity->setCurrentLocale($this->session->get('_locale'));
        }
    }
}