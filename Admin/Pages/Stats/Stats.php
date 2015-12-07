<?php
if(!$Auth->checkUserAccess(2))
{
    $PBH->showMessage("Omlouváme se, ale do této sekce nemáte přístup!", "warning");
    die();
}
?>
<h2>Statistiky posledního měsíce</h2>
<table>
    <tr>
        <td>Obrat: </td>
        <td><?php $MH->calculateMonthlyTurnOver(); ?> Kč</td>
    </tr>

    <tr>
        <td>Počet objednávek:</td>
        <td><?php $MH->calculateMonthlyOrders(); ?></td>
    </tr>

    <tr>
        <td>Počet prodaných položek:</td>
        <td><?php $MH->calculateMonthlySoldItems();?></td>
    </tr>
</table>

<h2>Top 10 nejčastěji objednávaných produktů</h2>
<?php $MH->getTop10MostOrderedProducts(); ?>
<h2>Top 10 nejaktivnějších zákazníků</h2>
<?php $MH->getTop10ActiveCustomers();?>