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

    private function returnPostBackValue($index)
    {
        if(isset($_POST[$index]))
        {
            echo " value='" . $_POST[$index] . "' ";
        }
    }

    private function returnPostBackValuePlain($index)
    {
        if(isset($_POST[$index]))
        {
            echo $_POST[$index];
        }
    }

    public function loadAddSupplierForm()
    {
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        if(isset($_POST))
        {
            $p = $_POST;
        }

?>
<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>
                <tr>
                    <td>Název nového dodavatele</td>
                    <td><input class="text" type="text" name="supplierName" <?php $this->returnPostBackValue("supplierName"); ?>></td>
                    <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                </tr>

                <tr>
                    <td>Mail dodavatele:</td>
                    <td><input class="text" type="text" name="supplierMail"<?php $this->returnPostBackValue("supplierMail"); ?>></td>
                </tr>
                <!-- -->

                <tr>
                    <td>Telefon:</td>
                    <td><input class="text" type="text" name="supplierPhone"<?php $this->returnPostBackValue("supplierPhone"); ?>></td>
                </tr>

                <tr>
                    <td>Dostupnost zboží</td>
                    <td><input class="text" type="text" name="supplierResupplytime"<?php $this->returnPostBackValue("supplierResupplytime"); ?>></td>
                    <td>(doba ve dnech)</td>
                </tr>

                <tr>
                    <td>IČO</td>
                    <td><input class="text" type="text" name="supplierICO"<?php $this->returnPostBackValue("supplierICO"); ?>></td>
                </tr>

                <tr>
                    <td>Adresa</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <textarea name="supplierAddress" rows="3"><?php $this->returnPostBackValuePlain("supplierAddress");?></textarea>
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

    private function insertSupplier($supplierName, $supplierMail, $supplierPhone, $supplierRessuplytime, $supplierICO, $supplierAddress)
    {
        $insertSQL = "INSERT INTO supplier (sup_name, sup_mail, sup_phone, sup_resupplytime, sup_ico, sup_address, sup_enabled) VALUES
        ('$supplierName', '$supplierMail', '$supplierPhone', '$supplierRessuplytime', '$supplierICO', '$supplierAddress', 'true');";

        if($this->DBH->query($insertSQL) == -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function submitNewCategory()
    {
        // Simplication of $_POST data checking
        $p = $_POST;

        $addRecord = true;
        $errorMessage = "";

        if(isset($p["categoryName"], $p["categoryDescription"]))
        {

            $categoryName           =   $this->FILTER->prepareInputForSQL($p["categoryName"]);
            $categoryDescription    =   $this->FILTER->prepareInputForSQL($p["categoryDescription"]);

            if(empty($categoryName))
            {
                $errorMessage   .= "Název kategorie musí být vyplněn! <br>";
                $addRecord      = false;
            }

            if(empty($categoryDescription))
            {
                $errorMessage .= "Popisek kategorie musí být vyplněn! <br>";
                $addRecord     = false;
            }


            if($addRecord)
            {
                return $this->insertCategory($categoryName, $categoryDescription);
            }
            else
            {
                return $errorMessage;
            }

        }
        else
        {
            $errorMessage .= "Nedostatečná POST data přijata!";
            return $errorMessage;
        }
    }

    private function insertCategory($categoryName, $categoryDescription)
    {
        $insertQuery = $this->DBH->query("INSERT INTO product_category (pcat_name, pcat_description) VALUES ('$categoryName', '$categoryDescription')");

        if($insertQuery == -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function submitNewSubcategory()
    {
        // Simplification of $_POST data checking
        $p = $_POST;

        $addRecord = true;
        $errorMessage = "";

        if(isset($p["subcategoryName"], $p["subcategoryDescription"], $p["subcategoryCategory"]))
        {
            $subcategoryName    =   $this->FILTER->prepareInputForSQL($p["subcategoryName"]);
            $subcategoryDescription = $this->FILTER->prepareInputForSQL($p["subcategoryDescription"]);
            $subcategoryCategory    = $this->FILTER->prepareInputForSQL($p["subcategoryCategory"]);

            if(empty($subcategoryName))
            {
                $errorMessage .= "Název podkategorie musí být vyplněn!";
                $addRecord = false;
            }

            if(empty($subcategoryDescription))
            {
                $errorMessage   .=  "Popisek podkategorie musí být vyplněn!";
                $addRecord  = false;
            }

            if(empty($subcategoryCategory))
            {
                $errorMessage   .= "Nadřazená kategorie musí být uvedena!";
                $addRecord = false;
            }

            if(!is_numeric($subcategoryCategory))
            {
                $errorMessage   .= "Nadřazená kategorie musí obsahovat číselnou hodnotu (pravděpodobně děláte něco, co nemáte).";
                $addRecord  = false;
            }


            if($addRecord)
            {
                return $this->insertSubcategory($subcategoryName, $subcategoryDescription, $subcategoryCategory);
            }
            else
            {
                return $errorMessage;
            }


        }
        else
        {
            $errorMessage   .=  "Obdržena nedostatečná POST data!";
            return $errorMessage;
        }

    }

    private function insertSubcategory($subcategoryName, $subcategoryDescription, $subcategoryCategory)
    {
        $insertQuery = $this->DBH->query("INSERT INTO product_subcategory (psub_name, psub_description, psub_category) VALUES ('$subcategoryName', '$subcategoryDescription', '$subcategoryCategory')");

        if($insertQuery == -1)
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

        if(isset($p["supplierName"], $p["supplierResupplytime"], $p["supplierICO"], $p["supplierAddress"], $p["supplierPhone"], $p["supplierMail"]))
        {
            $supplierName           =   $this->FILTER->prepareInputForSQL($p["supplierName"]);
            $supplierRessuplytime   =   $this->FILTER->prepareInputForSQL($p["supplierResupplytime"]);
            $supplierICO            =   $this->FILTER->prepareInputForSQL($p["supplierICO"]);
            $supplierAddress        =   $this->FILTER->prepareInputForSQL($p["supplierAddress"]);
            $supplierMail           =   $this->FILTER->prepareInputForSQL($p["supplierMail"]);
            $supplierPhone          =   $this->FILTER->prepareInputForSQL($p["supplierPhone"]);

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

            if(empty($supplierAddress))
            {
                $addRecord = false;
                $errorMessage .= "Kontaktní informace na dodavatele je třeba uvést! <br>";
            }

            if(empty($supplierMail))
            {
                $addRecord = false;
                $errorMessage .= "Email dodavatele musí být uveden! <br>";
            }

            if(empty($supplierPhone))
            {
               $addRecord = false;
               $errorMessage .= "Email dodavatele musí být uveden! <br>";
            }

            if($addRecord)
            {
                return $this->insertSupplier($supplierName, $supplierMail, $supplierPhone, $supplierRessuplytime, $supplierICO, $supplierAddress);
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
            echo "<option value='$r[sup_id]'>" . $this->FILTER->prepareText($r["sup_name"]) . "</option>" . PHP_EOL;
        }
    }

    private function loadCategoryOptions()
    {
        $selectQuery = $this->DBH->query("SELECT pcat_id, pcat_name FROM product_category ORDER BY pcat_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[pcat_id]'>" . $this->FILTER->prepareText($r["pcat_name"]) . "</option>" . PHP_EOL;
        }
    }

    private function loadSubcategoryOptions()
    {
        $selectQuery = $this->DBH->query("SELECT psub_id, psub_name FROM product_subcategory ORDER BY psub_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[psub_id]'>" . $this->FILTER->prepareText($r["psub_name"]) . "</option>" . PHP_EOL;
        }
    }

    private function loadSubcategoryOptionsFor($categoryId)
    {
        $selectQuery = $this->DBH->query("SELECT psub_id, psub_name FROM product_subcategory WHERE psub_category = '$categoryId' ORDER BY psub_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[cat_id]'>" . $this->FILTER->prepareText($r["cat_name"]) . "</option>" . PHP_EOL;
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
                            <?php
                                $this->loadCategoryOptions();
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Subkategorie produktu</td>
                    <td>
                        <select name="productSubCategory">
                            <?php
                                $this->loadSubcategoryOptions();
                            ?>
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
            <td><?php echo $this->FILTER->prepareText($r["sup_name"]);      ?></td>
            <td><?php echo $this->FILTER->prepareText($r["sup_ico"]);       ?></td>
            <td><?php echo $this->FILTER->prepareText($r["sup_enabled"]);   ?></td>
        </tr>
            <?php
            $i++;
        }

        echo "</table>";

    }

    public function loadCategoryList()
    {
        $selectQuery = $this->DBH->query("SELECT pcat_id, pcat_name FROM product_category ORDER BY pcat_name ASC");


    echo "<table>";
    echo "<tr>";
    echo "<th>#</th>";
    echo "<th>Název kategorie</th>";
    echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]); ?></td>
            </tr>

            <?php
            $i++;
        }
    echo "</table>";
    }

    public function loadSubcategoryList()
    {
        $selectQuery = $this->DBH->query("SELECT psub_name, psub_category, pcat_name FROM product_category JOIN product_subcategory ON pcat_id=psub_category ORDER BY psub_name ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Název podkategorie</th>";
        echo "<th>Název nadřazené kategorie</th>";
        echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $this->FILTER->prepareText($r["psub_name"]); ?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]); ?></td>
            </tr>
            <?php
            $i++;
        }

        echo "</table>";
    }


    public function loadCategoryManagement()
    {
        ?>

<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>

                    <tr>
                        <td>Jméno kategorie:</td>
                        <td><input type="text" class="text" name="categoryName"></td>
                    </tr>

                    <tr>
                        <td>Popisek kategorie:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"><textarea name="categoryDescription"></textarea></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td><input type="submit" value="Přidat kategorii"></td>
                    </tr>
            </table>
        </form>
    </div>

    <hr>

    <div class="form_content">
        <form action="" method="post">
            <table>

                <tr>
                    <td>Jméno podkategorie:</td>
                    <td><input type="text" class="text" name="subcategoryName"></td>
                </tr>

                <tr>
                    <td>Nadřazená kategorie:</td>
                    <td><select name="subcategoryCategory">
                            <?php
                            $this->loadCategoryOptions();
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Popisek podkategorie:</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2"><textarea name="subcategoryDescription"></textarea></td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Přidat kategorii"></td>
                </tr>
            </table>
        </form>
    </div>

</div>
        <?php
    }
}
?>