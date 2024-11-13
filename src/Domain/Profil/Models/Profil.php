<?php

namespace Src\Domain\Profil\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{

    use HasFactory;

    protected $fillable = [
        'lastName',
        'firstName',
        'image',
        'status'
    ];
}
