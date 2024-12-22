<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Module",
 *     type="object",
 *     title="Module",
 *     description="Représente un module de formation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="code", type="string", example="MATH101"),
 *     @OA\Property(property="nom", type="string", example="Mathématiques 1"),
 *     @OA\Property(property="description", type="string", example="Introduction aux mathématiques"),
 *     @OA\Property(property="annee", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description', 'annee'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
