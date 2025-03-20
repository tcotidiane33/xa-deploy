<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Mail\ClientNotification;
use App\Mail\ClientManagerChangeMail;
use App\Mail\ClientAcknowledgementMail;
use Illuminate\Support\Facades\Mail;

class Client extends Model implements AuditableContract
{
    use Filterable, HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'responsable_paie_id',
        'gestionnaire_principal_id',
        'date_debut_prestation',
        'contact_paie',
        'contact_comptabilite',
        'nb_bulletins',
        'maj_fiche_para',
        'convention_collective_id',
        'status',
        'is_portfolio',
        'parent_client_id',
        'type_societe',
        'ville',
        'dirigeant_nom',
        'dirigeant_telephone',
        'dirigeant_email',
        'contact_paie_nom',
        'contact_paie_prenom',
        'contact_paie_telephone',
        'contact_paie_email',
        'contact_compta_nom',
        'contact_compta_prenom',
        'contact_compta_telephone',
        'contact_compta_email',
        'responsable_telephone_ld',
        'gestionnaire_telephone_ld',
        'binome_telephone_ld',
        'binome_id',
        'saisie_variables',
        'client_forme_saisie',
        'date_formation_saisie',
        'date_fin_prestation',
        'date_signature_contrat',
        'taux_at',
        'adhesion_mydrh',
        'date_adhesion_mydrh',
        'is_cabinet',
        'portfolio_cabinet_id',
        'date_estimative_envoi_variables',
        'date_rappel_mail',
        'reference'
    ];

    protected $dates = [
        'date_debut_prestation',
        'date_estimative_envoi_variables',
        'maj_fiche_para',
        'saisie_variables',
        'date_signature_contrat',
        'date_formation_saisie',
        'date_adhesion_mydrh',
        'date_rappel_mail'
    ];

    protected $casts = [
        'date_debut_prestation' => 'datetime',
        'date_fin_prestation' => 'datetime',
        'date_estimative_envoi_variables' => 'datetime',
        'maj_fiche_para' => 'datetime',
        'date_rappel_mail' => 'datetime',
        'date_adhesion_mydrh' => 'datetime',
        'date_formation_saisie' => 'datetime',
        'date_signature_contrat' => 'datetime',
        'is_portfolio' => 'boolean',
        'is_cabinet' => 'boolean',
        'client_forme_saisie' => 'boolean',
        'adhesion_mydrh' => 'boolean',
        'gestionnaires_secondaires' => 'array',
    ];

    //email.JS
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($traitementPaie) {
            $traitementPaie->reference = 'TP-' . Str::upper(Str::random(8));
        });

        static::creating(function ($client) {
            $client->reference = 'CL-' . Str::upper(Str::random(8));
        });


        static::created(function ($client) {
            $client->sendAcknowledgementEmail();
        });

        static::updated(function ($client) {
            if ($client->isDirty('gestionnaire_principal_id')) {
                $client->sendManagerChangeEmail();
            }
        });
    }

    public function sendAcknowledgementEmail()
    {
        $gestionnaire = $this->gestionnairePrincipal;
        $client = $this;

        // Mail::to($client->email)->cc($gestionnaire->email)->send(new ClientAcknowledgementMail($gestionnaire, $client));

        // $data = [
        //     'managerName' => $gestionnaire->name,
        //     'managerEmail' => $gestionnaire->email,
        //     'clientName' => $client->name,
        //     'clientEmail' => $client->email,
        // ];

        // Mail::to($data['managerEmail'])->send(new ClientNotification($data));
    }

    public function sendManagerChangeEmail()
    {
        $gestionnaire = $this->gestionnairePrincipal;
        $client = $this;

        Mail::to($client->email)->cc($gestionnaire->email)->send(new ClientManagerChangeMail($gestionnaire, $client));


        // $data = [
        //     'managerName' => $gestionnaire->name,
        //     'managerEmail' => $gestionnaire->email,
        //     'managerPhone' => $gestionnaire->phone ?? 'N/A', // Assurez-vous que cette clé est définie
        //     'clientName' => $client->name,
        //     'clientEmail' => $client->email,
        // ];

        // Mail::to($data['managerEmail'])->send(new ClientNotification($data));
    }
    // protected function sendEmail($serviceId, $templateId, $data)
    // {
    //     Mail::to($data['managerEmail'])->send(new ClientNotification($data));
    // }


    public function scopeFilterBySearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($traitementPaie) {
    //         $traitementPaie->reference = 'TP-' . Str::upper(Str::random(8));
    //     });
    // }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'client_user', 'client_id', 'user_id');
    }

    public function periodePaie()
    {
        return $this->belongsTo(PeriodePaie::class);
    }

    // Relations

    public function gestionnairePrincipal()
    {
        return $this->belongsTo(User::class, 'gestionnaire_principal_id');
    }

    public function gestionnairesSecondaires()
    {
        return $this->belongsToMany(User::class, 'client_gestionnaire_secondaire', 'client_id', 'gestionnaire_id');
    }

    public function responsablePaie()
    {
        return $this->belongsTo(User::class, 'responsable_paie_id');
    }

    public function binome()
    {
        return $this->belongsTo(User::class, 'binome_id');
    }

    public function conventionCollective()
    {
        return $this->belongsTo(ConventionCollective::class);
    }

    public function subClients()
    {
        return $this->hasMany(Client::class, 'parent_client_id');
    }

    public function parentClient()
    {
        return $this->belongsTo(Client::class, 'parent_client_id');
    }

    public function portfolioCabinet()
    {
        return $this->belongsTo(Client::class, 'portfolio_cabinet_id');
    }

    public function traitementsPaie()
    {
        return $this->hasMany(TraitementPaie::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function histories()
    {
        return $this->hasMany(ClientHistory::class);
    }

    // Methods
    public function assignGestionnairePrincipal($userId)
    {
        $this->gestionnaire_principal_id = $userId;
        $this->save();
    }

    public function assignResponsablePaie($userId)
    {
        $this->responsable_paie_id = $userId;
        $this->save();
    }

    public function assignBinome($userId)
    {
        $this->binome_id = $userId;
        $this->save();
    }

    public function transferGestionnaire($oldGestionnaireId, $newGestionnaireId, $isPrincipal = false)
    {
        DB::transaction(function () use ($oldGestionnaireId, $newGestionnaireId, $isPrincipal) {
            $gestionnairesSecondaires = $this->gestionnaires_secondaires ?? [];
            if (($key = array_search($oldGestionnaireId, $gestionnairesSecondaires)) !== false) {
                unset($gestionnairesSecondaires[$key]);
            }
            $gestionnairesSecondaires[] = $newGestionnaireId;
            $this->gestionnaires_secondaires = $gestionnairesSecondaires;
            $this->save();

            if ($isPrincipal) {
                $this->update(['gestionnaire_principal_id' => $newGestionnaireId]);
            }
        });
    }

    protected function filterSearch($query, $value)
    {
        return $query->where('name', 'like', "%{$value}%")
            ->orWhere('contact_paie', 'like', "%{$value}%")
            ->orWhere('contact_comptabilite', 'like', "%{$value}%");
    }

    protected function filterStatus($query, $value)
    {
        return $query->where('status', $value);
    }

    public function saveMajFicheParaHistory()
    {
        if ($this->isDirty('maj_fiche_para')) {
            $this->histories()->create([
                'maj_fiche_para' => $this->maj_fiche_para,
            ]);
        }
    }

    // Méthodes pour distinguer les clients cabinets et les clients portefeuilles cabinets
    public function isCabinet()
    {
        return $this->is_cabinet;
    }

    public function isPortfolioCabinet()
    {
        return !$this->is_cabinet && $this->portfolio_cabinet_id !== null;
    }

    public function progressPercentage()
    {
        // Implémentez la logique pour calculer le pourcentage de progression
        $totalSteps = 5; // Nombre total d'étapes
        $completedSteps = 0;

        if ($this->reception_variables)
            $completedSteps++;
        if ($this->preparation_bp)
            $completedSteps++;
        if ($this->validation_bp_client)
            $completedSteps++;
        if ($this->preparation_envoie_dsn)
            $completedSteps++;
        if ($this->accuses_dsn)
            $completedSteps++;

        return ($completedSteps / $totalSteps) * 100;
    }

    /**
     * Récupère la fiche client la plus récente
     */
    public function currentFicheClient()
    {
        return $this->hasOne(FicheClient::class)->latest();
    }

    /**
     * Récupère toutes les fiches clients
     */
    public function fichesClients()
    {
        return $this->hasMany(FicheClient::class);
    }

    public function attachUser($userId, $role)
    {
        // Attacher l'utilisateur avec le rôle dans la table pivot
        $this->users()->attach($userId, [
            'role' => $role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Mettre à jour la colonne correspondante selon le rôle
        switch ($role) {
            case 'gestionnaire':
                $this->gestionnaire_principal_id = $userId;
                break;
            case 'responsable':
                $this->responsable_paie_id = $userId;
                break;
            case 'binome':
                $this->binome_id = $userId;
                break;
        }

        $this->save();
    }


}
