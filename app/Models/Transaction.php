<?php

namespace App\Models;

use App\Models\Propriete;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'propriete_id', 'montant', 'typeTransaction', 'dateTransaction', 'statutTransaction'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function propriete()
    {
        return $this->belongsTo(Propriete::class);
    }
}
