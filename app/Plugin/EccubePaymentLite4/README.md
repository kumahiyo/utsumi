
# EccubePaymentLite4 プラグインのカスタマイズした点

2026/09/24
    EventListener/EventSubscriber/Front/Mypage/AddFlashBug.php
    表示文言変更

    <購入確認画面でお届けサイクルを選択しないと500エラー>
        app/Plugin/EccubePaymentLite4/Resource/locale/messages.ja.yaml
        150行目`regular_cycle_unselected`を追加

        app/Plugin/EccubePaymentLite4/Form/Extension/Front/Shopping/OrderTypeExtension.php
        188-197行目の処理およびそれに必要なクラスの読み込み
