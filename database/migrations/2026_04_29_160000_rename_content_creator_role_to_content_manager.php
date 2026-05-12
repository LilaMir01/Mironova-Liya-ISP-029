<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'content_creator')
            ->update(['role' => 'content_manager']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'content_manager')
            ->update(['role' => 'content_creator']);
    }
};
