<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // cast
    protected $casts = [
        // date_event
        'date_event' => 'date',
    ];
}
