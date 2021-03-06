<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;
#use App\Model\TypeVar;

class Variable extends Model
{
    use OrderableModel;
	protected $table = 'vars';
    protected $fillable = ['title', 'label'];
    public $timestamps = false;

    // public function types()
    // {
    //     return $this->belongsToMany(Type::class, 'type_var', 'var_id', 'type_id')->withPivot('price', 'quantity');
    // }

    // public function types()
    // {
	   //  return $this->belongsToMany(Type::class, 'type_var', 'var_id', 'type_id')->withPivot('price', 'quantity');
    // }
}
