<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEyeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("eye_types", function (Blueprint $table) {
            $table->tinyIncrements("id");
            $table->string("name");
            $table->boolean("is_active")->default(1)->index();
            $table->timestamps();
        });

        DB::table("eye_types")->insert([
            [
                "id" => 1,
                "name" => "Googly Eyes",
                "is_active" => true,
            ],
            [
                "id" => 2,
                "name" => "Robot Eyes",
                "is_active" => true,
            ],
            [
                "id" => 3,
                "name" => "Droopy Eyes",
                "is_active" => true,
            ],
            [
                "id" => 4,
                "name" => "Heart Eyes",
                "is_active" => true,
            ],
            [
                "id" => 5,
                "name" => "Emerald Eyes",
                "is_active" => true,
            ],
            [
                "id" => 6,
                "name" => "Bloodshot Eyes",
                "is_active" => true,
            ],
            [
                "id" => 7,
                "name" => "Rockstar Eyes",
                "is_active" => true,
            ],
            [
                "id" => 8,
                "name" => "Evil Eyes",
                "is_active" => true,
            ],
            [
                "id" => 9,
                "name" => "Crazy Eyes",
                "is_active" => true,
            ],
            [
                "id" => 10,
                "name" => "Black Glasses",
                "is_active" => true,
            ],
            [
                "id" => 11,
                "name" => "Slot Glasses",
                "is_active" => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("eye_types");
    }
}
