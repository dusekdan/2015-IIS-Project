<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Class InputFilter
 * Methods for different kinds of filtering user input
 * @author Daniel Dusek <dusekdan@gmail.com>
 */
class InputFilter
{

    /// Property that stored the last error that occurred (not recycled)
    private $mLastError = "";

    /**
     * Setter for mLastError property
     * @param $value String value of last error
     */
    private function setLastError($value)
    {
        $this->mLastError = $value;
    }


    /**
     * Getter for mLastError property
     * @return string Value of last error
     */
    public function getLastError()
    {
        return $this->mLastError;
    }

    /**
     * Checks if the password is in the correct format (longer than 6 characters)
     * @param $string String value of password
     * @return bool True if password is in correct format, false otherwise
     */
    public function checkPasswordFormat($string)
    {
        if(strlen($string) < 6)
        {
            // Setting information about what went wrong
            $this->setLastError("Password is too short!");
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks whether username is in correct format (not empty, atm.)
     * @param $string String value of Username
     * @return bool
     */
    public function checkUsernameFormat($string)
    {
        if(empty($string))
        {
            // Setting information about what went wrong
            $this->setLastError("Username is too short!");
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Removes dangerous characters from the string. This is usually a "bad practice" but in this specific situation is acceptable
     * @param $string String value of user input with possibility of containing dangerous characters
     * @return String without dangerous characters (may as well be empty string)
     */
    public function stripUnsafeCredentialsCharacters($string)
    {
        $string = str_replace("'", "", $string);
        $string = str_replace("\\", "", $string);
        $string = str_replace("\"", "", $string);
        return $string;
    }

    /**
     * Prepares input for insertion to database. Preparation is considering magic_quotes's possible states
     * @param $input
     * @return string
     */
    public function prepareInputForSQL($input)
    {
        if(get_magic_quotes_gpc())
        {
            $input = stripslashes($input);
        }

        return mysql_real_escape_string($input);
    }

    public function preparePlainText($output)
    {
        return false;
    }

    public function prepareText($output)
    {
        return htmlspecialchars($output, ENT_QUOTES);
    }

    public function isMail($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }


    public function isBCN($bcn)
    {
        if(preg_match("~[0-9]{6}\/[0-9]{4}~", $bcn) == 1)
            return true;
        else
            return false;
    }

}