<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique()->index();
            $table->string('name_prefix');
            $table->string('first_name');
            $table->string('middle_initial', 1);
            $table->string('last_name');
            $table->enum('gender', ['M', 'F']);
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->time('time_of_birth');
            $table->decimal('age_in_years', 5);
            $table->date('date_of_joining');
            $table->decimal('age_in_company_years', 5);
            $table->string('phone_no', 20);
            $table->string('place_name');
            $table->string('county');
            $table->string('city');
            $table->string('zip', 10);
            $table->string('region');
            $table->string('username')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('sessions');
    }
};
