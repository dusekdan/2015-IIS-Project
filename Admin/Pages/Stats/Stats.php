<?php
if(!$Auth->checkUserAccess(2))
{
    $PBH->showMessage("Omlouváme se, ale do této sekce nemáte přístup!", "warning");
    die();
}
?>
    <br><h2 class="statistics">Statistiky posledního měsíce</h2>
<table class="information statistics">
    <tr>
        <td>Obrat: </td>
        <td class="number"><?php $MH->calculateMonthlyTurnOver(); ?> Kč</td>
    </tr>

    <tr>
        <td>Počet objednávek:</td>
        <td class="number"><?php $MH->calculateMonthlyOrders(); ?></td>
    </tr>

    <tr>
        <td>Počet prodaných položek:</td>
        <td class="number"><?php $MH->calculateMonthlySoldItems();?></td>
    </tr>
</table>

    <br><br><h2 class="statistics">Top 10 nejčastěji objednávaných produktů</h2>
<?php $MH->getTop10MostOrderedProducts(); ?>
    <br><br><h2 class="statistics">Top 10 nejaktivnějších zákazníků</h2>
<?php $MH->getTop10ActiveCustomers();?>