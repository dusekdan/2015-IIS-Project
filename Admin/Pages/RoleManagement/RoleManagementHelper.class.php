<?php

class RoleManagementHelper implements IAdminModule
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
        $description .= "\t\t<td>Module name:</td>" . PHP_EOL . "\t\t<td>Role management</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Author:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleAuthor() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Version:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleVersion() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Description:</td>" . PHP_EOL . "\t\t<td>This module allows user (manager) to add modify and remove roles and employees to and from the system.</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "</table>" . PHP_EOL;

        return $description;
    }


    public function deleteEmployee($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        // TODO: If employee has unfinished (claimed) orders, those orders will become unassigned

        return $this->deleteEmployeeQuery($id);
    }

    private function deleteEmployeeQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM employee WHERE emp_id = '$id'");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadEmployeeList()
    {

        $selectQuery = $this->DBH->query("SELECT * FROM employee JOIN employee_role ON emp_role = erole_id ORDER BY emp_fullname ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th>Jméno</th><th>Role</th><th>Telefon</th>";
        echo "</tr>";

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<tr>";
            echo "<td>$r[emp_fullname]</td>";
            echo "<td>$r[erole_name]</td>";
            echo "<td>$r[emp_phone]</td>";
            // <form method="post" action=""><input type="hidden" name="deleteCategory" value="
            echo "<td><form method='post' action=''><input type='hidden' name='deleteEmployee' value='$r[emp_id]'><input type='submit' value='Smazat'></form></td>";
            echo "<td><a href='Admin.php?action=RoleManagement&amp;edittype=employee&amp;edit=$r[emp_id]'>Editovat</a></td>";
            echo "</tr>";
        }



        echo "</table>";

    }

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

    public function editEmployee()
    {
        $this->postBackInfo = "";

        $p = $_POST;
        $empId = $this->FILTER->prepareInputForSQL($_GET["edit"]);

        $editRecord = true;
        $errorMessage = "";

        if(isset($p["employeeName"], $p["employeeUsername"], $p["employeeMail"], $p["employeePhone"], $p["employeeBCN"], $p["employeeRole"], $p["employeeAddress"]))
        {

            $employeeName = $this->FILTER->prepareInputForSQL($p["employeeName"]);
            $employeeUserName = $this->FILTER->prepareInputForSQL($p["employeeUsername"]);
            $employeeMail = $this->FILTER->prepareInputForSQL($p["employeeMail"]);
            $employeePhone = $this->FILTER->prepareInputForSQL($p["employeePhone"]);
            $employeeBCN = $this->FILTER->prepareInputForSQL($p["employeeBCN"]);
            $employeeRole = $this->FILTER->prepareInputForSQL($p["employeeRole"]);
            $employeeAddress = $this->FILTER->prepareInputForSQL($p["employeeAddress"]);


            if(!is_numeric($employeeRole) || empty($employeeRole))
            {
                $errorMessage .= "Zaměstnancká role musí být vyplněna správně!";
                $editRecord = false;
            }

            if(empty($employeeBCN))
            {
                $errorMessage .= "Rodné číslo zaměstnance musí být vyplněno!";
                $editRecord = false;
            }

            if(empty($employeeAddress))
            {
                $errorMessage .= "Adresa zaměstnance musí být vyplněna!";
                $editRecord = false;
            }

            if(empty($employeePhone))
            {
                $errorMessage .= "Telefon zaměstnance musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($employeeMail))
            {
                $errorMessage .= "Email zaměstnance musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($employeeUserName))
            {
                $errorMessage .= "Username pro zaměstnance musí být vyplněno!";
                $editRecord = false;
            }

            if(empty($employeeName))
            {
                $errorMessage .= "Jméno zaměstnance musí být vyplněno!";
                $editRecord = false;
            }

            if($editRecord)
            {
                return $this->editEmployeeQuery($employeeName, $employeeUserName, $employeeMail, $employeePhone, $employeeBCN, $employeeRole, $employeeAddress, $empId);
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

    private function editEmployeeQuery($employeeName, $employeeUserName, $employeeMail, $employeePhone, $employeeBCN, $employeeRole, $employeeAddress, $empId)
    {
        $updateQuery = $this->DBH->query("UPDATE employee SET
                                          emp_fullname = '$employeeName',
                                          emp_username = '$employeeUserName',
                                          emp_email    = '$employeeMail',
                                          emp_phone = '$employeePhone',
                                          emp_bcn = '$employeeBCN',
                                          emp_role = '$employeeRole',
                                          emp_address = '$employeeAddress'
                                          WHERE emp_id = '$empId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadEmployeeEditForm($employeeId)
    {
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;

        $employeeId = $this->FILTER->prepareInputForSQL($employeeId);
        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM employee JOIN employee_role on emp_role = erole_id WHERE emp_id = '$employeeId'");

        ?>

        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>

                        <tr>
                            <td>Jméno zaměstnance: </td>
                            <td><input type="text" class="text" name="employeeName"<?php $this->returnEditPostBackValue("employeeName", "emp_fullname"); ?>></td>
                            <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp;?>"></td>
                        </tr>

                        <tr>
                            <td>Username:</td>
                            <td><input type="text" class="text" name="employeeUsername"<?php $this->returnEditPostBackValue("employeeUsername", "emp_username"); ?>></td>
                        </tr>

                        <tr>
                            <td>Email:</td>
                            <td><input type="email" class="text" name="employeeMail"<?php $this->returnEditPostBackValue("employeeMail", "emp_email"); ?>></td>
                        </tr>

                        <tr>
                            <td>Telefon:</td>
                            <td><input type="text" class="text" name="employeePhone"<?php $this->returnEditPostBackValue("employeePhone", "emp_phone"); ?>></td>
                        </tr>

                        <tr>
                            <td>Rodné číslo:</td>
                            <td><input type="text" class="text" name="employeeBCN"<?php $this->returnEditPostBackValue("employeeBCN", "emp_bcn"); ?>></td>
                        </tr>

                        <tr>
                            <td>Role:</td>
                            <td>
                                <select name="employeeRole">
                                    <?php $this->loadUserRolesOptions(); ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Adresa:</td>
                            <td><textarea name="employeeAddress"><?php $this->returnEditPostBackValuePlain("employeeAddress", "emp_address"); ?></textarea></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Přidat zaměstnance"></td>
                        </tr>

                    </table>
                </form>
            </div>
        </div>

        <?php

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


    public function submitEmployee()
    {
        $this->postBackInfo = "";

        $p = $_POST;

        $addRecord = true;
        $errorMessage = "";

        if(isset($p["employeeName"], $p["employeeUsername"], $p["employeePassword"], $p["employeePassword2"], $p["employeeMail"], $p["employeePhone"], $p["employeeAddress"], $p["employeeRole"], $p["employeeBCN"]))
        {

            $employeeName = $this->FILTER->prepareInputForSQL($p["employeeName"]);
            $employeeUserName = $this->FILTER->prepareInputForSQL($p["employeeUsername"]);
            $employeePassword = $this->FILTER->prepareInputForSQL($p["employeePassword"]);
            $employeePassword2 = $this->FILTER->prepareInputForSQL($p["employeePassword2"]);
            $employeeMail = $this->FILTER->prepareInputForSQL($p["employeeMail"]);
            $employeePhone = $this->FILTER->prepareInputForSQL($p["employeePhone"]);
            $employeeAddress = $this->FILTER->prepareInputForSQL($p["employeeAddress"]);
            $employeeBCN = $this->FILTER->prepareInputForSQL($p["employeeBCN"]);
            $employeeRole = $this->FILTER->prepareInputForSQL($p["employeeRole"]);

            if(!is_numeric($employeeRole) || empty($employeeRole))
            {
                $errorMessage .= "Zaměstnancká role musí být vyplněna správně!";
                $addRecord = false;
            }

            if(empty($employeeBCN))
            {
                $errorMessage .= "Rodné číslo zaměstnance musí být vyplněno!";
                $addRecord = false;
            }

            if(empty($employeeAddress))
            {
                $errorMessage .= "Adresa zaměstnance musí být vyplněna!";
                $addRecord = false;
            }

            if(empty($employeePhone))
            {
                $errorMessage .= "Telefon zaměstnance musí být vyplněn!";
                $addRecord = false;
            }

            if(empty($employeeMail))
            {
                $errorMessage .= "Email zaměstnance musí být vyplněn!";
                $addRecord = false;
            }

            if(empty($employeeUserName))
            {
                $errorMessage .= "Username pro zaměstnance musí být vyplněno!";
                $addRecord = false;
            }

            if(empty($employeePassword) || empty($employeePassword2))
            {
                $errorMessage .= "Heslo nesmí být prázdné!";
                $addRecord = false;
            }

            if(empty($employeeName))
            {
                $errorMessage .= "Jméno zaměstnance musí být vyplněno!";
                $addRecord = false;
            }

            if($employeePassword != $employeePassword2)
            {
                $errorMessage .= "Zadaná hesla se neshodují!";
                $addRecord = false;
            }

            $employeePassword = $this->AUTH->hashPassword($employeePassword);

            if($addRecord)
            {
                return $this->insertEmployee($employeeName, $employeeUserName, $employeePassword, $employeeMail, $employeePhone, $employeeAddress, $employeeBCN, $employeeRole);
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

    private function insertEmployee($employeeName, $employeeUserName, $employeePassword, $employeeMail, $employeePhone, $employeeAddress, $employeeBCN, $employeeRole)
    {
        $insertQuery = $this->DBH->query("INSERT INTO employee (emp_fullname, emp_username, emp_password, emp_email, emp_phone, emp_address, emp_bcn, emp_role )
        VALUES ('$employeeName', '$employeeUserName', '$employeePassword', '$employeeMail', '$employeePhone', '$employeeAddress', '$employeeBCN', '$employeeRole')");

        if($insertQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadAddEmployeeForm()
    {
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>
        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>

                        <tr>
                            <td>Jméno zaměstnance: </td>
                            <td><input type="text" class="text" name="employeeName"<?php $this->returnPostBackValue("employeeName"); ?>></td>
                            <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp;?>"></td>
                        </tr>

                        <tr>
                            <td>Username:</td>
                            <td><input type="text" class="text" name="employeeUsername"<?php $this->returnPostBackValue("employeeUsername"); ?>></td>
                        </tr>

                        <tr>
                            <td>Heslo:</td>
                            <td><input type="password" class="text" name="employeePassword"></td>
                        </tr>

                        <tr>
                            <td>Heslo znovu:</td>
                            <td><input type="password" class="text" name="employeePassword2"></td>
                        </tr>

                        <tr>
                            <td>Email:</td>
                            <td><input type="email" class="text" name="employeeMail"<?php $this->returnPostBackValue("employeeMail"); ?>></td>
                        </tr>

                        <tr>
                            <td>Telefon:</td>
                            <td><input type="text" class="text" name="employeePhone"<?php $this->returnPostBackValue("employeePhone"); ?>></td>
                        </tr>

                        <tr>
                            <td>Rodné číslo:</td>
                            <td><input type="text" class="text" name="employeeBCN"<?php $this->returnPostBackValue("employeeBCN"); ?>></td>
                        </tr>

                        <tr>
                            <td>Role:</td>
                            <td>
                                <select name="employeeRole">
                                <?php $this->loadUserRolesOptions(); ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Adresa:</td>
                            <td><textarea name="employeeAddress"><?php $this->returnPostBackValuePlain("employeeAddress"); ?></textarea></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Přidat zaměstnance"></td>
                        </tr>

                    </table>
               </form>
            </div>
        </div>

        <?php
    }

    private function loadUserRolesOptions()
    {
        $selectQuery = $this->DBH->query("SELECT erole_id, erole_name FROM employee_role ORDER BY erole_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[erole_id]'>$r[erole_name]</option>";
        }

    }


}
?>