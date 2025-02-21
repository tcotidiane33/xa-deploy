<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Avihs\PostReply\Traits\HasPost;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use App\Notifications\ClientUserAssignmentNotification;

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'fonction_id',
        'domaine_id',
        'habilitation_id',
        'is_active'
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
        'is_active' => 'boolean',
    ];
        // Assurez-vous que le nom de la connexion est correct dans le fichier de config pour les rôles/permissions
        protected $guard_name = 'web';


    /**
     * Get the clients & gestionnaire that owns the Echeancier.
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin'); // ou toute autre logique pour déterminer si l'utilisateur est admin
    }


    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
    public function clientsAsGestionnaire()
    {
        return $this->hasMany(Client::class, 'gestionnaire_principal_id');
    }
    // public function clientsAsGestionnaire()
    // {
    //     return $this->hasMany(Client::class, 'gestionnaire_id');
    // }

    public function clientsAsResponsable()
    {
        return $this->hasMany(Client::class, 'responsable_paie_id');
    }

    public function clientsAsBinome()
    {
        return $this->hasMany(Client::class, 'binome_id');
    }

    public function clientsResponsable()
    {
        return $this->hasMany(Client::class, 'responsable_paie_id');
    }

    public function clientsGestionnairePrincipal()
    {
        return $this->hasMany(Client::class, 'gestionnaire_principal_id');
    }


    public function clientsPrincipaux()
    {
        return $this->clientsGeres()->wherePivot('is_principal', true);
    }

    public function clientsSecondaires()
    {
        return $this->clientsGeres()->wherePivot('is_principal', false);
    }

    public function traitementsPaie()
    {
        return $this->hasMany(TraitementPaie::class, 'gestionnaire_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'createur_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    // public function clients()
    // {
    //     return $this->clientsAsGestionnaire->merge($this->clientsAsResponsable)->merge($this->clientsAsBinome);
    // }
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_user', 'user_id', 'client_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function clientsAsManager()
    {
        return $this->belongsToMany(Client::class, 'gestionnaire_client', 'gestionnaire_id', 'client_id')
            ->withPivot('is_principal')
            ->withTimestamps();
    }

    public function assignClientsEnMasse(array $clientIds, $isPrincipal = false)
    {
        DB::transaction(function () use ($clientIds, $isPrincipal) {
            foreach ($clientIds as $clientId) {
                $this->clients()->attach($clientId, ['is_principal' => $isPrincipal]);

                if ($isPrincipal) {
                    Client::find($clientId)->binomes()
                        ->where('id', '<>', $this->id)
                        ->update(['is_principal' => false]);
                }
            }
        });
    }

    public function getAllClients()
    {
        return $this->clientsAsGestionnaire()
            ->union($this->clientsAsResponsable())
            ->union($this->clientsAsBinome())
            ->get();
    }

    

    public function notifyUserAssignment($user, $client, $role)
    {
        $notification = new ClientUserAssignmentNotification([
            'client_name' => $client->name,
            'role' => $role,
            'assigned_at' => now()
        ]);
        
        $user->notify($notification);
    }
}
