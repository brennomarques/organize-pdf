<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    public const PRIORITY_NORMAL = 0;
    public const PRIORITY_URGENT = 1;
    public const PRIORITY_VERY_URGENT = 2;

    public const ACTION_SIGN = 0;
    public const ACTION_APPROVE = 1;
    public const ACTION_VIEW = 2;

    use HasFactory;
}
