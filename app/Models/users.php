<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'usu_login',
        'usu_senha',
        'usu_status',
        'usu_nivel',
        'foto',
        'usu_data_cad',
        'usu_data_update',
        'senha', // Assuming this is a separate field for password reset or something similar
    ];

    protected $hidden = [
        'usu_senha', // Hide the password by default
    ];
}
