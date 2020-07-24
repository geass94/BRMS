<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

class mainClass {
    private $className;
	//
    public function loadClass($className) {
        $this->className = strtolower($_SERVER['DOCUMENT_ROOT']."/".CLS_DIR . $className . ".class.php");
        if (file_exists($this->className)) {
            include($this->className);          
        } else {
            die("<p align=\"center\">შეცდომა კლასის ჩატვირთვის დროს: კლასი <b>" . $this->className . "</b> არ არსებობს</p>");
        }
    } // loadClass-is dasasruli

} // mainClass-is dasasruli
?>