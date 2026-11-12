<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_ranking_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('position')->unique();
            $table->decimal('money', 10, 2)->default(0);
            $table->json('commands')->nullable();
            $table->boolean('need_online')->default(false);
            $table->timestamps();
        });

        Schema::create('vote_ranking_reward_server', function (Blueprint $table) {
            $table->unsignedInteger('reward_id');
            $table->unsignedInteger('server_id');

            $table->primary(['reward_id', 'server_id']);

            $table->foreign('reward_id')
                ->references('id')
                ->on('vote_ranking_rewards')
                ->cascadeOnDelete();

            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->cascadeOnDelete();
        });

        Schema::create('vote_ranking_distributions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period', 7)->unique();
            $table->timestamps();
        });

        Schema::create('vote_ranking_winners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('distribution_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('position');
            $table->unsignedInteger('votes');
            $table->decimal('money', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('distribution_id')
                ->references('id')
                ->on('vote_ranking_distributions')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_ranking_winners');
        Schema::dropIfExists('vote_ranking_distributions');
        Schema::dropIfExists('vote_ranking_reward_server');
        Schema::dropIfExists('vote_ranking_rewards');
    }
};
