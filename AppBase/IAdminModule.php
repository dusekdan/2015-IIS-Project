<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/



/**
 * Interface IAdminModule
 * @author Daniel Dušek <dusekdan@gmail.com>
 * Each admin module to be loaded needs to implement this interface
 * as it will ensure that there is existing constructor that takes
 * database object, authentication object and input filter object
 *
 * There is no requirement for class to work with those objects,
 * but it is important for every admin module to have access to
 * such functionality.
 *
 * Other than that, there are (few) more methods that are supposedly
 * handy to implement - they provide information about admin module
 * etc (no need for configuration/credits files)
 */
interface IAdminModule
{
    /** Construtor, passing Database, Authentication and InputFilter helpers */
    public function __construct($DB, $Auth, $IF);

    /** Returns version of the module */
    public function _getModuleVersion();

    /** Returns string author of the module */
    public function _getModuleAuthor();

    /** Returns full HTML formatted description of the module */
    public function _getModuleDescription();
}
?>