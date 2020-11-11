<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
