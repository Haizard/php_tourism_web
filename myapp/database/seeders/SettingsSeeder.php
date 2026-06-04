<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $groups = [
            'general' => [
                'siteName'     => json_encode('Tourism Starter Kit'),
                'tagline'      => json_encode('A reusable Laravel tourism management starter kit.'),
                'logo'         => 'null',
                'favicon'      => 'null',
                'contactEmail' => json_encode('contact@example.com'),
                'contactPhone' => json_encode('+1 555 0100'),
                'address'      => json_encode('123 Tourism Ave, Travel City'),
                'socialLinks'  => json_encode([]),
            ],
            'mail' => [
                'adminNotificationEmail' => json_encode('admin@example.com'),
                'fromAddress'            => json_encode('no-reply@example.com'),
                'fromName'               => json_encode('Tourism Starter Kit'),
                'mailEnabled'            => 'false',
                'replyTo'                => 'null',
            ],
            'seo' => [
                'defaultMetaTitle'       => json_encode('Tourism Starter Kit'),
                'defaultMetaDescription' => json_encode('A reusable Laravel tourism management starter kit for travel agencies and tourism websites.'),
                'ogImage'                => 'null',
                'twitterHandle'          => 'null',
            ],
            'theme' => [
                'primaryColor'      => json_encode('#0ea5e9'),
                'accentColor'       => json_encode('#f97316'),
                'backgroundColor'   => json_encode('#eef9fb'),
                'fontFamily'        => json_encode('Figtree, ui-sans-serif, system-ui, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji'),
                'heroOverlayOpacity' => '0.45',
            ],
            'language' => [
                'defaultLocale'  => json_encode('en'),
                'enabledLocales' => json_encode(['en', 'fr', 'de', 'es', 'ar', 'sw']),
            ],
        ];

        foreach ($groups as $group => $settings) {
            foreach ($settings as $name => $payload) {
                DB::table('settings')->updateOrInsert(
                    ['group' => $group, 'name' => $name],
                    [
                        'locked'     => false,
                        'payload'    => $payload,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }
    }
}
