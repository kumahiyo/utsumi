<?php

namespace Plugin\EccubePaymentLite4\Service;

use Eccube\Common\EccubeConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * イプシロン決済サーバーからの決済完了通知の送信元IPアドレスを検証する
 */
class PaymentNotificationIpValidator
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    /**
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * 送信元IPアドレスを検証する
     *
     * @param Request $request
     * @return bool 許可されたIPアドレスの場合はtrue、それ以外はfalse
     */
    public function validate(Request $request): bool
    {
        $allowedIps = $this->eccubeConfig['gmo_epsilon']['allowed_notification_ips'];
        if (empty($allowedIps)) {
            logs('gmo_epsilon')->info('決済完了通知IPアドレス検証: 許可IPリストが未設定のため検証をスキップします');
            return true;
        }

        $clientIp = $request->getClientIp();
        if ($clientIp === null) {
            logs('gmo_epsilon')->error('決済完了通知IPアドレス検証: IPアドレスを取得できませんでした');
            return false;
        }

        if (in_array($clientIp, $allowedIps, true)) {
            return true;
        }

        logs('gmo_epsilon')->error('決済完了通知IPアドレス検証: 許可されていないIPアドレスからのアクセスです。送信元IP=' . $clientIp . ', 許可リスト=[' . implode(', ', $allowedIps) . ']');
        return false;
    }
}
