<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Endereco extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;

    protected $fillable = [
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'complemento',
        'numero',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cep',
                'logradouro',
                'bairro',
                'cidade',
                'uf',
                'complemento',
                'numero',
            ]);
    }
}
