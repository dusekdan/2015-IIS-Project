<?php

class EshopViewer
{

    private $DBH;
    private $AUTH;
    private $FILTER;


    private $postBackInfo = "";

    public function getPostbackInfo()
    {
        return $this->postBackInfo;
    }


    public function __construct($db, $Auth, $IF)
    {
        $this->DBH = $db;
        $this->AUTH = $Auth;
        $this->FILTER = $IF;
    }


    public function loadCategoryListing()
    {
        $categorySelectQuery = $this->DBH->query("SELECT pcat_name, pcat_id FROM product_category ORDER BY pcat_name ASC");

        while ($row = mysql_fetch_assoc($categorySelectQuery)) {
            echo "<a href='index.php?shopaction=viewcategory&categoryid=$row[pcat_id]'>" . $this->FILTER->prepareText($row["pcat_name"]) . "</a><br>";
            $subcategorySelectQuery = $this->DBH->query("SELECT psub_name, psub_id FROM product_subcategory WHERE psub_category = '" . $row["pcat_id"] . "' ORDER BY psub_name ASC");
            while ($srow = mysql_fetch_assoc($subcategorySelectQuery)) {
                echo "&nbsp;&nbsp;<a href='index.php?shopaction=viewsubcategory&subcategoryid=$srow[psub_id]'>" . $this->FILTER->prepareText($srow["psub_name"]) . "</a><br>";
            }
        }
    }

    private function createProductListingFrame($productInfoQuery)
    {
        while($product = mysql_fetch_assoc($productInfoQuery))
        {
            echo "<div class='product-box'>";
            echo "<h3><a href='index.php?shopaction=viewproduct&amp;productid=$product[pr_id]'>$product[pr_name]</a></h3>";
            echo "<hr>";
            echo "<a href='index.php?shopaction=viewproduct&amp;productid=$product[pr_id]'><img src='$product[pr_imageurl]' width='150' height='150'></a>";
            echo "<hr>";
            echo "$product[pr_price] Kč s DPH | <a href='index.php?shopaction=addtobasket&amp;productid=$product[pr_id]'>Do košíku</a>";
            echo "</div>";
        }
    }

    public function loadNewestProducts()
    {
        $loadProductsQuery = $this->DBH->query(
            "SELECT pr_id, pr_name, pr_price, pr_imageurl FROM product ORDER BY pr_addtime DESC"
        );

        $this->createProductListingFrame($loadProductsQuery);
    }

    // TODO: Fix showing up correct message
    public function showProductsInCategory($categoryid)
    {
        $categoryid = $this->FILTER->prepareInputForSQL($categoryid);

        // List of possible categories
        $WHEREquery = "";
        $selectSubcategoryNumbers = $this->DBH->query("select psub_id FROM product_subcategory WHERE psub_category = '$categoryid'");
        $i = 0;
        while($number = mysql_fetch_assoc($selectSubcategoryNumbers))
        {
            $WHEREquery .= " pr_subcategory = '$number[psub_id]' OR";
            $i++;
        }
        $WHEREquery = substr_replace($WHEREquery, "", -1);
        $WHEREquery = substr_replace($WHEREquery, "", -1);

        if(!empty($WHEREquery))
        {
            $totalQuery = " WHERE $WHEREquery";
        }
        else
        {
            $totalQuery = "";
        }



        $queryString = "SELECT pr_id, pr_name, pr_price, pr_imageurl FROM product $totalQuery  ORDER BY pr_addtime DESC";


        $loadProductsQuery = $this->DBH->query("SELECT pr_id, pr_name, pr_price, pr_imageurl FROM product $totalQuery  ORDER BY pr_addtime DESC");

        if($i != 0)
        {
            $this->createProductListingFrame($loadProductsQuery);
        }
        else
        {
            echo "V této kategorii se nenachází žádné produkty...";
        }
    }

    public function showProductsInSubcategory($subcategoryId)
    {
        $subcategoryId = $this->FILTER->prepareInputForSQL($subcategoryId);
        $selectProducts = $this->DBH->query("SELECT pr_id, pr_name, pr_price, pr_imageurl FROM product WHERE pr_subcategory='$subcategoryId' ORDER BY pr_addtime DESC");
        $this->createProductListingFrame($selectProducts);
    }

