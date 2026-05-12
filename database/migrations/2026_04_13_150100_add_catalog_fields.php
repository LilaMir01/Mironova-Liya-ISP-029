<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_types', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('category_slug')->default('facade')->after('slug');
            $table->string('image_path')->nullable()->after('category_slug');
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('name');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('dimensions');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });

        Schema::table('material_types', function (Blueprint $table) {
            $table->dropColumn(['slug', 'category_slug', 'image_path']);
        });
    }
};
