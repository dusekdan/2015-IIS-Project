<?php

/**
 * Class Auth
 * @description Handles user authentication & log on/log off processes
 * @author Daniel Dusek <dusekdan@gmail.com>
 */
final class Auth
{

    /// Database helper property
    private $DBH;

    /// Salt used for hashing credentials (only password at the moment)
    const CREDENTIALS_SALT = "An0us3k4ndD4n0us3k";

    /**
     * Provides access to database helper for the whole class
     * @param $DBDriver MySQLDriver object
     */
    public function __construct($DBDriver)
    {
        $this->DBH = $DBDriver;
    }


    /**
     * Verifies whether the user & password combination exists in database
     * @param $name String username
     * @param $password String password
     * @return bool
     */
    private function verifyEmployeeCredentials($name, $password)
    {
        // Prepare password to be send to database
        $password = $this->hashPassword($password);

        $sql = $this->DBH->fetch("SELECT emp_id FROM employee WHERE emp_username='$name' AND emp_password='$password'");

        if(empty($sql))
        {
            return false;
        }
        return true;
    }


    /**
     * TODO: Add timestamp check to satisfy task assignment conditions
     * Verifies whether the user session is still valid
     * @param $uid
     * @param $hash
     * @return bool
     */
    public function verifyEmployeeSession($uid, $hash)
    {
        $sql = $this->DBH->fetch("SELECT * FROM employee_hash WHERE ehash_value='$hash' AND ehash_employee='$uid'");
        if(empty($sql))
        {
            return false;
        }
        return true;
    }


    /**
     * Logs user on, or returns false when credentials are not correct
     * @param $name String User name
     * @param $password String password
     * @return bool True when employee is logged on, false when not
     */
    public function logOnEmployee($name, $password)
    {
        if($this->verifyEmployeeCredentials($name, $password)) {
            // Gather data about user to be logged in (no need for additional checks; it comes to this phase only when the user credentials are verified already)
            $sql = $this->DBH->fetch("SELECT emp_id FROM employee WHERE emp_username='$name'");
            $uid = $sql["emp_id"];
            $hash = $this->generateUniqueHash();

            // Insert hash to database
            $this->DBH->query("INSERT INTO employee_hash (ehash_value, ehash_time, ehash_employee) VALUES ('$hash', NOW(), '$uid')"); //TODO Refactor this function, make it pretty

            // SESSION set up
            $_SESSION["emp_hash"] = $hash;
            $_SESSION["emp_id"] = $uid;

            return true;
        }
        return false;
    }


    /**
     * Logs out the employee that is currently logged on (removes database record, destroys session)
     */
    public function logOffEmployee()
    {
        $this->destroyEmployeeHash($_SESSION["emp_id"], $_SESSION["emp_hash"]);
        $this->destroyEmployeeSession();
    }


    /**
     * Destroys record in database for user identified by parameters
     * @param $uid Integer Identifies the user
     * @param $hash String Identifies the user session
     */
    private function destroyEmployeeHash($uid, $hash)
    {
        $removeSql = "DELETE FROM employee_hash WHERE ehash_value='$hash' AND ehash_employee='$uid'";
        $this->DBH->query($removeSql);
    }


    /**
     * Destroys SESSIONs
     */
    private function destroyEmployeeSession()
    {
        if(isset($_SESSION["emp_id"]))
        {
            unset($_SESSION["emp_id"]);
        }

        if(isset($_SESSION["emp_hash"]))
        {
            unset($_SESSION["emp_hash"]);
        }
    }


    /**
     * Creates a hash based on salt provided by CREDENTIALS_SALT class constant
     * @param $string String to be hashed
     * @return string String that is hashed
     */
    private function hashPassword($string)
    {
        return crypt($string, self::CREDENTIALS_SALT);
    }


    /**
     * Generates random string hash (does not have to be unique, but usually is)
     * @return String random, possibly unique, string
     */
    private function generateUniqueHash()
    {
        $rand = mt_rand(0, 10000);
        $string = $this->generateRandomString();
        $hash = md5($rand . $string);
        return $hash;
    }


    /**
     * Helper method for generateUniqueHash() method
     * @param int $length Integer length of string to be generated
     * @return string Random string of specified length
     */
    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
?>