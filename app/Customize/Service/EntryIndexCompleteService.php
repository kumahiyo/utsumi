<?php

namespace Customize\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\BaseInfo;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Event\TemplateEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntryIndexCompleteService
{
    /**
     * 会員登録ページでポイントを付与する
     */
    public function setPoint(EventArgs $event, ContainerInterface $container)
    {
        $em = $container->get('doctrine')->getManager();

        $form = $event->getArgument('form');
        $Customer = $event->getArgument('Customer');

        $BaseInfo = $em->getRepository('Eccube\Entity\BaseInfo')->get();
        $point = $BaseInfo->getEntryPointValue();

        if ($point && $point > 0) {
          $Customer->setPoint($point);

          $em->persist($Customer);
          $em->flush();
        }
    }
}
