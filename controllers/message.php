<!-- This php file contains functions used to set messages 
 in session storage and retrieve them -->
<?php
function set_message($key, $message) {
    $_SESSION['message'][$key] = $message;
}

function get_message($key) {
    if (!empty($_SESSION['message'][$key])) {
        $msg = $_SESSION['message'][$key];
        // when a message is retrieved it is removed from session storage, 
        // this frees up memory and reduces the time that messages are exposed
        unset($_SESSION['message'][$key]);
        return htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    }
    // if a message does not exist return an empty string
    return '';
}
?>