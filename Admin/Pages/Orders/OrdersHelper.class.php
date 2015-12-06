<?php
class OrdersHelper implements IAdminModule
{
    /**
     * Helper properties for variables passed to constructor enforced by IAdminModule interface
     */
    /// Database helper
    private $DBH;
    /// Authentication helper (typically for determining whether user has privileges to perform operation)
    private $AUTH;
    /// InputFilter - helps work with user input (or other not secure strings)
    private $FILTER;

    private $postBackInfo = "";
    private $postBackType = "info";

    const PRODUCT_DEFAULT_URL = "HTTP_DEFAULT_URL_FILLIN";

    public function getPostBackInfo()
    {
        return $this->postBackInfo;
    }


    /**
     * Constructor (enforced by IAdminModule interface)
     * @param $DBDriver MySQLDriver object
     * @param $Auth Auth object
     * @param $IF InputFilter object
     */
    public function __construct($DBDriver, $Auth, $IF)
    {
        // Assigning references to internal properties
        $this->DBH      = $DBDriver;
        $this->AUTH     = $Auth;
        $this->FILTER   = $IF;
    }


    /**
     * Gets module version (enforced by IAdminModule interface)
     * @return float Module version
     */
    public function _getModuleVersion()
    {
        return 0.1;
    }


    /**
     * Gets name of the author of the module (enforced by interface)
     * @return string
     */
    public function _getModuleAuthor()
    {
        return "Daniel Dušek &lt;dusekdan@gmail.com&gt;";
    }


    /**
     * Returns HTML table containing full description of the module (enforced by interface)
     * @return string
     */
    public function _getModuleDescription()
    {
        // Tabs and line breaks are used for formatting resulting in readable HTML code
        $description = "<table>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Module name:</td>" . PHP_EOL . "\t\t<td>Product/Supplier add</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Author:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleAuthor() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Version:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleVersion() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Description:</td>" . PHP_EOL . "\t\t<td>This module allows user (employee) to add products to the system as well as suppliers.</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "</table>" . PHP_EOL;

        return $description;
    }


