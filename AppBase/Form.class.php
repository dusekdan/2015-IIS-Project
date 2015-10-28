<?php
/**
 * Class Form
 * This is a concept-class no real usage at the moment
 * @description Creates & receives forms and form data
 * @author Daniel Dusek <dusekdan@gmail.com>
 */

class Form
{

    private $DBH;

    public function __construct($DBDriver)
    {
        $this->DBH = $DBDriver;
    }

    public function createField($name, $value="", $type="text", $id="", $class="")
    {

    }

}


?>

