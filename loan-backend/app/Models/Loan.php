<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'term',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejected_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalRepayableAttribute()
    {
        return round($this->amount + ($this->amount * ($this->interest_rate / 100)), 2);
    }

    public function getMontlyPayableAttribute()
    {
        return round($this->total_repayable / $this->term, 2);
    }
}
