<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wechat_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('aid')->nullable();
            $table->text('album_id')->nullable();
            $table->text('appmsgid')->nullable();
            $table->text('checking')->nullable();
            $table->text('copyright_type')->nullable();
            $table->text('cover')->nullable();
            $table->text('create_time')->nullable();
            $table->text('digest')->nullable();
            $table->text('has_red_packet_cover')->nullable();
            $table->text('is_pay_subscribe')->nullable();
            $table->text('item_show_type')->nullable();
            $table->text('itemidx')->nullable();
            $table->text('link')->nullable();
            $table->text('media_duration')->nullable();
            $table->text('mediaapi_publish_status')->nullable();
            $table->text('title')->nullable();
            $table->text('update_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wechat_article');
    }
};
