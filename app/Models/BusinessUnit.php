<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $fillable = ['uuid', 'name', 'document', 'zipCode', 'address', 'city', 'state', 'country', 'status'];

}
