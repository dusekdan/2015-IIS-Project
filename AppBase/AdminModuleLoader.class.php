<?php
final class AdminModuleLoader
{
    public function __construct()
    {
        // Nothing so far
    }

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
}

?>