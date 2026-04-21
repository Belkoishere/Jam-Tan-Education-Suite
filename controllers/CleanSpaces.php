<?php

function cleanSpaces(string $text): string {
    // Replace multiple whitespace characters with a single space
    $text = preg_replace('/\s+/', ' ', $text);

    // Trim leading and trailing spaces
    return trim($text);
}

?>