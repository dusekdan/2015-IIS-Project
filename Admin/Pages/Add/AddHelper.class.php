<?php
final class AddHelper
{

    private $DBH;
    private $AUTH;
    private $FILTER;

    public function __construct($DBDriver, $Auth, $IF)
    {
        echo "Ending right here!";
        $this->DBH      = $DBDriver;
        $this->AUTH     = $Auth;
        $this->FILTER   = $IF;

        $this->showUsers();
    }


    public function showUsers()
    {
        $sql = $this->DBH->query("select * from employee order by emp_fullname", true, true, false);

        if($sql != -1)
        {
            while($r = mysql_fetch_assoc($sql))
            {
                echo "Username: " . $r["emp_username"] . "<br>" . PHP_EOL;
            }
        }
        else
        {
            echo $this->DBH->getLastError();
        }




    }
}
?>