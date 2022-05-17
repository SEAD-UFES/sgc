<?php
$envs = getenv();
$envFileText = '';

foreach ($envs as $key => $value) {
    echo $envFileText .= $key . '=' . $value . "\n";
}

echo file_put_contents('/www/.env', $envFileText) . " bytes written to /www/.env\n";
