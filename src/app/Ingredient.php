<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['supplier_code', 'name', 'measure'];

    /**
     * Define relationship between supplier and ingredients
     *
     * @return \Illuminate\Http\Response
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
