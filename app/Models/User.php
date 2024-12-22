<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Représente un utilisateur de l'application",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique de l'utilisateur"),
 *     @OA\Property(property="name", type="string", example="John Doe", description="Nom complet de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Adresse email de l'utilisateur"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2023-01-01T12:34:56Z", description="Date de vérification de l'email"),
 *     @OA\Property(property="password", type="string", writeOnly=true, example="hashed_password", description="Mot de passe de l'utilisateur"),
 *     @OA\Property(property="remember_token", type="string", nullable=true, example="random_token_string", description="Jeton pour la persistance de session"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:34:56Z", description="Date de création de l'utilisateur"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:34:56Z", description="Date de mise à jour de l'utilisateur")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

}
