<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('slot')->unique();
            $table->string('image_path', 1024);
            $table->string('alt_text', 255)->nullable();
            $table->timestamps();
        });

        $default = 'images/vu-anh-ExOmPidaHvY-unsplash.jpg';
        $rows = [
            ['slot' => 1, 'image_path' => $default, 'alt_text' => 'Слайд 1', 'created_at' => now(), 'updated_at' => now()],
            ['slot' => 2, 'image_path' => 'images/oneOne.png', 'alt_text' => 'Слайд 2', 'created_at' => now(), 'updated_at' => now()],
            ['slot' => 3, 'image_path' => 'images/twoTwo.png', 'alt_text' => 'Слайд 3', 'created_at' => now(), 'updated_at' => now()],
            ['slot' => 4, 'image_path' => $default, 'alt_text' => 'Слайд 4', 'created_at' => now(), 'updated_at' => now()],
            ['slot' => 5, 'image_path' => $default, 'alt_text' => 'Слайд 5', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('home_slides')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_slides');
    }
};
