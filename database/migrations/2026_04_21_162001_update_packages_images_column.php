<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->json('images')->nullable()->after('features');
        });

        // Pindahkan string image_path menjadi array di json
        $packages = DB::table('packages')->whereNotNull('image_path')->get();
        foreach ($packages as $pkg) {
            DB::table('packages')->where('id', $pkg->id)->update([
                'images' => json_encode([$pkg->image_path])
            ]);
        }

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('features');
        });

        $packages = DB::table('packages')->whereNotNull('images')->get();
        foreach ($packages as $pkg) {
            $imgs = json_decode($pkg->images, true);
            DB::table('packages')->where('id', $pkg->id)->update([
                'image_path' => $imgs[0] ?? null
            ]);
        }

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
