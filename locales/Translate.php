<?php

class Translate{
    private $Language;

    public function __construct(Language $Language)
    {
        $this->Language = $Language;
    }

    public function SetLanguage(Language $Language)
    {
        $this->Language = $Language;
    } 

    public function Translate(string $word)
    {
        return $this->Language->GetEquivalent($word);
    }
}

?>