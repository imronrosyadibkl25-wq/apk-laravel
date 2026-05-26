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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('xp')->default(0)->after('avatar');
            $table->integer('level')->default(1)->after('xp');
            $table->string('last_quest_reset_date')->nullable()->after('level');
            $table->text('completed_quests_today')->nullable()->after('last_quest_reset_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp', 'level', 'last_quest_reset_date', 'completed_quests_today']);
        });
    }
};
