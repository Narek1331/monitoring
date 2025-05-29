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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('verification_method_id');
            $table->bigInteger('inspection_cost_id')->nullable();
            $table->boolean('status')->default(true);
            $table->string('name')->nullable();
            $table->string('protocol')->default('http://');
            $table->string('address_ip');
            $table->string('port')->nullable();
            $table->boolean('control_domain')->nullable()->default(false);
            $table->boolean('control_ssl')->nullable()->default(false);
            $table->boolean('site_virus_check')->nullable()->default(false);
            $table->string('frequency_of_inspection')->nullable();
            $table->string('error_check_interval')->nullable();
            $table->string('error_notification_threshold')->nullable();
            $table->string('remind_on_error')->nullable();
            $table->boolean('notify_on_recovery')->nullable()->default(false);
            $table->boolean('task_status_rss')->nullable()->default(false);
            $table->text('error_message')->nullable();
            $table->string('site_timeout_duration')->nullable();
            $table->string('timezone')->nullable();
            $table->string('search_text_in_response')->nullable();
            $table->string('text_presence_error_check')->nullable();
            $table->string('header_for_request')->nullable();
            $table->boolean('dangerous_sites_detection')->nullable()->default(false);
            $table->boolean('send_critical_error_alerts')->nullable()->default(false);
            $table->boolean('ignore_error_recovery')->nullable()->default(false);
            $table->boolean('notify_on_rkn_domain_detection')->nullable()->default(false);
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->string('save_response_time')->nullable();
            $table->string('notify_on_dns_error_9')->nullable();
            $table->string('notify_on_empty_response_6x')->nullable();
            $table->string('valid_response_code')->nullable();
            $table->string('ignored_error_codes')->nullable();
            $table->string('alert_on_specific_codes')->nullable();
            $table->boolean('follow_redirects')->nullable()->default(false);
            $table->string('response_number_range')->nullable();
            $table->string('page_size_range')->nullable();
            $table->boolean('set_as_template')->nullable()->default(false);
            $table->dateTime('last_check_date')->nullable();
            $table->string('token')->nullable();
            $table->string('ignored_directories')->nullable();
            $table->text('form_fields')->nullable();
            $table->date('report_date_from')->nullable();
            $table->date('report_date_to')->nullable();

            $table->boolean('sample')->nullable()->default(false);

            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
