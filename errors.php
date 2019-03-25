<?php
function displayError($errors){
    foreach ($errors as $key => $value) {
        echo "$value"."<br>";
    }
}
?>