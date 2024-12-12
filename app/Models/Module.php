<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description', 'annee'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
