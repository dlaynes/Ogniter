<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefenseDebris extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('universes','debris_factor_def')){

            //DB::statement("ALTER TABLE `universes` CHANGE `created_at` `created_at` TIMESTAMP NULL");
            //DB::statement("ALTER TABLE `universes` CHANGE `updated_at` `updated_at` TIMESTAMP NULL");
            //DB::statement("UPDATE universes SET updated_at='2012-09-01 10:00:00' WHERE updated_at IS NULL");
            //DB::statement("UPDATE universes SET created_at='2012-09-01 10:00:00' WHERE created_at IS NULL");


            DB::unprepared("ALTER TABLE `universes` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT current_timestamp(), CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT current_timestamp()");

            Schema::table('universes', function (Blueprint $table) {
                $table->decimal('debris_factor_def', 3, 2)->default(0.3);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
