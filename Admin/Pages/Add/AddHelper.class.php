<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Class AddHelper Helper class for module Add product/supplier
 * @Author Daniel Dušek <dusekdan@gmail.com>
 */
final class AddHelper implements IAdminModule
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

    public function loadAddSupplierForm()
    {
?>
<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>
                <tr>
                    <td>Název nového dodavatele</td>
                    <td><input class="text" type="text" name="supplierName"></td>
                </tr>

                <tr>
                    <td>Dostupnost zboží</td>
                    <td><input class="text" type="text" name="supplierResupplytime"></td>
                    <td>(doba ve dnech)</td>
                </tr>

                <tr>
                    <td>IČO</td>
                    <td><input class="text" type="text" name="supplierICO"></td>
                </tr>

                <tr>
                    <td>Kontaktní informace</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <textarea name="supplierContactInfo" rows="3"></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Přidat dodavatele"></td>
                </tr>
            </table>
        </form>
    </div>

</div>
<?php
    }

    private function insertSupplier($supplierName, $supplierRessuplytime, $supplierICO, $supplierContactInfo)
    {
        $insertSQL = "INSERT INTO supplier (sup_name, sup_contact, sup_resupplytime, sup_ico, sup_enabled) VALUES
        ('$supplierName', '$supplierContactInfo', '$supplierRessuplytime', '$supplierICO', 'true');";

        if($this->DBH->query($insertSQL) == -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function submitNewSupplier()
    {
        // Simplification of $_POST data checking
        $p = $_POST;

        $addRecord = true;
        $errorMessage =  "";

        if(isset($p["supplierName"], $p["supplierResupplytime"], $p["supplierICO"], $p["supplierContactInfo"]))
        {
            $supplierName           =   $this->FILTER->prepareInputForSQL($p["supplierName"]);
            $supplierRessuplytime   =   $this->FILTER->prepareInputForSQL($p["supplierResupplytime"]);
            $supplierICO            =   $this->FILTER->prepareInputForSQL($p["supplierICO"]);
            $supplierContactInfo    =   $this->FILTER->prepareInputForSQL($p["supplierContactInfo"]);

            if(empty($supplierName))
            {
                $addRecord = false;
                $errorMessage .= "Jméno dodavatele je třeba uvést! <br>";
            }

            if(!is_numeric($supplierRessuplytime))
            {
                $addRecord = false;
                $errorMessage .= "Dobu obnovy je třeba zadat jako číslo! <br>";
            }

            if(empty($supplierICO))
            {
                $addRecord = false;
                $errorMessage .= "IČO dodavatele je nutné uvést! <br>";
            }

            if(empty($supplierContactInfo))
            {
                $addRecord = false;
                $errorMessage .= "Kontaktní informace na dodavatele je třeba uvést! <br>";
            }

            if($addRecord)
            {
                return $this->insertSupplier($supplierName, $supplierRessuplytime, $supplierICO, $supplierContactInfo);
            }
            else
            {
                return $errorMessage;
            }
        }
        else
        {
            $errorMessage = "Insufficient data received (no POST data arrived).";
            return $errorMessage;
        }

    }

    private function loadSupplierSelectionOptions()
    {
        $selectQuery = $this->DBH->query("SELECT sup_name, sup_id FROM supplier ORDER BY sup_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[sup_id]'>" . $this->FILTER->prepareText($r[sup_name]) . "</option>" . PHP_EOL;
        }
    }

    public function loadAddProductForm()
    {
?>
<div class="form">
    <div class="form_content">
        <form action="skript.php" method="get">
            <table>
                <tr>
                    <td>Název nového produktu</td>
                    <td><input class="text" type="text" name="productName"></td>
                </tr>

                <tr>
                    <td>Dodavatel</td>
                    <td>
                        <select name="productSupplier">
                            <?php
                                $this->loadSupplierSelectionOptions();
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Kategorie produktu</td>
                    <td>
                        <select name="productCategory">
                            <option value="hs" selected="selected">Hlavní kategorie</option>
                            <option value="hs2" >Hlavní kategorie 2</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Subkategorie produktu</td>
                    <td>
                        <select name="productSubCategory">
                            <option value="hs" selected="selected">Vedlejší kategorie</option>
                            <option value="h2s" >Vedlejší kategorie 2</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Popis produktu</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <textarea name="productDescription" rows="3"></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Přidat produkt"></td>
                </tr>
            </table>
        </form>
    </div>

</div>
<?php
    }


    public function loadSupplierList()
    {

        $selectQuery = $this->DBH->query("SELECT sup_id, sup_name, sup_ico, sup_enabled FROM supplier ORDER BY sup_name ASC");

        echo "<table>";
            echo "<tr>";
                echo "<th>#</th>";
                echo "<th>Jméno dodavatele</th>";
                echo "<th>IČO</th>";
                echo "<th>Aktivní</th>";
            echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>
        <tr>
            <td><?php echo $i;                  ?></td>
            <td><?php echo $r["sup_name"];      ?></td>
            <td><?php echo $r["sup_ico"];       ?></td>
            <td><?php echo $r["sup_enabled"];   ?></td>
        </tr>
            <?php
            $i++;
        }

        echo "</table>";

    }
}
?>