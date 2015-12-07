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
            $subcategoryCount = $this->DBH->fetch("SELECT count(*) subcategorycount FROM product_subcategory WHERE psub_category='$row[pcat_id]'");
            if($subcategoryCount["subcategorycount"] > 0)
            {
            ?>
                <a href="<?php echo "index.php?shopaction=viewcategory&categoryid=$row[pcat_id]"; ?>">
                <div class="item first">
                    <?php echo $this->FILTER->prepareText($row["pcat_name"]);?>
                </div>

            <?php
            $subcategorySelectQuery = $this->DBH->query("SELECT psub_name, psub_id FROM product_subcategory WHERE psub_category = '" . $row["pcat_id"] . "' ORDER BY psub_name ASC");
            while ($srow = mysql_fetch_assoc($subcategorySelectQuery)) {
                ?>
                <a href="<?php echo "index.php?shopaction=viewsubcategory&subcategoryid=$srow[psub_id]" ?>">
                    <div class="item sub-item">
                        <?php echo $this->FILTER->prepareText($srow["psub_name"]); ?>
                    </div>
                </a>
                <?php
                }
            }
        }
    }

    private function createProductListingFrame($productInfoQuery)
    {
        while($product = mysql_fetch_assoc($productInfoQuery))
        {
            echo "<div class='product-box'>";
            echo "<div class='productname'>";
            echo "<h3><a href='index.php?shopaction=viewproduct&amp;productid=$product[pr_id]'>$product[pr_name]</a></h3>";
            echo "</div>";
            echo "<hr>";
            echo "<a href='index.php?shopaction=viewproduct&amp;productid=$product[pr_id]'><img src='$product[pr_imageurl]' width='150' height='150'></a>";
            echo "<hr>";
            echo "<strong>$product[pr_price] Kč </strong>s DPH";
            echo "<br>";
            echo "Skladem (x kusů)";
            echo "<br>";
            echo "<a href='index.php?shopaction=addtobasket&amp;productid=$product[pr_id]' class='addtobasket'>Do košíku</a>";
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
            echo "<img src='$productInfo[pr_imageurl]' height='300' widht='300'>";
            echo "<p>$productInfo[pr_description]</p>";
            echo "<p>Cena produktu:<strong> $productInfo[pr_price] Kč</strong> s DPH</p>";

                if($productInfo["pr_available"] == 'true')
                {
                    echo "<p>Dostupnost: Skladem <small>(zbývá $productInfo[pr_quantity] ks)</small>";
                }
                else
                {
                    echo "<p>Dostupnost: Objednáme (dostupnost do $productInfo[sup_resupplytime] dní)</p><br>";
                }

            echo "<br><a href='index.php?shopaction=addtobasket&productid=$productInfo[pr_id]' class='addtobasket_det'>Přidat do košíku</a>";

    }

    public function loadAddToBasketForm($productId)
    {
        $productId = $this->FILTER->prepareInputForSQL($productId);

        $addedProductInfo = $this->DBH->fetch("SELECT pr_id, pr_name, pr_price FROM product WHERE pr_id='$productId'");
        ?>
        <div class='form'>
        <div class='form_content'>
        <form method="post" action="">
            <table>
                <tr>
                    <td>Produkt: <?php echo $addedProductInfo["pr_name"]; ?> </td>
                    <td>Množství: <input type="text" name="addToBasketQuantity" value="1"></td>
                    <td>Cena:   <?php echo $addedProductInfo["pr_price"];?> Kč</td>
                    <td><input type="submit" value="Přidat do košíku" class="button"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </form>
        </div>
        </div>
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
        if(!isset($_SESSION["cust_cart"]))
        {
            $_SESSION["cust_cart"] = array();
        }

        $basket = json_decode($_SESSION["cust_cart"], true);

        $priceTotal = 0;

        echo "<table class='information'>";
        echo "<tr>";
        echo "<th>Název produktu</th><th>Množství</th><th>Cena</th><th></th>";
        echo"</tr>";
        foreach($basket as $item => $value)
        {
            $productInfo = $this->DBH->fetch("SELECT pr_name, pr_price FROM product WHERE pr_id= '$item'");
            $priceTotal += $value*$productInfo["pr_price"];
            echo "<tr>";
            echo "<td>$productInfo[pr_name]</td><td>$value</td><td>$value × $productInfo[pr_price] = ". ($value*$productInfo["pr_price"]) ." Kč</td><td><form method='post' action=''><input type='hidden' value='$item' name='deleteItem'><input onclick=\"return confirm('Opravdu chcete odstranit tuto položku z košíku?');\" type='submit' value='Odebrat' class='button'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<hr>";
        echo "Celková cena: " . $priceTotal . " Kč <br>";
        echo "<form method='post' action=''><input type='submit' name='makeOrder' value='Objednat obsah košíku' class='button'></form>";


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
        $orderSelect = $this->DBH->query("SELECT ord_time, ord_processed, ord_id FROM orders WHERE (ord_processed='false' or ord_processed='waiting') AND ord_orderedby='$uid'  ORDER BY ord_time DESC");

        echo "<table class='information'>";
        echo "<tr><th>Číslo objednávky</th><th>Čas</th><td>Položky</td><th>Celková cena</th></tr>";
        while($r = mysql_fetch_assoc($orderSelect))
        {
            echo "<tr>";
            echo "<td>$r[ord_id]</td><td>$r[ord_time]</td><td><ul>";

            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_product, ordp_quantity FROM order_product JOIN product ON pr_id=ordp_product WHERE ordp_order='$r[ord_id]'");

            $i = 1;
            while($it = mysql_fetch_assoc($itemSelect))
            {
                echo "Položka #$i: " . $it["pr_name"] . " $it[ordp_quantity] × $it[pr_price] = " . ($it['ordp_quantity']*$it['pr_price']) . " Kč <br>";

                $i++;
            }

            echo "</ul></td><td>".$this->calculateOrderPrice($r["ord_id"])."</td></tr>";
        }
        echo "</table>";



        // Only already processed orders
        echo "<h2>Vyřízené objednávky</h2>";
        $orderSelect = $this->DBH->query("SELECT ord_time, ord_processed, ord_id FROM orders WHERE ord_processed='true' AND ord_orderedby='$uid'  ORDER BY ord_time DESC");

        echo "<table class='information'>";
        echo "<tr><th>Číslo objednávky</th><th>Čas</th><td>Položky</td><th>Celková cena</th><th>TISK</th></tr>";
        while($r = mysql_fetch_assoc($orderSelect))
        {
            echo "<tr>";
            echo "<td>$r[ord_id]</td><td>$r[ord_time]</td><td><ul>";

            $itemSelect = $this->DBH->query("SELECT pr_name, pr_price, ordp_product, ordp_quantity FROM order_product JOIN product ON pr_id=ordp_product WHERE ordp_order='$r[ord_id]'");

            $i = 1;
            while($it = mysql_fetch_assoc($itemSelect))
            {
                echo "Položka #$i: " . $it["pr_name"] . " $it[ordp_quantity] × $it[pr_price] = " . ($it['ordp_quantity']*$it['pr_price']) . " Kč <br>";

                $i++;
            }

            echo "</ul></td><td>".$this->calculateOrderPrice($r["ord_id"])."</td><td><a target='_blank' href='print.php?orderid=$r[ord_id]'>Vytisknout</a></td></tr>";
        }
        echo "</table>";

    }

    public function printOrder($orderId)
    {
        $orderId = $this->FILTER->prepareInputForSQL($orderId);

        $r = $this->DBH->fetch("SELECT ord_time, ord_processed, ord_id, cust_firstname, cust_lastname, cust_address FROM orders JOIN customer ON cust_id = ord_orderedby WHERE ord_id='$orderId'");

        echo "<table>";

        echo "<tr><td><b>Objednávka č. $r[ord_id]</b></td><td>Čas objednání:". $this->convertTime($r["ord_time"]) ."</td></tr>";

        echo "<tr>";
        echo "<td valign='top'><b>Dodavatel:</b></td>";
        echo "<td>Papírnictví Dušek & Popková<br>Brněnská 158/88<br>Brno<br>637 18</td>";

        echo "</tr>";

        echo "<tr>";
        echo "<td valign='top'><b>Odběratel:</b></td>";
        echo "<td>$r[cust_firstname] $r[cust_lastname]<br>" . nl2br($r["cust_address"]). "</td>";
        echo "</tr>";
        echo "<tr><td><b>Položky:</b></td><td>";
        $this->loadOrderItems($r["ord_id"]);
        echo "</td></tr>";
        echo "<tr><td><b>Cena celkem:</b> </td><td>" . $this->calculateOrderPrice($orderId) . " Kč</td></tr>";
        echo "</table>";
    }

        private function convertTime($time)
    {
        $dateFormat = new DateTime($time);
        return date_format($dateFormat, "m.d.Y H:i:s");
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
        <div class="form">
        <div class="form_content">
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
                        <input type="submit" value="Zaregistrovat" class='button'>
                    </td>
                </tr>
            </table>

        </form>
        </div>
        </div>

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
        <div class='form'>
        <div class='form_content'>
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
                    <td><input type="submit" value="Přihlásit se" class='button'></td>
                </tr>
            </table>
        </form>
        </div>
        </div>

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

        echo "Přihlášen: <strong>". $customerDataSelectQuery["cust_firstname"] . " " . $customerDataSelectQuery["cust_lastname"] ." </strong><small>(<a href='index.php?auth=logoff'>odhlásit</a>)</small> | <a href=\"index.php?shopaction=viewcart\">Košík</a> | <a href='index.php?shopaction=orderhistory'>Historie objednávek</a>  | <a href='index.php?shopaction=changecontact'>Změnit kontaktní údaje</a>";
    }

    public function loadEditContactForm()
    {

        $dataSelect = $this->DBH->fetch("select * from customer where cust_id='$_SESSION[cust_id]'");

        echo "<h2>Změnit kontaktní údaje</h2>";
        ?>

        <div class='form'>
        <div class='form_content'>
        <form method="post" action="">

            <table>
                <tr>
                    <td><strong>Jméno</strong>*:</td>
                    <td><input type="text"
                               name="registerFirstName" value="<?php echo $dataSelect["cust_firstname"];?>"></td>
                    <td><input type="hidden" name="formGenerationStamp"></td>
                </tr>
                <tr>
                    <td><strong>Příjmení</strong>*:</td>
                    <td><input type="text"
                               name="registerLastName" value="<?php echo $dataSelect["cust_lastname"];?>"></td>
                </tr>
                <tr>
                    <td><strong>Email</strong>*:</td>
                    <td><input type="text" name="registerEmail" value="<?php echo $dataSelect["cust_email"];?>">
                    </td>
                </tr>
                <tr>
                    <td><strong>Adresa</strong>*:</td>
                    <td><textarea type="text"
                                  name="registerAddress"><?php echo $dataSelect["cust_address"];?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Telefon:</td>
                    <td><input type="text"
                               name="registerPhone" value="<?php echo $dataSelect["cust_phone"];?>"></td>
                </tr>
                <tr>
                    <td>Pohlaví:</td>
                    <td><input type="radio" name="registerGender[]" <?php if($dataSelect["cust_gender"] == "male"){echo "checked='checked'";}?> value="male">Muž <input type="radio"
                                                                                            name="registerGender[]"
                                                                                            value="female" <?php if($dataSelect["cust_gender"] == "female"){echo "checked='checked'";}?>>Žena <input
                            type="radio" name="registerGender[]" value="none" checked="checked" <?php if($dataSelect["cust_gender"] == "none"){echo "checked='checked'";}?>> Preferuji neuvádět
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Změnit údaje" class='button'>
                    </td>
                </tr>
            </table>

        </form>
        </div>
        </div>

        <?php
    }

    public function editCustomer()
    {
        $p = $_POST;
        $editRecord = true;
        $errorMessage = "";
        $this->postBackInfo = "";

        if(isset($p["registerFirstName"], $p["registerEmail"], $p["registerLastName"], $p["registerAddress"], $p["registerPhone"], $p["registerGender"]))
        {
            $firstName = $this->FILTER->prepareInputForSQL($p["registerFirstName"]);
            $lastName = $this->FILTER->prepareInputForSQL($p["registerLastName"]);
            $address = $this->FILTER->prepareInputForSQL($p["registerAddress"]);
            $phone = $this->FILTER->prepareInputForSQL($p["registerPhone"]);
            $gender = $this->FILTER->prepareInputForSQL($p["registerGender"][0]);
            $mail = $this->FILTER->prepareInputForSQL($p["registerEmail"]);

            if(empty($firstName))
            {
                $errorMessage .= "Jméno nesmí být prázdné!<br>";
                $editRecord = false;
            }

            if(empty($lastName))
            {
                $errorMessage .= "Příjmení nesmí být prázdné!<br>";
                $editRecord = false;
            }

            if(empty($address))
            {
                $errorMessage .= "Adresa musí být uvedena!<br>";
                $editRecord = false;
            }

            if(!empty($phone) && (!is_numeric($phone)) || strlen($phone) != 9)
            {
                $errorMessage .= "Telefon musí být ve správném tvaru!<br>";
                $editRecord = false;
            }

            if(!$this->FILTER->isMail($mail))
            {
                $errorMessage .= "Email musí být ve správném tvaru!<br>";
                $editRecord = false;
            }


            if($editRecord)
            {
                $updateQuery = $this->DBH->query("UPDATE customer SET cust_firstname='$firstName', cust_lastname='$lastName', cust_email='$mail', cust_phone='$phone', cust_gender='$gender',
                                cust_address='$address' WHERE cust_id='$_SESSION[cust_id]'");
                if($updateQuery === -1)
                {
                    $this->postBackInfo = "Nepodařilo se upravit data, vnitřní chyba, zkuste to znovu prosím!<br>";
                    return false;
                }
                else
                {
                    return true;
                }
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }
        }
        else
        {
            $this->postBackInfo = "Obdržena nedostatečná post DATA";
            return false;
        }
    }
}

?>