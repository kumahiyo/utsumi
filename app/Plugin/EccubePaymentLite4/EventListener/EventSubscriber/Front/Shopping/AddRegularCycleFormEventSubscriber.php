<?php

namespace Plugin\EccubePaymentLite4\EventListener\EventSubscriber\Front\Shopping;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Master\SaleType;
use Eccube\Entity\Order;
use Eccube\Event\TemplateEvent;
use Plugin\EccubePaymentLite4\Repository\ConfigRepository;
use Plugin\EccubePaymentLite4\Repository\RegularCycleRepository;
use Plugin\EccubePaymentLite4\Service\CalculateNextDeliveryDateService;
use Plugin\EccubePaymentLite4\Service\IsRegularPaymentService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AddRegularCycleFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;
    /**
     * @var IsRegularPaymentService
     */
    private $isRegularPaymentService;
    /**
     * @var ConfigRepository
     */
    private $configRepository;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RegularCycleRepository
     */
    private $regularCycleRepository;
    /**
     * @var CalculateNextDeliveryDateService
     */
    private $calculateNextDeliveryDateService;

    public function __construct(
        EccubeConfig $eccubeConfig,
        IsRegularPaymentService $isRegularPaymentService,
        ConfigRepository $configRepository,
        SessionInterface $session,
        RegularCycleRepository $regularCycleRepository,
        CalculateNextDeliveryDateService $calculateNextDeliveryDateService
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->isRegularPaymentService = $isRegularPaymentService;
        $this->configRepository = $configRepository;
        $this->session = $session;
        $this->regularCycleRepository = $regularCycleRepository;
        $this->calculateNextDeliveryDateService = $calculateNextDeliveryDateService;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopping/index.twig' => 'index',
            'Shopping/confirm.twig' => 'confirm',
        ];
    }

    public function index(TemplateEvent $event)
    {
        /** @var Order $Order */
        $Order = $event->getParameter('Order');

        /** @var SaleType $SaleType */
        $SaleType = $Order->getShippings()->first()->getDelivery()->getSaleType();

        if ($SaleType->getName() !== '定期商品') {
            return;
        }

        if (!$this->isRegularPaymentService->isRegularPayment($Order)) {
            return;
        }

        $event->addSnippet('@EccubePaymentLite4/default/Shopping/regular_cycle_form.twig');
    }

    public function confirm(TemplateEvent $event)
    {
        /** @var Order $Order */
        $Order = $event->getParameter('Order');
        /** @var FormView $form */
        $form = $event->getParameter('form');

        /** @var SaleType $SaleType */
        $SaleType = $Order->getShippings()->first()->getDelivery()->getSaleType();

        if ($SaleType->getName() !== '定期商品') {
            return;
        }

        if (!$this->isRegularPaymentService->isRegularPayment($Order)) {
            return;
        }
        // Get list product item
        $ProductItems = $Order->getProductOrderItems();
        $event->setParameter('ProductItems', $ProductItems);
        $event->setParameter('limitCycle', 5);
        $event->setParameter('label', $form->children['RegularCycles']->vars['data']);
        $event->addSnippet('@EccubePaymentLite4/default/Shopping/regular_cycle_confirm_form.twig');
    }
}
