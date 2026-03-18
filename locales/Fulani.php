<?php
require("Language.php");
class Fulani extends Language{
    public function Month($month){
        $intmonth = intval(str_replace("0", "", $month)) - 1;

        $month_name=array("Silo","Cholte","Mboy","Setto","Dujjal","Korso","Morso","Juko",
        "Silto","Yarkuma","Jolal","Bowtal");

        return $month_name[$intmonth];
    }
}
?>