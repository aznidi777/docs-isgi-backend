<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Document",
 *     title="Document",
 *     description="Schéma pour un document",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nom", type="string", example="Introduction aux bases de données"),
 *     @OA\Property(property="type", type="string", example="pdf"),
 *     @OA\Property(property="url", type="string", example="http://example.com/document.pdf"),
 *     @OA\Property(property="module_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Document extends Model
{
    
    use HasFactory;


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

