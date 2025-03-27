<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class RunCommand extends Model
{
    //
    protected $table = 'run_commands';

    protected $fillable = [
        'command',
        'argument',
        'status'
    ];


}
