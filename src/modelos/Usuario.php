<?php
 
use Illuminate\Database\Eloquent\Model;
 
class Usuario extends Model {
 
    // Nombre de la tabla en la base
    protected $table = 'usuarios';
 
    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'usuario',
        'email',
        'password',
        'token',
        'token_exp',
    ];
 
    // Ocultar password y token 
    protected $hidden = [
        'password',
        'token',
        'token_exp',
    ];
}
 
