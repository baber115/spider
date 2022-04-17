<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wechat_article_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('album_id');
            $table->text('target_id');
            $table->text('tagSource');
            $table->text('title');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wechat_article_album');
    }
};
