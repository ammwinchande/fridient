<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['code', 'name'];

    /**
     * Define relationship between supplier and ingredients
     *
     * @return \Illuminate\Http\Response
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
