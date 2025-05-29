<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'verification_method_id',
        'inspection_cost_id',
        'user_id',
        'status',
        'name',
        'protocol',
        'address_ip',
        'port',
        'control_domain',
        'control_ssl',
        'site_virus_check',
        'frequency_of_inspection',
        'error_check_interval',
        'error_notification_threshold',
        'remind_on_error',
        'notify_on_recovery',
        'task_status_rss',
        'error_message',
        'site_timeout_duration',
        'timezone',
        'search_text_in_response',
        'text_presence_error_check',
        'header_for_request',
        'dangerous_sites_detection',
        'send_critical_error_alerts',
        'ignore_error_recovery',
        'notify_on_rkn_domain_detection',
        'user_agent',
        'referrer',
        'login',
        'password',
        'save_response_time',
        'notify_on_dns_error_9',
        'notify_on_empty_response_6x',
        'valid_response_code',
        'ignored_error_codes',
        'alert_on_specific_codes',
        'follow_redirects',
        'response_number_range',
        'page_size_range',
        'set_as_template',
        'last_check_date',
        'token',
        'ignored_directories',
        'sample',
        'form_fields',
        'report_date_from',
        'report_date_to',
    ];

    protected $casts = [
        'control_domain' => 'boolean',
        'site_virus_check' => 'boolean',
        'notify_on_recovery' => 'boolean',
        'task_status_rss' => 'boolean',
        'dangerous_sites_detection' => 'boolean',
        'send_critical_error_alerts' => 'boolean',
        'ignore_error_recovery' => 'boolean',
        'notify_on_rkn_domain_detection' => 'boolean',
        'follow_redirects' => 'boolean',
        'set_as_template' => 'boolean',
        // 'last_check_date' => 'datetime',
    ];

    public function reportFrequencies()
    {
        return $this->belongsToMany(ReportFrequency::class);
    }

    public function verificationMethod()
    {
        return $this->hasOne(VerificationMethod::class,'id','verification_method_id');
    }

    public function reportContacts()
    {
        return $this->belongsToMany(Contact::class, 'task_report_contact');
    }

    public function errorNotificationContacts()
    {
        return $this->belongsToMany(Contact::class, 'task_error_notification_contact');
    }

    public function messages()
    {
        return $this->hasMany(TaskMessage::class);
    }

     public function links()
   {
        return $this->hasMany(TaskLink::class);
   }

}
