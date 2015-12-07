<?php
if(!$Auth->checkUserAccess(2))
{
    $PBH->showMessage("Omlouváme se, ale do této sekce nemáte přístup!", "warning");
    die();
}
?>
<h2>Stav objednávek: <?php $MH->loadOrderProcessingState(); ?></h2>
<p><em>Pokud je objednávek více jak 5 čekajících na zpracování, stav se mění na "nestíhá se vyřizovat".</em></p>
<strong>Objednávky čekající na vyřízení:</strong> <?php $MH->loadWaitingOrders(); ?><br>
<strong>Vyřízené objednávky</strong>: <?php $MH->loadProcessedOrders(); ?>
