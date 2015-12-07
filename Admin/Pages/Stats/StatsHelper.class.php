<?php
class StatsHelper implements IAdminModule
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


    const PRODUCT_DEFAULT_URL = "HTTP_DEFAULT_URL_FILLIN";


    private $postBackInfo = "";
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

    public function calculateMonthlyTurnover()
    {
        $currentTime = new DateTime();
        $monthStart = $currentTime->format('Y-m-01 H:i:s');
        $monthEnd   = $currentTime->format('Y-m-31 H:i:s');

        $totalTurnover = 0;

        // Select orders
        $select = $this->DBH->query("SELECT ord_id FROM orders WHERE ord_time BETWEEN '$monthStart' AND '$monthEnd'");
        while($r = mysql_fetch_assoc($select))
        {
            // For each order calculate its outcome
            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_quantity FROM order_product JOIN product ON ordp_product = pr_id WHERE ordp_order='$r[ord_id]'");
            while($ir = mysql_fetch_assoc($itemSelect))
            {
                $totalTurnover += $ir['ordp_quantity']*$ir['pr_price'];
            }
        }
        echo $totalTurnover;

    }

    public function calculateMonthlyOrders()
    {
        $currentTime = new DateTime();
        $monthStart = $currentTime->format('Y-m-01 H:i:s');
        $monthEnd   = $currentTime->format('Y-m-31 H:i:s');
        $select = $this->DBH->fetch("SELECT count(ord_id) as numberOfOrders FROM orders WHERE ord_time BETWEEN '$monthStart' AND '$monthEnd'");
        echo $select["numberOfOrders"];
    }

    public function calculateMonthlySoldItems()
    {
        $currentTime = new DateTime();
        $monthStart = $currentTime->format('Y-m-01 H:i:s');
        $monthEnd   = $currentTime->format('Y-m-31 H:i:s');

        $totalCount = 0;

        // Select orders
        $select = $this->DBH->query("SELECT ord_id FROM orders WHERE ord_time BETWEEN '$monthStart' AND '$monthEnd'");
        while($r = mysql_fetch_assoc($select))
        {
            // For each order calculate its outcome
            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_quantity FROM order_product JOIN product ON ordp_product = pr_id WHERE ordp_order='$r[ord_id]'");
            while($ir = mysql_fetch_assoc($itemSelect))
            {
                $totalCount += $ir["ordp_quantity"];
            }
        }
        echo $totalCount;
    }

    public function getTop10ActiveCustomers()
    {
        $select = $this->DBH->query("SELECT count(*) totalcount, ord_orderedby, cust_firstname, cust_lastname FROM orders JOIN customer ON cust_id = ord_orderedby GROUP BY ord_orderedby ORDER BY count(*) DESC");

        echo "<table>";
        echo "<tr>";
        echo "<th>Jméno zákazníka</th><th>Počet objednávek</th>";
        echo "</tr>";

        while($r = mysql_fetch_assoc($select))
        {
            echo "<tr>";
            echo "<td>$r[cust_firstname] $r[cust_lastname]</td><td>$r[totalcount]</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function getTop10MostOrderedProducts()
    {
        $select = $this->DBH->query("SELECT count(*) soldtotal, pr_name FROM order_product JOIN product ON pr_id=ordp_product GROUP BY ordp_product");

        echo "<table>";
        echo "<tr>";
        echo "<th>Produkt</th><th>Počet objednávek</th>";
        echo "</tr>";

        while($r = mysql_fetch_assoc($select))
        {
            echo "<tr>";
            echo "<td>$r[pr_name]</td><td>$r[soldtotal]</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
}
?>