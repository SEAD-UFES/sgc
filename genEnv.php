<?php
$envs = getenv();
$envFileText = "";

foreach ($envs as $key => $value) {
    $envFileText .= $key . "=" . $value . "\n";
}

file_put_contents(".env", $envFileText);
?>