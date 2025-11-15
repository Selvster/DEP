<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class Request extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'tracking_number',
        'name',
        'national_id',
        'phone',
        'email',
        'center_id',
        'request_type_id',
        'status_id',
        'description',
        'documents',
        'rejection_reason',
        'response_document',
        'created_by',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate tracking number on creation
        static::creating(function ($request) {
            if (empty($request->tracking_number)) {
                $request->tracking_number = self::generateTrackingNumber();
            }
        });
    }

    /**
     * Generate a unique tracking number.
     */
    public static function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'REQ-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    /**
     * Get the activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontSubmitEmptyLogs();
    }

    /**
     * Disable automatic activity logging for this model.
     */
    public function shouldLogActivity(string $eventName): bool
    {
        return false;
    }

    /**
     * Get the user who created this request.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the center for this request.
     */
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    /**
     * Get the request type.
     */
    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }

    /**
     * Get the status.
     */
    public function status()
    {
        return $this->belongsTo(RequestStatus::class, 'status_id');
    }
}

