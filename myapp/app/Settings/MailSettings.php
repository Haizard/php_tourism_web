<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string $adminNotificationEmail = 'admin@example.com';
    public string $fromAddress = 'no-reply@example.com';
    public string $fromName = 'Tourism Starter Kit';
    public bool $mailEnabled = false;
    public ?string $replyTo = null;

    public static function group(): string
    {
        return 'mail';
    }
}
