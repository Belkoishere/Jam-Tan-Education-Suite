<?php

function containsAll(string $haystack, array $needles): bool {
    foreach ($needles as $needle) {
        if ($needle !== '' && strpos($haystack, $needle) === false) {
            return false;
        }
    }
    return true;
}

?>