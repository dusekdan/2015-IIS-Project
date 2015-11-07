<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Class Config
 * @description Basic class that provides basic settings
 * @author Daniel Dusek <dusekdan@gmail.com>
 */
abstract class Config
{
    /// Constant with character set
    const CHARSET                            = "utf-8";

    /// Constants with allowed error_reporting() levels
    const ERROR_REPORTING_LEVEL_ALL          = "All";
    const ERROR_REPORTING_LEVEL_NONE         = "None";
    const ERROR_REPORTING_LEVEL_NODEPRECATED = "No Deprecated";


    /**
     * Method called from child class - all configuration is done here (via other methods)
     */
    protected function LoadConfiguration()
    {
        $this->setDocumentEncoding();
        $this->setErrorReporting(self::ERROR_REPORTING_LEVEL_NODEPRECATED);
    }


    /**
     * Method setting the Content-Type headers based on class constant CHARSET
     */
    private function setDocumentEncoding()
    {
        header("Content-type: text/html;charset=" . self::CHARSET);
    }


    /**
     * Sets reporting level for application
     * @param $reportLevel String constant determining level of notices
     */
    private function setErrorReporting($reportLevel)
    {
        switch($reportLevel)
        {
            case "All":
                error_reporting(E_ALL);
                break;
            case "No Deprecated":
                error_reporting(E_ALL ^ E_DEPRECATED);
                break;
            case "None":                                // TO-DO: Possibly not really affecting anything
                error_reporting(0);
                break;
            default:
                error_reporting(E_ALL ^ E_DEPRECATED);
                break;
        }
    }

}
?>