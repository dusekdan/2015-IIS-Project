<?php

final class ShowHelper implements IAdminModule
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
                <td><form method="post" action=""><input type="hidden" name="deleteCategory" value="<?php echo $this->FILTER->prepareInputForSQL($r["pcat_id"]); ?>"><input onclick="return confirm('Opravdu chcete smazat tuto kategorii?');" type="submit" value="Smazat"></form></td>
            </tr>

            <?php
            $i++;
        }
        echo "</table>";
    }

    public function deleteCategory($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $categorySubcategories = $this->DBH->fetch("SELECT psub_id FROM product_subcategory WHERE psub_category = '$id'");
            if(!empty($categorySubcategories))
            {
                $this->postBackInfo = "Kategorii není možné smazat. Stále existují podkategorie náležící do této kategorie!";
                return false;
            }

        $deleteCategoryResult = $this->deleteCategoryQuery($id);
            if(!$deleteCategoryResult)
            {
                $this->postBackInfo = "Kategorii se nepodařilo smazat (vnitřní chyba).";
            }

        return $deleteCategoryResult;
    }

    private function deleteCategoryQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product_category WHERE pcat_id = '$id'");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
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
                <td><form method="post" action=""><input type="hidden" name="deleteSupplier" value="<?php echo $this->FILTER->prepareInputForSQL($r["sup_id"]); ?>"><input onclick="return confirm('Opravdu chcete tohoto dodavatele smazat?');" type="submit" value="Smazat"></form></td>
                <td><a href="Admin.php?action=Show&amp;edittype=supplier&amp;edit=<?php echo $this->FILTER->prepareText($r["sup_id"]); ?>">Editovat</a></td>
            </tr>
            <?php
            $i++;
        }

        echo "</table>";

    }

    public function deleteSupplier($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        // Check whether supplier is still supplying with some products
        $selectSupplierProducts = $this->DBH->fetch("SELECT pr_id FROM product WHERE pr_supplier = '$id'");
            if(!empty($selectSupplierProducts))
            {
                $this->postBackInfo = "Dodavatele nebylo možné smazat - stále existují výrobky, které jsou tímto dodavatelem dodávány.";
                return false;
            }

        $deleteSupplierResult =  $this->deleteSupplierQuery($id);
            if(!$deleteSupplierResult)
            {
                $this->postBackInfo = "Nepodařilo se smazat dodavatele z databáze (vnitřní chyba).";
            }

        return $deleteSupplierResult;
    }

    private function deleteSupplierQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM supplier WHERE sup_id = '$id'");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadSubcategoryList()
    {
        $selectQuery = $this->DBH->query("SELECT psub_id, psub_name, psub_category, pcat_name FROM product_category JOIN product_subcategory ON pcat_id=psub_category ORDER BY psub_name ASC");

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
                <td><form method="post" action=""><input type="hidden" name="deleteSubcategory" value="<?php echo $this->FILTER->prepareInputForSQL($r["psub_id"]); ?>"><input onclick="return confirm('Opravdu chcete tuto podkategorii smazat?\n \n');" type="submit" value="Smazat"></form></td>
            </tr>
            <?php
            $i++;
        }

        echo "</table>";
    }

    public function deleteSubcategory($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $selectSubcategoryProducts = $this->DBH->fetch("SELECT pr_id FROM product WHERE pr_subcategory = '$id'");
            if(!empty($selectSubcategoryProducts))
            {
                $this->postBackInfo = "Podkategorie nemůže být smazána. Stále existují produkty, které do ní spadají.";
                return false;
            }

        $deleteSubcategoryResult = $this->deleteSubcategoryQuery($id);
            if(!$deleteSubcategoryResult)
            {
                $this->postBackInfo  = "Podkategorie nemohla být smazána (vnitřní chyba).";
            }

        return $deleteSubcategoryResult;
    }

    private function deleteSubcategoryQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product_subcategory WHERE psub_id = '$id' ");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /// TODO: Allow user to view only products in some specific (sub)category

    public function loadProductList()
    {
        $selectQuery = $this->DBH->query("SELECT pr_name, pr_id, pcat_name, psub_name FROM product JOIN product_subcategory ON psub_id = pr_subcategory JOIN product_category ON psub_category = pcat_id");

        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Název produktu</th>";
        echo "<th>Název kategorie</th>";
        echo "<th>Název podkategorie</th>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>

            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo $this->FILTER->prepareText($r["pr_name"]);?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]);?></td>
                <td><?php echo $this->FILTER->prepareText($r["psub_name"]);?></td>
                <td><form method="post" action=""><input type="hidden" name="deleteProduct" value="<?php echo $this->FILTER->prepareInputForSQL($r["pr_id"]); ?>"><input onclick="return confirm('Opravdu chcete smazat tento produkt?');" type="submit" value="Smazat"></form></td>
            </tr>

            <?php
            $i++;
        }

        echo "</table>";

    }

    public function deleteProduct($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $deleteProductResult = $this->deleteProductQuery($id);
            if(!$deleteProductResult)
            {
                $this->postBackInfo = "Produkt se nepodařilo smazat (vnitřní chyba).";
            }
        return $deleteProductResult;
    }

    private function deleteProductQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product WHERE pr_id = '$id' ");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /// Editation here

    // Data for edited record are stored here
    private $temporaryDataBuffer;


    private function returnEditPostbackValue($index, $columnName)
    {
        if(isset($_POST[$index]))
        {
            echo " value='" . $_POST[$index] . "' ";
        }
        else
        {
            // Use data for the edited record
            echo " value='" . $this->temporaryDataBuffer[$columnName] . "' ";
        }
    }

    private function returnEditPostbackValuePlain($index, $columnName)
    {
        if(isset($_POST[$index]))
        {
            echo $_POST[$index];
        }
        else
        {
            // Use data for the edited record
            echo $this->temporaryDataBuffer[$columnName];
        }
    }

    public function editSupplier()
    {
        // Reseting postbackinformation
        $this->postBackInfo = "";

        // Simplication of $_POST
        $p = $_POST;

        // Simplification of $_GET["edit"]
        $supId = $_GET["edit"];

        // Basic variables init
        $editRecord = true;
        $errorMessage = "";

        // Data initialization & securing
        if(isset($p["supplierName"], $p["supplierMail"], $p["supplierPhone"], $p["supplierResupplytime"], $p["supplierICO"], $p["supplierAddress"]))
        {

            $supplierName = $this->FILTER->prepareInputForSQL($p["supplierName"]);
            $supplierMail = $this->FILTER->prepareInputForSQL($p["supplierMail"]);
            $supplierPhone = $this->FILTER->prepareInputForSQL($p["supplierPhone"]);
            $supplierResupplytime = $this->FILTER->prepareInputForSQL($p["supplierResupplytime"]);
            $supplierICO = $this->FILTER->prepareInputForSQL($p["supplierICO"]);
            $supplierAddress = $this->FILTER->prepareInputForSQL($p["supplierAddress"]);
            $supId = $this->FILTER->prepareInputForSQL($supId);

            if(empty($supplierName))
            {
                $errorMessage .= "Název dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierMail))
            {
                $errorMessage .= "Email dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierPhone))
            {
                $errorMessage .= "Telefon dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(!is_numeric($supplierResupplytime))
            {
                $errorMessage .= "Doba znovuobnovení musí být číslo!";
                $editRecord = false;
            }

            if(empty($supplierICO))
            {
                $errorMessage .= "IČO dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierAddress))
            {
                $errorMessage .= "Adresa dodavatele musí být vyplněn!";
                $editRecord = false;
            }


            if($editRecord)
            {
                $editRecordResult = $this->editSupplierQuery($supplierName, $supplierMail, $supplierPhone, $supplierResupplytime, $supplierICO, $supplierAddress, $supId);
                if(!$editRecordResult)
                {
                    $this->postBackInfo = "Nepodařilo se upravit dodavatele! (vnitřní chyba)";
                }

                return $editRecordResult;
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

    private function editSupplierQuery($supplierName, $supplierMail, $supplierPhone, $supplierResupplytime, $supplierICO, $supplierAddress, $supId)
    {
        $updateQuery = $this->DBH->query("UPDATE supplier SET
                                          sup_name='$supplierName',
                                          sup_mail='$supplierMail',
                                          sup_phone='$supplierPhone',
                                          sup_resupplytime='$supplierResupplytime',
                                          sup_ico='$supplierICO',
                                          sup_address='$supplierAddress'
                                          WHERE sup_id='$supId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function loadScriptsForProductEditForm()
    {
        ?>

        <script language="javascript" type="text/javascript">
$(document).ready(function(){

//let's create arrays
var chocolates = [
    {display: "Dark chocolate", value: "dark-chocolate" },
    {display: "Milk chocolate", value: "milk-chocolate" },
    {display: "White chocolate", value: "white-chocolate" },
    {display: "Gianduja chocolate", value: "gianduja-chocolate" }];

var vegetables = [
    {display: "Broccoli", value: "broccoli" },
    {display: "Cabbage", value: "cabbage" },
    {display: "Carrot", value: "carrot" },
    {display: "Cauliflower", value: "cauliflower" }];

var icecreams = [
    {display: "Frozen yogurt", value: "frozen-yogurt" },
    {display: "Booza", value: "booza" },
    {display: "Frozen yogurt", value: "frozen-yogurt" },
    {display: "Ice milk", value: "ice-milk" }];

//If parent option is changed
$("#parent_selection").change(function() {
        var parent = $(this).val(); //get option value from parent

        switch(parent){ //using switch compare selected option and populate child
              case 'chocolates':
                list(chocolates);
                break;
              case 'vegetables':
                list(vegetables);
                break;
              case 'icecreams':
                list(icecreams);
                break;
            default: //default child option is blank
                $("#child_selection").html('');
                break;
           }
});

//function to populate child select box
function list(array_list)
{
    $("#child_selection").html(""); //reset child options
    $(array_list).each(function (i) { //populate child options
        $("#child_selection").append("<option value=\""+array_list[i].value+"\">"+array_list[i].display+"</option>");
    });
}

});
</script>


        <?php
    }

    public function loadProductEditForm($productId)
    {
        $productId = $this->FILTER->prepareInputForSQL($productId);
        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM product WHERE pr_id = '$productId'");

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>

        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Název nového produktu</td>
                            <td><input class="text" type="text" name="productName"<?php $this->returnPostBackValue("productName"); ?>></td>
                            <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp; ?>"></td>
                        </tr>

                        <tr>
                            <td>Ceseta k náhledovému obrázku</td>
                            <td><input class="text" type="text" name="productUrl"<?php $this->returnPostBackValue("productUrl");?>></td>
                        </tr>

                        <tr>
                            <td>Cena</td>
                            <td><input class="text" type="text" name="productPrice"<?php $this->returnPostBackValue("productPrice");?>></td>
                        </tr>

                        <tr>
                            <td>Počáteční počet:</td>
                            <td><input class="text" type="text" name="productInitialStock"<?php $this->returnPostBackValue("productInitialStock");?>></td>
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
                                <select name="productSubcategory">
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
                            <td><input type="submit" value="Upravit produkt"></td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>

        <?php
    }

    public function loadSupplierEditForm($supplierId)
    {
        $supplierId = $this->FILTER->prepareInputForSQL($supplierId);

        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM supplier WHERE sup_id = '$supplierId'");

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>
        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Název dodavatele</td>
                            <td><input class="text" type="text" name="supplierName" <?php $this->returnEditPostbackValue("supplierName", "sup_name"); ?>></td>
                            <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                        </tr>

                        <tr>
                            <td>Mail dodavatele:</td>
                            <td><input class="text" type="text" name="supplierMail" <?php $this->returnEditPostbackValue("supplierMail", "sup_mail"); ?>></td>
                        </tr>

                        <tr>
                            <td>Telefon:</td>
                            <td><input class="text" type="text" name="supplierPhone" <?php $this->returnEditPostbackValue("supplierPhone", "sup_phone"); ?>></td>
                        </tr>

                        <tr>
                            <td>Dostupnost zboží</td>
                            <td><input class="text" type="text" name="supplierResupplytime" <?php $this->returnEditPostbackValue("supplierResupplytime", "sup_resupplytime"); ?>></td>
                            <td>(doba ve dnech)</td>
                        </tr>

                        <tr>
                            <td>IČO</td>
                            <td><input class="text" type="text" name="supplierICO" <?php $this->returnEditPostbackValue("supplierICO", "sup_ico"); ?>></td>
                        </tr>

                        <tr>
                            <td>Adresa</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <textarea name="supplierAddress" rows="3"><?php $this->returnEditPostbackValuePlain("supplierAddress", "sup_address"); ?></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Upravit dodavatele"></td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>

        <?php
    }

}