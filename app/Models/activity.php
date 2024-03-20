<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activity extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function task(){
        return $this->hasMany(Task::class);
    }
}
