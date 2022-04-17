<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('post');
            $table->string('available_time')->default('')->comment('可用时长');
            $table->boolean('is_discard');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proxies');
    }
};
