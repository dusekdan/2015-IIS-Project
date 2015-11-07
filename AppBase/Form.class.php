<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


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

