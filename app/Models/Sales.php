<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'product_id',
        'company_id',
        'award_id',
        'amount',
        'date',
    ];

    // Relación con el modelo User (como agente)
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id');
    }
}
