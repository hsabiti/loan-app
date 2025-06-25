<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'annual_interest_rate',
        'repayment_type',
        'start_date',
        'end_date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalRepayableAttribute()
    {
        return round($this->amount + ($this->amount * ($this->interest_rate / 100)), 2);
    }

    public function getMonthlyPaymentAttribute()
    {
        $months = Carbon::parse($this->start_date)->diffInMonths($this->end_date);
        return $months > 0
            ? round($this->getTotalRepayableAttribute() / $months, 2)
            : 0;
    }


    public function events()
    {
        return $this->hasMany(LoanEvent::class);
    }

}
