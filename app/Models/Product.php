<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_name', 'price', 'stock', 'img_path', 'comment', 'company_id'];
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
