<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'email',
        'company_id',
        'product_id',
        'user_id',
        'award_id'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relación con el modelo User usando el campo 'user_id'
    }    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }
}
