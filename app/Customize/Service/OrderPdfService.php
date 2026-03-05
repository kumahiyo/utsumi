<?php

namespace Customize\Service;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Shipping;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\ShippingRepository;
use Eccube\Service\OrderPdfService as EccubeOrderPdfService;
use Eccube\Service\TaxRuleService;
use Eccube\Twig\Extension\EccubeExtension;
use Eccube\Twig\Extension\TaxExtension;

/**
 * Class OrderPdfService.
 * Do export pdf function.
 */
class OrderPdfService extends EccubeOrderPdfService
{
    /**
     * OrderPdfService constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param OrderRepository $orderRepository
     * @param ShippingRepository $shippingRepository
     * @param TaxRuleService $taxRuleService
     * @param BaseInfoRepository $baseInfoRepository
     * @param EccubeExtension $eccubeExtension
     * @param TaxExtension $taxExtension
     *
     * @throws \Exception
     */
    public function __construct(EccubeConfig $eccubeConfig, OrderRepository $orderRepository, ShippingRepository $shippingRepository, TaxRuleService $taxRuleService, BaseInfoRepository $baseInfoRepository, EccubeExtension $eccubeExtension, TaxExtension $taxExtension)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->baseInfoRepository = $baseInfoRepository->get();
        $this->orderRepository = $orderRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRuleService = $taxRuleService;
        $this->eccubeExtension = $eccubeExtension;
        $this->taxExtension = $taxExtension;

        parent::__construct(
            $eccubeConfig, 
            $orderRepository, 
            $shippingRepository, 
            $taxRuleService, 
            $baseInfoRepository, 
            $eccubeExtension, 
            $taxExtension            
        );
    }

    /**
     * 購入者情報を設定する.
     *
     * @param Shipping $Shipping
     */
    protected function renderOrderData(Shipping $Shipping)
    {
        parent::renderOrderData($Shipping);

        // 基準座標を設定する
        $this->setBasePosition();

        // フォント情報のバックアップ
        $this->backupFont();

        // =========================================
        // 購入者情報部
        // =========================================

        $Order = $Shipping->getOrder();

        // 郵便番号
        $text = '〒'. $Shipping->getPostalCode();
        $this->lfText(27, 43, $text, 10);

        // 電話番号
        $text = 'TEL: '. $Shipping->getPhoneNumber();
        $this->lfText(57, 43, $text, 10);

        // =========================================
        // お買い上げ明細部
        // =========================================
        $this->SetFont(self::FONT_SJIS, '', 10);

        // 注文者IDと注文者名
        $Customer = $Order->getCustomer();
        if ($Customer) {
            $text = '会員番号: '. $Customer->getId();
            $this->lfText(21, 145, $text, 10);
            $text = '会員名: '. $Customer->getName01(). ' '. $Customer->getName02();
            $this->lfText(55, 145, $text, 10);
        }

        // フォント情報の復元
        $this->restoreFont();
    }

    /**
     * PDFに備考を設定数.
     *
     * @param array $formData
     */
    protected function renderEtcData(array $formData)
    {
        $id = $this->lastOrderId;
        $Shipping = $this->shippingRepository->find($id);
        $Order = $Shipping->getOrder();

        // フォント情報のバックアップ
        $this->backupFont();

        $this->SetFont(self::FONT_SJIS, '', 10);
        $this->Ln();
        $this->Ln();
        $this->SetX(21);

        $text = "支払方法: ". $Order->getPaymentMethod();
        $this->MultiCell(90, 6, $text, '', 2, 'L', 0, '');

        $text = '時間指定: ';
        $ShippingDeliveryTime = $Shipping->getShippingDeliveryTime();
        if ($ShippingDeliveryTime) {
            $text .= $ShippingDeliveryTime;
        }    
        $this->SetX(120);
        $this->MultiCell(60, 6, $text, '', 2, 'L', 0, '');

        $this->Cell(0, 10, '', 0, 1, 'C', 0, '');

        $this->SetFont(self::FONT_GOTHIC, 'B', 9);
        $this->MultiCell(0, 6, '＜ 備考 ＞', 'T', 2, 'L', 0, '');

        $this->SetFont(self::FONT_SJIS, '', 8);

        $this->Ln();
        // rtrimを行う
        $text  = preg_replace('/\s+$/us', '', $Order->getMessage());
        $text .= "\n\n";
        $text .= preg_replace('/\s+$/us', '', $formData['note1']."\n".$formData['note2']."\n".$formData['note3']);
        $this->MultiCell(0, 4, $text, '', 2, 'L', 0, '');

        // フォント情報の復元
        $this->restoreFont();
    }
}
