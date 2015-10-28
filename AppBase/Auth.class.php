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
     * @description Provides access to database helper for the whole class
     * @param $DBDriver MySQLDriver object
     */
    public function __construct($DBDriver)
    {
        $this->DBH = $DBDriver;
    }

    /**
     * TODO
     * @description Verifies whether the user & password combination exists in database
     * @param $name String username
     * @param $password String password
     * @return bool
     */
    public function verifyUserCredentials($name, $password)
    {
        // Prepare password to be send to database
        $password = $this->hashPassword($password);

        //$sql = "SELECT "
        return false;
    }

    /**
     * @description Creates a hash based on salt provided by CREDENTIALS_SALT class constant
     * @param $string String to be hashed
     * @return string String that is hashed
     */
    private function hashPassword($string)
    {
        return crypt($string, self::CREDENTIALS_SALT);
    }

    // TODO
    private function generateUniqueHash()
    {

    }
}

?>