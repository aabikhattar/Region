<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainPerson extends Model
{
    use HasFactory;

    protected $table = 'main_persons';

    protected $fillable = [
        'username',
        'password',
        'database_connection'
    ];

    protected $hidden = [
        'password',
        'database_connection',
        'created_at',
        'updated_at'
    ];


    protected static function boot()
    {
        parent::boot();

        //-- retreved function
        static::retrieved(function ($model) {

            config(['database.default' => $model->database_connection]);

            $person = Person::where('username', $model->username)->first();
            $model->full_name = $person->full_name;
        });
    }
}
