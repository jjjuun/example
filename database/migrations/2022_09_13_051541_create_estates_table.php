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
        Schema::create('estates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->timestamps();
            $table->longText('EstateName')->nullable();
            $table->string('Type')->nullable();
            $table->string('Prefecture')->nullable();
            $table->string('Municipality')->nullable();
            $table->string('DistrictName')->nullable();
            $table->string('FloorPlan')->nullable();
            $table->string('BuildingYear')->nullable();
            $table->string('status')->nullable()->default("検討中");
            $table->string('detail')->nullable()->default("ここに詳細を記入してください");
            $table->integer('DB_status')->default(1);
            $table->integer('BuyPrice')->nullable();
            $table->integer('property_income')->nullable();//家賃収入
            $table->integer('property_management_cost')->nullable();//管理費
            $table->integer('perperty_maintenance_cost')->nullable();//修繕積立費
            $table->integer('KARI_PROPERTY_TAX')->nullable();//固定資産税
            $table->integer('KARI_CITY_PLAN_TAX')->nullable();//都市計画税
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estates');
    }
};
