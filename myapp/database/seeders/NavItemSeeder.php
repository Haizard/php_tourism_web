<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        if (NavItem::count() > 0) {
            return;
        }

        $items = [
            [
                'label'      => 'Home',
                'type'       => 'manual',
                'url'        => '/',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'label'      => 'Tours',
                'type'       => 'manual',
                'url'        => '/tours',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'label'        => null,
                'type'         => 'destinations_hub',
                'url'          => null,
                'sort_order'   => 3,
                'is_active'    => true,
                'reference_id' => null,
            ],
            [
                'label'      => 'Blog',
                'type'       => 'manual',
                'url'        => '/blog',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'label'      => 'Contact',
                'type'       => 'manual',
                'url'        => '/contact',
                'sort_order' => 5,
                'is_active'  => true,
            ],
        ];

        foreach ($items as $item) {
            NavItem::create($item);
        }
    }
}