    public function showProductDetail($productId)
    {
        $productId = $this->FILTER->prepareInputForSQL($productId);

        $productInfo = $this->DBH->fetch("SELECT pr_id, pr_name, pr_description, pr_quantity, pr_available, pr_imageurl, pr_price, pr_addtime, sup_resupplytime FROM product JOIN supplier ON pr_supplier = sup_id WHERE pr_id='$productId'");

            echo "<h2>$productInfo[pr_name]</h2>";
            echo "<img src='$productInfo[pr_imageurl]' height='150' widht='150'>";
            echo "<p>$productInfo[pr_description]</p>";
            echo "<p>Cena produktu: $productInfo[pr_price]</p>";

                if($productInfo["pr_available"] == 'true')
                {
                    echo "<p>Dostupnost: Skladem <small>(zbývá $productInfo[pr_quantity] ks)</small>";
                }
                else
                {
                    echo "<p>Dostupnost: Objednáme (dostupnost do $productInfo[sup_resupplytime] dní)</p>";
                }

            echo "<a href='index.php?shopaction=addtobasket&productid=$productInfo[pr_id]'>Přidat do košíku</a>";

    }

    public function loadAddToBasketForm($productId)
    {
        $productId = $this->FILTER->prepareInputForSQL($productId);

        $addedProductInfo = $this->DBH->fetch("SELECT pr_id, pr_name, pr_price FROM product WHERE pr_id='$productId'");
        ?>
        <form method="post" action="">
            <table>
                <tr>
                    <td>Produkt: <?php echo $addedProductInfo["pr_name"]; ?> </td>
                    <td>Množství: <input type="text" name="addToBasketQuantity" value="1"></td>
                    <td>Cena:   <?php echo $addedProductInfo["pr_price"];?> Kč</td>
                    <td><input type="submit" value="Přidat do košíku"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Cena celkem: MAYBE_HERE</td>
                </tr>
            </table>
        </form>
        <hr>
        <?php
    }

    public function addToBasket($productId, $productQuantity)
    {
        if(isset($_SESSION["cust_cart"]))
        {
            $basket = json_decode($_SESSION["cust_cart"], true);
        }
        else
        {
            $basket = array();
        }

        $found = false;

        // Loop through $basket and check whether item is already contained
        foreach($basket as $item => $value)
        {
            if($item == $productId)
            {
                $basket[$item] = $value+$productQuantity;
                $found = true;
                break;
            }
        }

        if(!$found)
        {
            $basket[$productId] = $productQuantity;
        }

        $basket = json_encode($basket);

        $_SESSION["cust_cart"] = $basket;
    }

    public function removeFromBasket($productId)
    {
        if(isset($_SESSION["cust_cart"]))
        {
            $basket = json_decode($_SESSION["cust_cart"], true);
        }
        else
        {
            return false;
        }

        if(isset($basket[$productId]))
        {
            unset($basket[$productId]);
        }
        else
        {
            return false;
        }

        $_SESSION["cust_cart"] = json_encode($basket);
        return true;
    }

