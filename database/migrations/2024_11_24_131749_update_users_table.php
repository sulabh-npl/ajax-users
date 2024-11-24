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
            $table->foreignId('role_id')->constrained()->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->longText('description')->nullable()->after('phone');
            $table->string('profile_image')->nullable()->after('description');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('remember_token');
            $table->dropColumn('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('phone');
            $table->dropColumn('description');
            $table->dropColumn('profile_image');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('password');
        });
    }
};
