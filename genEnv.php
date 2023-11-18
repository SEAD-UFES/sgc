<?php

/**
 * @var string $fmtConfEnvText
 */
$fmtConfEnvText = "\n; SGC added env variables\n";

/**
 * @var array<int, string> $laravelEnvKeys
 */
$laravelEnvKeys = array(
    'APP_ADMIN_EMAIL',
    'APP_DEBUG',
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
    'FILESYSTEM_DISK',
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

// Add env variables to PHP-FPM www.conf
foreach ($laravelEnvKeys as $laravelEnvKey) {
    $fmtConfEnvText .= 'env[' . $laravelEnvKey . '] = ' . '$' . $laravelEnvKey . "\n";
}

$fmtConfEnvText .= 'env[APP_BUILD] = ' . file_get_contents('BUILD') . "\n";

/**
 * @var int $bytesWritten
 */
$bytesWritten = file_put_contents('/etc/php/php-fpm.d/www.conf', $fmtConfEnvText, FILE_APPEND);

echo $bytesWritten . " bytes written to PHP-FPM www.conf\n";

unlink(__FILE__);
