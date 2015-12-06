<?php
/**
 * Created by PhpStorm.
 * User: Dan
 * Date: 11/26/2015
 * Time: 4:47 PM
 */

class PostBackHelper
{

    public function showMessage($message, $type = "info")
    {
        switch($type)
        {
            case "info":
                echo "<div class='message-info'><p>" . PHP_EOL;
                echo $message;
                break;

            case "warning":
                echo "<div class='message-warning'><p>" . PHP_EOL;
                echo $message;
                break;

            case "error":
                echo "<div class='message-error'><p>" . PHP_EOL;
                echo $message;
                break;
        }
        echo "</p></div> ";
    }

    public function clearFormFields($inputArray)
    {
        echo "<script type='text/javascript'>";
        echo "function clearFormFields(){" .PHP_EOL;
        foreach($inputArray as $input)
        {
            echo "document.getElementsByName(\"$input\")[0].value = \"\"; " . PHP_EOL;
        }
        echo PHP_EOL . "}</script>";
    }

    public function refreshPage($returnParameter, $timer = 0)
    {

        $refreshUrl = $_SERVER["REQUEST_URI"];
        // Replace original post back parameter
        $refreshUrl = str_replace("&".$returnParameter, "", $refreshUrl);
        $refreshUrl = str_replace("?".$returnParameter, "", $refreshUrl);

        // Add new
        if(strpos($refreshUrl, '?') !== false)
        {
            $refreshUrl .= "&" . $returnParameter;
        }
        else
        {
            $refreshUrl .= "?" . $returnParameter;
        }


        ?>

            <meta http-equiv="refresh" content="<?php echo $timer; ?>;url=<?php echo $refreshUrl;?>">

        <?php
    }

    public function redirectPage($url, $timer = 0)
    {
        ?>
        <meta http-equiv="refresh" content="<?php echo $timer;?>; url=<?php echo $url; ?>">
        <?php
    }




}


?>