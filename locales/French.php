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
            "Ms" => "Mlle",
            "Pass" => "Réussite",
            "Fail" => "Échec",
            "Present" => "Présent",
            "Absent" => "Absent",
            "Exam" => "Examen",
            "Assignment" => "Devoir"
        ];

        return $dictionary[$word] ?? $word;
    }
}
?>