<?php

 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class SignUp extends Model{

    protected $table = 'DataSignUp';

    // column sa table
    protected $fillable = [
    'email', 'name' , 'password'
    ];

    public $timestamps = false;

}