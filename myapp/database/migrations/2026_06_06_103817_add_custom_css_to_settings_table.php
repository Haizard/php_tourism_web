<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            'group'   => 'theme',
            'name'    => 'customCss',
            'payload' => json_encode(''),
            'locked'  => false,
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('group', 'theme')->where('name', 'customCss')->delete();
    }
};
