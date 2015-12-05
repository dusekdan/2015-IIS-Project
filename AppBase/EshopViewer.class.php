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
            echo "<a href='index.php?viewcategory=$row[pcat_id]'>" . $this->FILTER->prepareText($row["pcat_name"]) . "</a><br>";
            $subcategorySelectQuery = $this->DBH->query("SELECT psub_name, psub_id FROM product_subcategory WHERE psub_category = '" . $row["pcat_id"] . "' ORDER BY psub_name ASC");
            while ($srow = mysql_fetch_assoc($subcategorySelectQuery)) {
                echo "&nbsp;&nbsp;<a href='index.php?viewsubcategory=$srow[psub_id]'>" . $this->FILTER->prepareText($srow["psub_name"]) . "</a><br>";
            }
        }
    }

    public function loadNewestProducts()
    {
        echo "Tady se načítají produkty... resp. budou! <br>";


    }

    private function returnPostBackValue($index)
    {
        if (isset($_POST[$index])) {
            echo " value='" . $_POST[$index] . "' ";
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

    public function viewProfileInfo()
    {
        $uid = $this->FILTER->prepareInputForSQL($_SESSION["cust_id"]);

        $customerDataSelectQuery = $this->DBH->fetch("SELECT cust_firstname, cust_lastname FROM customer WHERE cust_id = '$uid'");

        echo "Přihlášen: ". $customerDataSelectQuery["cust_firstname"] . " " . $customerDataSelectQuery["cust_lastname"] ." <small>(<a href='index.php?auth=logoff'>odhlásit</a>)</small>";
    }


}

?>