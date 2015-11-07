<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Class MySQLDriver
 * This class handles all the communication with database server
 * @author Daniel Dusek <dusekdan@gmail.com>
 */
final class MySQLDriver extends Config
{


    /**
     * SQL Connection constants
     */
    /// Constant containing server name
    const SERVER_NAME     = "localhost";
    /// Constant containing username
    const SERVER_USERNAME = "root";
    /// Constant containing password to server
    const SERVER_PASSWORD = "";
    /// Constant containing name of the database
    const SERVER_DBNAME   = "iisproject";
    /// Character set we use to work with database
    const SERVER_CHARSET = "utf8";

    /**
     * Error constants
     */
    /// Value of this constant is returner whenever database error occurs
    const DATABASE_ERROR  = -1;

    /**
     * Properties
     */

    private $mLastError = "";


    /**
     * Basically every page of the system uses database - which makes constructor ideal place to load configuration & establish connection
     */
    public function __construct()
    {
        session_start();

        /// Loading configuration
        parent::LoadConfiguration();

        mysql_connect(self::SERVER_NAME, self::SERVER_USERNAME, self::SERVER_PASSWORD);
        mysql_select_db(self::SERVER_DBNAME);

        $setCharacterSet = "SET NAMES " . self::SERVER_CHARSET . ";" ;
        $this->query($setCharacterSet);

    }


    /**
     * Setter for mLastError property. Is set only when verbose parameter of callee function is set to false
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
     * Serves for sending sql commands to database. Method expects outputs to be already secured.
     * @param $sql String sql command to be send to sql server
     * @param bool|true $checkForErrors Setting this parameter to false will result in no error check will be done after the sql command is sent to database
     * @param bool|true $useHTMLFormat Setting this parameter to false will strip output from error check (if provided) of HTML formatting
     * @param bool|true $verbose Setting this parameter to false will not print (echo) error check report on screen, it will only set property and return DATABASE_ERROR constant
     * @return int|resource|string
     */
    public function query($sql, $checkForErrors = true, $useHTMLFormat = true, $verbose = true)
    {
        // Re-setting mLastError to an empty String
        $this->setLastError("");

        // Sending command to database
        $resource = mysql_query($sql);


        if($checkForErrors)
        {
            // Setting variables containing information about result of operation
            $errorMessage = mysql_error();
            $errorNumber  = mysql_errno();

            // Based on values of $errorMessage & $errorNumber method either provides error report & terminates the operation
            if(!empty($errorMessage) && !empty($errorNumber))
            {
                // Usage of HTML in error report
                if($useHTMLFormat)
                {
                    $errorReport = "Database Error! <br> " . PHP_EOL . "<p>#". $errorNumber  .": " . $errorMessage . "</p>" . PHP_EOL;
                }
                else
                {
                    $errorReport = "Database Error! #$errorNumber: $errorMessage" . PHP_EOL;
                }

                // Printing information on screen & setting return value to error
                if($verbose)
                {
                    echo $errorReport;
                    return self::DATABASE_ERROR;
                }
                else // Or setting mLastError property and setting return value to error
                {
                    $this->setLastError($errorReport);
                    return self::DATABASE_ERROR;
                }
            }

        }

        // If everything is OK, we return the resource
        return $resource;
    }

    /**
     * Returns one row for the specified command TODO: Responses for error states
     * @param $sql String sql command to be send to database
     * @return array One fetched row from the database
     */
    public function fetch($sql)
    {
        $q = $this->query($sql);
        $data = mysql_fetch_assoc($q);
        return $data;
    }


    /// TODO: Create function that secures string & move it to separate class
    private function preventSQLInjection($value)
    {

    }


    /// TODO: Create function that secures string & move it to separate class
    private function preventXSS($value)
    {
        return $value;
    }



}

?>