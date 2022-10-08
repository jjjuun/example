<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // 追加分
            $table->string('name')->nullable();
            $table->string('name_kana')->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('contact_type')->nullable();
            $table->string('email')->nullable();
            $table->integer('age')->nullable();
            $table->string('pref')->nullable();
            $table->string('agree')->nullable();
            $table->string("file_name")->nullable();
			$table->string("file_path")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
};
