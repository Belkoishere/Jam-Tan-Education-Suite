<?php

class French implements Language {

    public function GetEquivalent($word): string {
        $dictionary = [
            "January" => "Janvier",
            "February" => "Février",
            "March" => "Mars",
            "April" => "Avril",
            "May" => "Mai",
            "June" => "Juin",
            "July" => "Juillet",
            "August" => "Août",
            "September" => "Septembre",
            "October" => "Octobre",
            "November" => "Novembre",
            "December"=> "Décembre",
            "Mr" => "M",
            "Mrs" => "Mme",
            "Ms" => "Mlle"
        ];

        return $dictionary[$word] ?? $word;
    }
}
?>