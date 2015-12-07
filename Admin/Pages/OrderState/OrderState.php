<?php
if(!$Auth->checkUserAccess(2))
{
    $PBH->showMessage("Omlouváme se, ale do této sekce nemáte přístup!", "warning");
    die();
}
?>
<h2 class="statistics">Stav objednávek: <?php $MH->loadOrderProcessingState(); ?></h2>

<table class="information statistics">
    <tr>
        <td>Objednávky čekající na vyřízení: </td>
        <td class="number"><?php $MH->loadWaitingOrders(); ?></td>
    </tr>

    <tr>
        <td>Vyřízené objednávky:</td>
        <td class="number"><?php $MH->loadProcessedOrders(); ?></td>
    </tr>

</table>
<p style="text-align: center"><small><em>Pokud je objednávek více jak 5 čekajících na zpracování, stav se mění na "nestíhá se vyřizovat".</em></small></p>
