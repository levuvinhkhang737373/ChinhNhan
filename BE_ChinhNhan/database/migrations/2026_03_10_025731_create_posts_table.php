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
        Schema::create('posts', function (Blueprint $table) {
        
            $table->id();
            $table->unsignedInteger('member_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->boolean('display')->default(1);
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->index('member_id');
            $table->index('status');
            $table->index('display');
             $table->index('id');
            $table->index(['status','display']);
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->cascadeOnDelete();
        });
}
   
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
