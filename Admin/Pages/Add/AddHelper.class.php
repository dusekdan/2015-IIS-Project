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

    private $postBackInfo = "";
    private $postBackType = "info";

    const PRODUCT_DEFAULT_URL = "http://iis.fhfstudio.com/SI/default_picture.png";

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

?>
<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>
                <tr>
                    <td><strong>Název nového dodavatele</strong>*:</td>
                    <td><input class="text" type="text" name="supplierName" <?php $this->returnPostBackValue("supplierName"); ?>></td>
                    <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                </tr>

                <tr>
                    <td><strong>Mail dodavatele</strong>*:</td>
                    <td><input class="text" type="email" name="supplierMail"<?php $this->returnPostBackValue("supplierMail"); ?>></td>
                </tr>

                <tr>
                    <td>Telefon:</td>
                    <td><input class="text" type="text" name="supplierPhone"<?php $this->returnPostBackValue("supplierPhone"); ?>></td>
                    <td><small>(ve tvaru 721852506)</small></td>
                </tr>

                <tr>
                    <td><strong>Dostupnost zboží</strong>*:</td>
                    <td><input class="text" type="text" name="supplierResupplytime"<?php $this->returnPostBackValue("supplierResupplytime"); ?>></td>
                    <td><small>(doba ve dnech)</small></td>
                </tr>

                <tr>
                    <td>IČO:</td>
                    <td><input class="text" type="text" name="supplierICO"<?php $this->returnPostBackValue("supplierICO"); ?>></td>
                    <td><small>(nevyplněno="00000000")</small></td>
                </tr>

                <tr>
                    <td>Adresa:</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <textarea name="supplierAddress" rows="3"><?php $this->returnPostBackValuePlain("supplierAddress");?></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Přidat dodavatele" class="button"></td>
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

        if($this->DBH->query($insertSQL) === -1)
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
        // Reset internal property for last postback message
        $this->postBackInfo = "";

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

            // Removed cause of TASK SPEC
            /*if(empty($categoryDescription))
            {
                $errorMessage .= "Popisek kategorie musí být vyplněn! <br>";
                $addRecord     = false;
            }*/


            if($addRecord)
            {
                return $this->insertCategory($categoryName, $categoryDescription);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }

        }
        else
        {
            $errorMessage .= "Nedostatečná POST data přijata!";
            $this->postBackInfo = $errorMessage;
            return false;
        }
    }

    private function insertCategory($categoryName, $categoryDescription)
    {
        $insertQuery = $this->DBH->query("INSERT INTO product_category (pcat_name, pcat_description) VALUES ('$categoryName', '$categoryDescription')");

        if($insertQuery === -1)
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

            // Removed cause of TASK SPEC
            /*if(empty($subcategoryDescription))
            {
                $errorMessage   .=  "Popisek podkategorie musí být vyplněn!";
                $addRecord  = false;
            }*/

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
                $this->postBackInfo = $errorMessage;
                return false;
            }


        }
        else
        {
            $errorMessage   .=  "Obdržena nedostatečná POST data!";
            $this->postBackInfo = $errorMessage;
            return false;
        }

    }

    private function insertSubcategory($subcategoryName, $subcategoryDescription, $subcategoryCategory)
    {
        $insertQuery = $this->DBH->query("INSERT INTO product_subcategory (psub_name, psub_description, psub_category) VALUES ('$subcategoryName', '$subcategoryDescription', '$subcategoryCategory')");

        if($insertQuery === -1)
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
                $errorMessage .= "Dobu obnovy dostupnosti zboží je třeba zadat jako číslo! <br>";
            }

            // REMOVED 'cause of TASK SPEC
            if(empty($supplierICO))
            {
            //    $addRecord = false;
            //    $errorMessage .= "IČO dodavatele je nutné uvést! <br>";
                  $supplierICO = "00000000";
            }

            // REMOVED 'cause of TASK SPEC
            /*if(empty($supplierAddress))
            {
                $addRecord = false;
                $errorMessage .= "Kontaktní informace na dodavatele je třeba uvést! <br>";
            }*/

            if(empty($supplierMail))
            {
                $addRecord = false;
                $errorMessage .= "Email dodavatele musí být uveden! <br>";
            }

            if(!empty($supplierMail) && !$this->FILTER->isMail($supplierMail))
            {
                $addRecord = false;
                $errorMessage .= "Email musí být ve správném formátu! <br>";
            }

            if(!empty($supplierPhone) && (!is_numeric($supplierPhone) || strlen($supplierPhone) != 9))
            {
               $addRecord = false;
               $errorMessage .= "Telefon není v předepsaném formátu! <br>";
            }

            if($addRecord)
            {
                return $this->insertSupplier($supplierName, $supplierMail, $supplierPhone, $supplierRessuplytime, $supplierICO, $supplierAddress);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }
        }
        else
        {
            $errorMessage = "Insufficient data received (no POST data arrived).";
            $this->postBackInfo = $errorMessage;
            return false;
        }

    }

    private function loadSupplierSelectionOptions()
    {
        $selectQuery = $this->DBH->query("SELECT sup_name, sup_id FROM supplier ORDER BY sup_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            if(isset($_POST["productSupplier"]) && $_POST["productSupplier"] == $r["sup_id"])
            {
                $selectedAdd = " selected='selected' ";
            }
            else
            {$selectedAdd = "" ;}

            echo "<option $selectedAdd value='$r[sup_id]'>" . $this->FILTER->prepareText($r["sup_name"]) . "</option>" . PHP_EOL;
        }
    }

    private function loadCategoryOptions()
    {
        $selectQuery = $this->DBH->query("SELECT pcat_id, pcat_name FROM product_category ORDER BY pcat_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            if(isset($_POST["subcategoryCategory"]) && $_POST["subcategoryCategory"] == $r["pcat_id"])
            {
                $selectedAdd = " selected='selected' ";
            }
            else
            {
                $selectedAdd = "";
            }
            echo "<option $selectedAdd value='$r[pcat_id]'>" . $this->FILTER->prepareText($r["pcat_name"]) . "</option>" . PHP_EOL;
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

    public function submitNewProduct()
    {
        $p = $_POST;

        $addRecord = true;
        $errorMessage = "";

        if(isset($p["productName"], $p["productSupplier"], $p["productSubcategory"], $p["productDescription"]))
        {
            $productName = $this->FILTER->prepareInputForSQL($p["productName"]);
            $productSupplier = $this->FILTER->prepareInputForSQL($p["productSupplier"]);
            $productSubcategory = $this->FILTER->prepareInputForSQL($p["productSubcategory"]);
            $productDescription = $this->FILTER->prepareInputForSQL($p["productDescription"]);
            $productPrice = $this->FILTER->prepareInputForSQL($p["productPrice"]);
            $productInitialStock = $this->FILTER->prepareInputForSQL($p["productInitialStock"]);
            $productUrl = $this->FILTER->prepareInputForSQL($p["productUrl"]);

            if(empty($productName))
            {
                $addRecord = false;
                $errorMessage .= "Jméno produktu nesmí být prázdné! <br>";
            }

            if(empty($productSupplier))
            {
                $addRecord = false;
                $errorMessage .= "Není možné přidat produkt bez dodavatele! <br>";
            }

            if(empty($productSubcategory))
            {
                $addRecord = false;
                $errorMessage .= "Produkt musí spadat do nějaké vedlejší kategoriie! <br>";
            }


            // Removed cause of TASK SPEC
            /*if(empty($productDescription))
            {
                $addRecord = false;
                $errorMessage .= "Produkt musí mít popisek! <br>";
            }*/

            if(empty($productInitialStock))
            {
                $productInitialStock = 0;
            }

            if(!is_numeric($productInitialStock))
            {
                $addRecord = false;
                $errorMessage .= "Počáteční počet musí být zadán jako číslo!<br>";
            }

            if(empty($productPrice))
            {
                $addRecord = false;
                $errorMessage .= "Produkt musí mít nastavenou cenu! <br>";
            }

            if(!is_numeric($productPrice))
            {
                $addRecord = false;
                $errorMessage .= "Cena musí být zadána jako číslo!<br>";
            }

            if(empty($productUrl))
            {
                // TODO: Check this actually works
                $productUrl = self::PRODUCT_DEFAULT_URL;
            }


            if($addRecord)
            {
                return $this->insertProduct($productName, $productSupplier, $productSubcategory, $productDescription, $productPrice, $productUrl, $productInitialStock);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }

        }
        else
        {
            $errorMessage .= "Obdržena nedostatečná POST data!";
            $this->postBackInfo = $errorMessage;
            return false;
        }
    }

    private function insertProduct($productName, $productSupplier, $productSubcategory, $productDescription, $productPrice, $productUrl, $productInitialStock)
    {

        $productAddedBy = $_SESSION["emp_id"];
        $productAvailability = ($productInitialStock == 0) ? 'false' : 'true';

        // well apparently I have missed some columns too
        $insertQuery = $this->DBH->query("INSERT INTO product (pr_name, pr_description, pr_quantity, pr_available, pr_imageurl, pr_price, pr_subcategory, pr_addedby, pr_supplier, pr_addtime)
        VALUES ('$productName', '$productDescription', '$productInitialStock', '$productAvailability', '$productUrl', '$productPrice', '$productSubcategory', '$productAddedBy', '$productSupplier', NOW());");

        if($insertQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function loadProductCategoryOptions()
    {
        $selectCategoryQueries = $this->DBH->query("SELECT pcat_name, pcat_id FROM product_category ORDER BY pcat_name");

        while($r = mysql_fetch_assoc($selectCategoryQueries))
        {

            echo "<option disabled='disabled' value='$r[pcat_id]'>$r[pcat_name]</option>";

            $selectSubcategories = $this->DBH->query("SELECT psub_name, psub_id FROM product_subcategory WHERE psub_category = '".$r["pcat_id"]."'");
            while($sc = mysql_fetch_assoc($selectSubcategories))
            {
                if(isset($_POST["productSubcategory"]) && $_POST["productSubcategory"] == $sc["psub_id"])
                {
                    $selectedAdd = " selected='selected' ";
                }
                else
                {
                    $selectedAdd = "";
                }
                echo "<option $selectedAdd value='$sc[psub_id]'>&nbsp;&nbsp;$sc[psub_name]</option>";
            }
        }
    }

    public function loadAddProductForm()
    {
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
?>
<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>
                <tr>
                    <td><strong>Název nového produktu</strong>*:</td>
                    <td><input class="text" type="text" name="productName"<?php $this->returnPostBackValue("productName"); ?>></td>
                    <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp; ?>"></td>
                </tr>

                <tr>
                    <td>Cesta k náhledovému obrázku:</td>
                    <td><input class="text" type="text" name="productUrl"<?php $this->returnPostBackValue("productUrl");?>></td>
                </tr>

                <tr>
                    <td><strong>Cena</strong>*:</td>
                    <td><input class="text" type="text" name="productPrice"<?php $this->returnPostBackValue("productPrice");?>></td>
                </tr>

                <tr>
                    <td>Počáteční počet:</td>
                    <td><input class="text" type="text" name="productInitialStock"<?php $this->returnPostBackValue("productInitialStock");?>></td>
                    <td><small>(nevyplněno=0)</small></td>
                </tr>

                <tr>
                    <td>Dodavatel*:</td>
                    <td>
                        <select name="productSupplier">
                            <?php
                                $this->loadSupplierSelectionOptions();
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Podkategorie produktu*:</td>
                    <td>
                        <select name="productSubcategory">
                            <?php
                                $this->loadProductCategoryOptions();
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Popis produktu:</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <textarea name="productDescription" rows="3"><?php $this->returnPostBackValuePlain("productDescription"); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Přidat produkt" class="button"></td>
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
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>

<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>

                    <tr>
                        <td><strong>Jméno kategorie</strong>*:</td>
                        <td><input type="text" class="text" name="categoryName"<?php $this->returnPostBackValue("categoryName"); ?>></td>
                        <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                    </tr>

                    <tr>
                        <td>Popisek kategorie:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"><textarea name="categoryDescription"><?php $this->returnPostBackValuePlain("categoryDescription"); ?></textarea></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td><input type="submit" value="Přidat kategorii" class="button"></td>
                    </tr>
            </table>
        </form>
    </div>

    <hr>

    <div class="form_content">
        <form action="" method="post">
            <table>

                <tr>
                    <td><strong>Jméno podkategorie</strong>*:</td>
                    <td><input type="text" class="text" name="subcategoryName"></td>
                    <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                </tr>

                <tr>
                    <td>Nadřazená kategorie*:</td>
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
                    <td><input type="submit" value="Přidat kategorii" class="button"></td>
                </tr>
            </table>
        </form>
    </div>

</div>
        <?php
    }
}
?>