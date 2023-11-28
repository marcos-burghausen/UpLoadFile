<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mongodb\Laravel\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'logs';

    protected $fillable = [
        'email',
        'timestamp',
        'user_agent',
        'ip',
        'action',
        'parameters',
    ];
}
