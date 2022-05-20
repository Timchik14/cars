<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number', 255)->unique();
            $table->string('mark', 255)->nullable(false);
            $table->string('model', 255);
            $table->string('color', 255);
            $table->string('vin', 255);
            $table->decimal('load_capacity', 12, 2);
            $table->integer('holding_capacity');
            $table->string('glonass', 255);
            $table->json('pts')->nullable();
            $table->json('sts')->nullable();
            $table->json('lease');
            $table->unsignedBigInteger('created_by');
            $table->smallInteger('type')->default(1)->nullable();
            $table->unsignedBigInteger('company_id');
            $table->integer('status')->default(1)->nullable(false);
            $table->integer('updated_by');
            $table->string('report_uid', 255);
            $table->decimal('unloaded_weight', 6, 3);
            $table->integer('country_id')->default(0)->nullable(false);
            $table->boolean('is_non_resident')->default(false)->nullable(false);
            $table->unsignedBigInteger('dd_id');
            $table->string('external_id', 255)->unique();
            $table->string('temperature_recorder', 255);
            $table->string('tracking_number', 255);
            $table->string('sts_number', 255)->default('')->nullable(false);
            $table->integer('fines_monitoring_id');
            $table->smallInteger('owner_confirmation_status');
            $table->smallInteger('platform_type');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('owner_inn', 255)->nullable();
            $table->smallInteger('owner_entity_type')->nullable();

            $table
                ->foreign('created_by')
                ->references('id')
                ->on('users');
            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies');
            $table
                ->foreign('dd_id')
                ->references('id')
                ->on('tms_nodes');
            $table
                ->foreign('owner_id')
                ->references('id')
                ->on('companies');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
