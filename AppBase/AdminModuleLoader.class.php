<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Class AdminModuleLoader
 * Contains logic for loading modules to administration
 */
final class AdminModuleLoader
{
    /**
     * TODO: Determine whether this is duplicate code
     * @param $moduleName
     * @param string $rootDir
     * @return bool
     */
    public function checkModuleExists($moduleName, $rootDir = "./")
    {
        $scan = scandir($rootDir . $moduleName);

        // Boolean condition variables
        $moduleFile = false;
        $moduleHelperClass = false;

        foreach($scan as $file)
        {
            if($file == "." || $file == "..")   // skipping current folder and the folder one level above
            {
                continue;
            }

            if($file == $moduleName . ".php")
            {
                $moduleFile = true;
            }

            if($file == $moduleName . "Helper.class.php")
            {
                $moduleHelperClass = true;
            }
        }

        return $moduleFile && $moduleHelperClass;
    }


    /**
     * @param $className String name of the class to be checked on interface implementation
     * @return bool True when class implements given interface, False when it does not
     */
    public function checkModuleImplementsInterface($className)
    {
        $interfaces = class_implements($className);
        return in_array("IAdminModule", $interfaces);
    }
}

?>