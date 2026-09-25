<?php

namespace Plugin\EccubePaymentLite4\EventListener\EventSubscriber\Front\Mypage;

use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AddFlashBug implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::FRONT_MYPAGE_CHANGE_INDEX_COMPLETE => 'frontMypageChangeIndexComplete',
            EccubeEvents::FRONT_MYPAGE_DELIVERY_EDIT_COMPLETE => 'frontMypageDeliveryEditComplete',
        ];
    }

    public function frontMypageDeliveryEditComplete(EventArgs $eventArgs)
    {
        $this->session->getFlashBag()->add('eccube.front.warning', '定期購入中の方は、別途『定期お届け先の変更』手続きが必要です。ページ下部公式LINEまでご連絡ください');
    }

    public function frontMypageChangeIndexComplete(EventArgs $eventArgs)
    {
        $this->session->getFlashBag()->add('eccube.front.warning', '定期購入中の方は、別途『定期お届け先の変更』手続きが必要です。ページ下部公式LINEまでご連絡ください');
    }
}
