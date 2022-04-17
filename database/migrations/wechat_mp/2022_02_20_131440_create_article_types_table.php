<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('article_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tag');
            $table->string('pos_num');
            $table->string('key');
            $table->string('title');
            $table->text('url');
            $table->string('cover_image_url');
            $table->string('mp_create_at');
            $table->string('msgid');
            $table->string('itemidx');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_types');
    }
};