    public function loadBasketContents()
    {
        $basket = json_decode($_SESSION["cust_cart"], true);

        $priceTotal = 0;

        echo "<table>";
        echo "<tr>";
        echo "<th>Název produktu</th><th>Množství</th><th>Cena</th><th></th>";
        echo"</tr>";
        foreach($basket as $item => $value)
        {
            $productInfo = $this->DBH->fetch("SELECT pr_name, pr_price FROM product WHERE pr_id= '$item'");
            $priceTotal += $value*$productInfo["pr_price"];
            echo "<tr>";
            echo "<td>$productInfo[pr_name]</td><td>$value</td><td>$value × $productInfo[pr_price] = ". ($value*$productInfo["pr_price"]) ." Kč</td><td><form method='post' action=''><input type='hidden' value='$item' name='deleteItem'><input onclick=\"return confirm('Opravdu chcete odstranit tuto položku z košíku?');\" type='submit' value='Odebrat'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<hr>";
        echo "Celková cena: " . $priceTotal . " Kč <br>";
        echo "<form method='post' action=''><input type='submit' name='makeOrder' value='Objednat obsah košíku'></form>";


    }

    public function orderBasketContent()
    {
        $basket = json_decode($_SESSION["cust_cart"], true);
        $priceTotal = 0;

        // Create a record for order table
        $uid = $this->FILTER->prepareInputForSQL($_SESSION["cust_id"]);
        $createOrderRecord = $this->DBH->query("INSERT INTO orders(ord_processed, ord_servedby, ord_orderedby, ord_time) VALUES('false', '0', '$uid', NOW())");

        $orderId = mysql_insert_id();

        foreach($basket as $item => $value)
        {
            // Generating order contents
            if($this->checkItemExists($item))
            {
                // Each loop create a record for order_product table
                $insertOrderedItem = $this->DBH->query("INSERT INTO order_product (ordp_product, ordp_order, ordp_quantity) VALUES('$item', '$orderId', '$value')");
            }
            else
            {
                echo "Snažíte se objednat neexistující předmět!";
            }
        }
    }

    private function checkItemExists($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $select = $this->DBH->fetch("SELECT pr_name FROM product WHERE pr_id = '$id'");
        if(empty($select))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function emptyBasket()
    {
        unset($_SESSION["cust_cart"]);
        $_SESSION["cust_cart"] = "{}";
    }


    private function returnPostBackValue($index)
    {
        if (isset($_POST[$index])) {
            echo " value='" . $_POST[$index] . "' ";
        }
    }

    public function showOrderHistory()
    {
        $uid = $this->FILTER->prepareInputForSQL($_SESSION["cust_id"]);


        // Not resolved orders
        echo "<h2>Nevyřízené objednávky</h2>";
        $orderSelect = $this->DBH->query("SELECT ord_time, ord_processed, ord_id FROM orders WHERE ord_processed='false' or ord_processed='waiting' ORDER BY ord_time DESC");
        while($r = mysql_fetch_assoc($orderSelect))
        {
            echo "Objednávka z " . $r["ord_time"] . " STAV: $r[ord_processed]<br>";
            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_product, ordp_quantity FROM order_product JOIN product ON pr_id=ordp_product WHERE ordp_order='$r[ord_id]'");

            $i = 1;
            while($it = mysql_fetch_assoc($itemSelect))
            {
                echo "Položka #$i: " . $it["pr_name"] . " $it[ordp_quantity] × $it[pr_price] = " . ($it['ordp_quantity']*$it['pr_price']) . " Kč <br>";

                $i++;
            }

            echo "Celková cena...";
            echo "<a href=''>Vytisknout objednávku</a><hr>";
        }

        // Only already processed orders
        echo "<h2>Vyřízené objednávky</h2>";
        $orderSelect = $this->DBH->query("SELECT ord_time, ord_processed, ord_id FROM orders WHERE ord_processed='true' ORDER BY ord_time DESC");
        while($r = mysql_fetch_assoc($orderSelect))
        {
            echo "Objednávka z " . $r["ord_time"] . "<br>";
            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_product, ordp_quantity FROM order_product JOIN product ON pr_id=ordp_product WHERE ordp_order='$r[ord_id]'");

            $i = 1;
            while($it = mysql_fetch_assoc($itemSelect))
            {
                echo "Položka #$i: " . $it["pr_name"] . " $it[ordp_quantity] × $it[pr_price] = " . ($it['ordp_quantity']*$it['pr_price']) . " Kč <br>";
                $i++;
            }
            echo "Celková cena...";
            echo "<a href=''>Vytisknout objednávku</a>";
        }

    }

    private function returnPostBackValuePlain($index)
    {
        if (isset($_POST[$index])) {
            echo $_POST[$index];
        }
    }

    public function loadRegisterForm()
    {

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;

        ?>

        <form method="post" action="">

            <table>
                <tr>
                    <td>Jméno</td>
                    <td><input type="text"
                               name="registerFirstName" <?php $this->returnPostBackValue("registerFirstName"); ?>></td>
                    <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp; ?>"></td>
                </tr>
                <tr>
                    <td>Příjmení</td>
                    <td><input type="text"
                               name="registerLastName" <?php $this->returnPostBackValue("registerLastName"); ?>></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="registerEmail" <?php $this->returnPostBackValue("registerEmail"); ?>>
                    </td>
                </tr>
                <tr>
                    <td>Adresa</td>
                    <td><textarea type="text"
                                  name="registerAddress"><?php $this->returnPostBackValuePlain("registerAddress"); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Heslo</td>
                    <td><input type="password" name="registerPassword"></td>
                </tr>
                <tr>
                    <td>Potvrzení hesla</td>
                    <td><input type="password" name="registerPassword2"></td>
                </tr>
                <tr>
                    <td>Telefon</td>
                    <td><input type="text"
                               name="registerPhone" <?php $this->returnPostBackValue("registerLastName"); ?>></td>
                </tr>
                <tr>
                    <td>Pohlaví</td>
                    <td><input type="radio" name="registerGender[]" value="male">Muž <input type="radio"
                                                                                            name="registerGender[]"
                                                                                            value="female">Žena <input
                            type="radio" name="registerGender[]" value="none" checked="checked"> Preferuji neuvádět
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Zaregistrovat">
                    </td>
                </tr>
            </table>

        </form>

        <?php
    }

    public function submitRegistration()
    {
        // Reset
        $this->postBackInfo = "";

        $p = $_POST;
        $doRegister = true;


        if (isset($p["registerFirstName"], $p["registerLastName"], $p["registerEmail"], $p["registerAddress"], $p["registerPassword"], $p["registerPassword2"], $p["registerPhone"], $p["registerGender"])) {

            // Secure input
            $registerFirstname = $this->FILTER->prepareInputForSQL($p["registerFirstName"]);
            $registerLastName = $this->FILTER->prepareInputForSQL($p["registerLastName"]);
            $registerEmail = $this->FILTER->prepareInputForSQL($p["registerEmail"]);
            $registerAddress = $this->FILTER->prepareInputForSQL($p["registerAddress"]);
            $registerPassword = $this->FILTER->prepareInputForSQL($p["registerPassword"]);
            $registerPassword2 = $this->FILTER->prepareInputForSQL($p["registerPassword2"]);
            $registerPhone = $this->FILTER->prepareInputForSQL($p["registerPhone"]);
            $registerGender = $this->FILTER->prepareInputForSQL($p["registerGender"][0]);

            if (empty($registerFirstname)) {
                $this->postBackInfo .= "Je třeba vyplnit vaše jméno!<br>";
                $doRegister = false;
            }

            if (empty($registerLastName)) {
                $this->postBackInfo .= "Je třeba vyplnit vaše příjmení<br>";
                $doRegister = false;
            }

            if (empty($registerEmail)) {
                $this->postBackInfo .= "Je třeba vyplnit email!<br>";
                $doRegister = false;
            }

            if (empty($registerAddress)) {
                $this->postBackInfo .= "Je třeba vyplnit vaši adresu<br>";
                $doRegister = false;
            }

            if (empty($registerPassword) || empty($registerPassword2) || ($registerPassword != $registerPassword2)) {
                $this->postBackInfo .= "Zadaná hesla se neshodují!<br>";
                $doRegister = false;
            }

            if (empty($registerGender)) {
                $registerGender = "none";
            }

            if ($doRegister) {
                return $this->registerUser($registerFirstname, $registerLastName, $registerEmail, $registerAddress, $registerAddress, $registerPassword, $registerPhone, $registerGender);
            } else {
                return false;
            }


        } else {
            $this->postBackInfo .= "Obdržena nedostatečná post data!";
            return false;
        }

    }

    private function registerUser($registerFirstName, $registerLastName, $registerEmail, $registerAddress, $registerAddress, $registerPassword, $registerPhone, $registerGender)
    {
        $registerPassword = $this->FILTER->prepareInputForSQL($this->AUTH->hashPassword($registerPassword));
        echo $registerPassword;
        //echo $registerFirstName . " - " . $registerLast$registerEmail;//
        $insertQuery = $this->DBH->query("INSERT INTO customer (cust_firstname, cust_lastname, cust_address, cust_email, cust_password, cust_phone, cust_registerdate, cust_gender)
        VALUES ('$registerFirstName', '$registerLastName', '$registerAddress', '$registerEmail', '$registerPassword', '$registerPhone', NOW(), '$registerGender')");


        if ($insertQuery === -1) {
            return false;
        } else {
            return true;
        }
    }

    public function loadLogonForm()
    {
        ?>

        <form method="post" action="">
            <table>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="logonMail"></td>
                </tr>
                <tr>
                    <td>Heslo:</td>
                    <td><input type="password" name="logonPassword"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Přihlásit se"></td>
                </tr>
            </table>
        </form>

        <p>Nemáte ještě účet? <a href="index.php?auth=register">Vytvořte si ho zdarma zde!</a></p>

        <?php
    }

    public function submitLogon()
    {
        $p = $_POST;

        $mail = $this->FILTER->prepareInputForSQL($p["logonMail"]);
        $password = $p["logonPassword"];

        if ($this->AUTH->logOnCustomer($mail, $password)) {
            return true;
        } else {
            return false;
        }
    }

    // TODO: Maybe show how many items are in the basket...
    public function viewProfileInfo()
    {
        $uid = $this->FILTER->prepareInputForSQL($_SESSION["cust_id"]);

        $customerDataSelectQuery = $this->DBH->fetch("SELECT cust_firstname, cust_lastname FROM customer WHERE cust_id = '$uid'");

        echo "Přihlášen: ". $customerDataSelectQuery["cust_firstname"] . " " . $customerDataSelectQuery["cust_lastname"] ." <small>(<a href='index.php?auth=logoff'>odhlásit</a>)</small> | <a href=\"index.php?shopaction=viewcart\">Košík</a> | <a href='index.php?shopaction=orderhistory'>Historie objednávek</a>";
    }


}

?>