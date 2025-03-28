<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type', 'description', 'user_id']; // Ajout de 'user_id'

    protected static $cache_key = 'app_settings';
    protected static $cache_duration = 1440; // 24 heures

    public static function get($key, $default = null)
    {
        $settings = static::getAllCached();
        return $settings->get($key, $default);
    }

    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Mise à jour du cache
        Cache::forget(static::$cache_key);

        return $setting;
    }

    public static function getAllCached()
    {
        return Cache::remember(static::$cache_key, static::$cache_duration, function () {
            return static::all()->pluck('value', 'key');
        });
    }

    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        if ($this->type === 'array' && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
