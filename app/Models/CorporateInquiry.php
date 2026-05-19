<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateInquiry extends Model
{
    protected $fillable = [
        'company_name', 'contact_name', 'email', 'phone', 'event_type',
        'expected_quantity', 'required_date', 'requirements', 'status', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'required_date' => 'date',
    ];
}