    public function loadOrderHistory()
    {
        $orderHistorySelect = $this->DBH->query("SELECT ord_time, ord_processed, ord_id, cust_firstname, cust_lastname FROM orders JOIN customer ON cust_id = ord_orderedby WHERE ord_processed='true' ORDER BY ord_time ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th>Číslo objednávky</th><th>Čas objednání</th><th>Jméno zákazníka</th><th>Částka</th><th>Stav</th>";
        echo "</tr>";
        while($r = mysql_fetch_assoc($orderHistorySelect))
        {

            // Calculate the price of given order
            $orderPrice = $this->calculateOrderPrice($r["ord_id"]);

            echo "<tr>";
            echo "<td>$r[ord_id]</td><td>$r[ord_time]</td><td>$r[cust_firstname] $r[cust_lastname]</td><td>$orderPrice Kč</td>";
            echo "<td>Vyřízena";
            $this->loadOrderItems($r["ord_id"]);
            echo "</td>";
            echo "<td><a href='Admin.php?action=Orders&amp;type=print&amp;orderid=$r[ord_id]'>TISK</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    private function loadProductOptions()
    {
        $selectProduct = $this->DBH->query("SELECT pr_name, pr_id FROM product ORDER BY pr_name ASC");
        while($r = mysql_fetch_assoc($selectProduct))
        {
            echo "<option value='$r[pr_id]'>$r[pr_name]</option>";
        }
    }

    public function loadResupplyForm()
    {
        ?>

        <form method="post" action="">
            <table>
                <tr>
                    <td><strong>Produkt</strong>*</td>
                    <td><select style="width: 450px;" name="resupplyProduct">
                            <?php
                                $this->loadProductOptions();
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Množství</strong>*:</td>
                    <td><input name="resupplyQuantity" type="text" class="text" value="10"></td>
                    <td><small>(hodnota je předvyplněna pro usnadnění opravování)</small></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Doobjednat zboží" class="button"></td>
                </tr>
            </table>
        </form>

        <?php
    }

    public function resupplyProduct()
    {
        $addRecord = true;
        $this->postBackInfo = "";
        $p = $_POST;

        $id = $this->FILTER->prepareInputForSQL($p["resupplyProduct"]);
        $quantity = $this->FILTER->prepareInputForSQL($p["resupplyQuantity"]);

        if(!is_numeric($quantity))
        {
            $this->postBackInfo .= "Množství musí být číselná hodnota! <br>";
            $addRecord = false;
        }

        if(empty($id))
        {
            $this->postBackInfo .= "Produkt k doplnění musí být uveden! <br>";
            $addRecord = false;
        }

        if($addRecord)
        {
            $resupply = $this->DBH->query("UPDATE product SET pr_quantity=pr_quantity+$quantity WHERE pr_id='$id'");
        }
        else
        {
            return false;
        }

        if($resupply === -1)
        {
            return false;
        }
        return true;

    }

    public function loadUnprocessedOrders()
    {
        echo "<table>";
        echo "<tr>";
        echo "<th>Číslo objednávky</th><th>Čas objednání</th><th>Jméno zákazníka</th><th>Částka</th><th>Stav</th>";
        echo "</tr>";
        $selectQuery = $this->DBH->query("SELECT ord_time, ord_processed, ord_id, cust_firstname, cust_lastname FROM orders JOIN customer ON cust_id = ord_orderedby WHERE ord_processed='false' ORDER BY ord_time ASC");
        while($r = mysql_fetch_assoc($selectQuery))
        {

            // Calculate the price of given order
            $orderPrice = $this->calculateOrderPrice($r["ord_id"]);
            // Determine status of the given order
            $orderStatus = $this->determineOrderStatus($r["ord_id"]);

            echo "<tr>";
            echo "<td>$r[ord_id]</td><td>$r[ord_time]</td><td>$r[cust_firstname] $r[cust_lastname]</td><td>$orderPrice</td>";
            if($orderStatus == "doable")
            {
                echo "<td>Lze vyřídit";

                $this->loadOrderItems($r["ord_id"]);

                echo "</td>";
                echo "<td><form method='post' action=''><input type='hidden' value='$r[ord_id]' name='orderId'><input name='processOrder' type='submit' value='Vyřídit' class='button'></form></td>";
                echo "<td><a href=''>TISK</a></td>";
            }
            else
            {
                echo "<td>Nelze vyřídit";
                $this->loadOrderItems($r["ord_id"]);
                echo "<em>$this->postBackInfo</em>";
                echo "</td>";
                echo "<td><a href='Admin.php?action=Orders&amp;type=resupply'>Doobjednat zboží</a></td>";
                echo "<td><a href='Admin.php?action=Orders&amp;type=print&amp;orderid=$r[ord_id]'>TISK</a></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    public function showOrderToPrint($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $r = $this->DBH->fetch("SELECT ord_time, ord_processed, ord_id, cust_firstname, cust_lastname FROM orders JOIN customer ON cust_id = ord_orderedby WHERE ord_id='$id'");

        $orderStatus = $r["ord_processed"]=="false"?"Nevyřízena":"Vyřízena";

        echo "<a href='javascript:print();'>Klikněte pro tisk</a>";
        echo "<table>";
        echo "<tr><td>Objednávka č. $r[ord_id]</td><td>Čas objednání: $r[ord_time]</td></tr>";
        echo "<tr><td>Objednal: $r[cust_firstname] $r[cust_lastname]</td><td>Stav: $orderStatus</td></tr>";
        echo "<tr><td>Položky:</td><td>";
        $this->loadOrderItems($r["ord_id"]);
        echo "</td></tr>";
        echo "</table>";

    }

    public function processOrder($orderId)
    {
        $orderId = $this->FILTER->prepareInputForSQL($orderId);

        // Update numbers for products available
        $orderItemSelection = $this->DBH->query("SELECT * FROM order_product WHERE ordp_order='$orderId'");

        // Iterating over ordered items
        while($r = mysql_fetch_assoc($orderItemSelection))
        {
            $orderedItemQuantity = $r["ordp_quantity"];
            $productId           = $r["ordp_product"];
            // Update quantity of the product
            $updateItemQuantity = $this->DBH->query("UPDATE product SET pr_quantity=pr_quantity-$orderedItemQuantity WHERE pr_id='$productId'");

            if($updateItemQuantity === -1)
            {
                echo "Nepodařilo se změnit počet produktů na skladě, sorry.";
                return false;
            }

            // Check whether zero has been reached
            $selectRemainingNumber = $this->DBH->fetch("SELECT pr_quantity FROM product WHERE pr_id='$productId'");

            // If positive update product availability
            if($selectRemainingNumber["pr_quantity"] == 0)
            {
                $updateItemAvailability = $this->DBH->query("UPDATE product SET pr_available = 'false' WHERE pr_id='$productId'");
                if($updateItemAvailability === -1)
                {
                    echo "Nepodařilo se upravit dostupnost produktu.";
                    return false;
                }
            }
        }

        // Update order status
        $updateOrderStatus = $this->DBH->query("UPDATE orders SET ord_processed='true' WHERE ord_id='$orderId'");
        if($updateOrderStatus === -1)
        {
            echo "Nepodařilo se změnit stav objednávky!";
            return false;
        }

        return true;
    }

    private function loadOrderItems($orderId)
    {
        $orderId = $this->FILTER->prepareInputForSQL($orderId);

        $selectOrderItems = $this->DBH->query("SELECT ordp_product, ordp_quantity, pr_price, pr_name FROM order_product JOIN product ON pr_id = ordp_product WHERE ordp_order = '$orderId'");
        echo "<ul>";
        while($r = mysql_fetch_assoc($selectOrderItems))
        {
            echo "<li>$r[ordp_quantity]× $r[pr_name] ($r[pr_price] Kč/ks)</li>";
        }
        echo "</ul>";
    }

    private function calculateOrderPrice($orderId)
    {
        $orderId = $this->FILTER->prepareInputForSQL($orderId);

        $selectOrderItems = $this->DBH->query("SELECT ordp_product, ordp_quantity, pr_price FROM order_product JOIN product ON pr_id = ordp_product WHERE ordp_order = '$orderId'");

        $totalPrice = 0;
        while($r = mysql_fetch_assoc($selectOrderItems))
        {
            $totalPrice += ($r["pr_price"] * $r["ordp_quantity"]);
        }

        return $totalPrice;

    }

    private function determineOrderStatus($orderId)
    {
        $orderId = $this->FILTER->prepareInputForSQL($orderId);

        $selectOrderItems = $this->DBH->query("SELECT ordp_product, ordp_quantity, pr_price, pr_quantity, pr_name FROM order_product JOIN product ON pr_id = ordp_product WHERE ordp_order = '$orderId'");

        $orderStatus = "doable";
        $this->postBackInfo = "";
        while($r = mysql_fetch_assoc($selectOrderItems))
        {
            if($r["ordp_quantity"] > $r["pr_quantity"])
            {
                $orderStatus = "notdoable";
                $this->postBackInfo .= "Zákazník si přeje koupit " . $r["ordp_quantity"] . "ks $r[pr_name], ale skladem je k dispozici pouze " . $r["pr_quantity"] . " ks.<br>";
            }
        }

        return $orderStatus;
    }

}

?>