<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoFactorColumnsToDashboardUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table(config('dashboard.users.table'), function (Blueprint $table) {
            $table->text('two_factor_secret')
                ->after('password')
                ->nullable()
            ;

            $table->text('two_factor_recovery_codes')
                ->after('two_factor_secret')
                ->nullable()
            ;
        });
    }

    public function down(): void
    {
        Schema::table(config('dashboard.users.table'), function (Blueprint $table) {
            $table->dropColumn('two_factor_secret', 'two_factor_recovery_codes');
        });
    }
}
