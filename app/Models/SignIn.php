<?php

 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class SignIn extends Model{

    protected $table = 'DataSignIn';

    // column sa table
    protected $fillable = [
    'email', 'password',
    ];

    public $timestamps = false;

}