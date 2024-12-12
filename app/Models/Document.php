<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'libelle',
        'description',
        'chemin',
        'module_id',
        'downloads',
    ];

    // Relation avec les modules
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}

