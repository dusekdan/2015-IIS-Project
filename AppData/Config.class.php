<?php

/**
 * Class Config
 * @description Basic class that provides basic settings
 * @author Daniel Dusek <dusekdan@gmail.com>
 */
abstract class Config
{

    /// Constant with character set
    const CHARSET               = "utf-8";
    //const ERROR_REPORTING_LEVEL = ""

    /**
     * Method called from child class - all configuration is done here (via other methods)
     */
    protected function LoadConfiguration()
    {
        this.$this->setDocumentEncoding();
    }

    /**
     * Method setting the Content-Type headers based on class constant CHARSET
     */
    private function setDocumentEncoding()
    {
        header("Content-type: text/html;charset=" . self::CHARSET);
    }

    /**
     * TODO: Prepared for error reporting level setting
     */
    private function setErrorReporting()
    {

    }

}
?>