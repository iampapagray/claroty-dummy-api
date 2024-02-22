<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /****
         $newObj->uid = "";
            $newObj->asset_id = "";
            $newObj->risk_score = "";
            $newObj->os_category = "";
            $newObj->labels = [];
            $newObj->device_type_family = "";
            $newObj->vlan_list = [];
            $newObj->mac_list = [];
            $newObj->device_subcategory = "";
            $newObj->network_list = [];
            $newObj->model = "";
            $newObj->device_type = "";
            $newObj->device_category = "";
            $newObj->ip_list = [];
         * **/ 
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('asset_id');
            $table->string('risk_score');
            $table->string('risk_score');
            $table->string('risk_score');
            $table->string('risk_score');
            $table->string('risk_score');
            $table->string('risk_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
