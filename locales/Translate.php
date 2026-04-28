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

    public function Translate(?string $word = null)
    {   
        $word = $word ?? '';
        if ($word == ''){
            return $word;
        }
        else {
            return $this->Language->GetEquivalent($word);
        }
        
    }
}

?>