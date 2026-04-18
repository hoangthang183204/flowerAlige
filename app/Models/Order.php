<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'payment_method',
        'status',
        'total_amount',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return allowed next statuses for each current status.
     *
     * @return array<string, string[]>
     */
    public function allowedStatusTransitions(): array
    {
        return [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];
    }

    /**
     * Check if the order can transition from current status to given status.
     */
    public function canTransitionTo(string $to): bool
    {
        $from = $this->status;

        if ($from === $to) {
            return true;
        }

        $map = $this->allowedStatusTransitions();

        return in_array($to, $map[$from] ?? [], true);
    }
}
