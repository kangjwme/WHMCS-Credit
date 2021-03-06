<?php

use WHMCS\View\Menu\Item as MenuItem;

# 側邊欄添加，創建框架
add_hook('ClientAreaSecondarySidebar', 1, function(MenuItem $primarySidebar){
    $filename = APP::getCurrentFileName();
    $client = Menu::context("client");
    $clientid = intval( $client->id );
    $action = $_GET['action'];
    $allowed = array('invoices', 'quotes', 'masspay', 'addfunds');

# 對未登入者隱藏
    if ($filename!=='clientarea' || $clientid===0 || strpos($_SERVER['REQUEST_URI'], 'verificationId') !== false || is_null($client)) {
        return;
    }

    $primarySidebar->addChild('Client-Balance', array(
        'label' => Lang::trans('availcreditbal'),
        'uri' => '#',
        'order' => '1',
        'icon' => 'fa-money'
    ));

    # 爬取當前餘額
    $getCurrency = getCurrency($clientid);
    $balanceDisplay = formatCurrency($client->credit, $getCurrency);

    $balancePanel = $primarySidebar->getChild('Client-Balance');

    #讓餘額顯示永遠在最底下
    $balancePanel->moveToBack();
    $balancePanel->setOrder(0);

    # 顯示餘額
    $balancePanel->addChild('balance-amount', array(
        'uri' => 'clientarea.php?action=addfunds',
        'label' => '<h4 style="text-align:center;">'.$balanceDisplay.'</h4>',
        'order' => 1
    ));

    $balancePanel->setFooterHtml(
        '<a href="clientarea.php?action=addfunds" class="btn btn-success btn-sm btn-block">
            <i class="fa fa-plus"></i>加值 </a>'
    );
});
