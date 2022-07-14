<?php

/**
 * @var array<string, string> $sysEnvs
 */
$sysEnvs = getenv();

/**
 * @var string $envFileText
 */
$envFileText = '';

ksort($sysEnvs);

/**
 * @var array<int, string> $laravelEnvKeys
 */
$laravelEnvKeys = array(
    'APP_ADMIN_EMAIL',
    'APP_DEBUG',
    'APP_ADMIN_EMAIL',
    'APP_ENV',
    'APP_KEY',
    'APP_NAME',
    'APP_TIMEZONE',
    'APP_URL',
    'BACKUP_ARCHIVE_PASSWORD',
    'BROADCAST_DRIVER',
    'CACHE_DRIVER',
    'DB_CONNECTION',
    'DB_DATABASE',
    'DB_HOST',
    'DB_PASSWORD',
    'DB_PORT',
    'DB_USERNAME',
    'FILESYSTEM_CLOUD',
    'FILESYSTEM_DRIVER',
    'GOOGLE_DRIVE_CLIENT_ID',
    'GOOGLE_DRIVE_CLIENT_SECRET',
    'GOOGLE_DRIVE_FOLDER',
    'GOOGLE_DRIVE_REFRESH_TOKEN',
    'LOG_CHANNEL',
    'LOG_LEVEL',
    'MAIL_EHLO_DOMAIN',
    'MAIL_ENCRYPTION',
    'MAIL_FROM_ADDRESS',
    'MAIL_FROM_NAME',
    'MAIL_HOST',
    'MAIL_MAILER',
    'MAIL_PASSWORD',
    'MAIL_PORT',
    'MAIL_USERNAME',
    'MEMCACHED_HOST',
    'QUEUE_CONNECTION',
    'SESSION_DRIVER',
    'SESSION_LIFETIME',
);

foreach ($laravelEnvKeys as $laravelEnvKey) {
    if (array_key_exists($laravelEnvKey, $sysEnvs)) {
        $envFileText .= $laravelEnvKey . '=' . $sysEnvs[$laravelEnvKey] . "\n";
    }
}

/**
 * @var int $bytesWritten
 */
$bytesWritten = file_put_contents('.env', $envFileText);

echo $bytesWritten . " bytes written to .env\n";
