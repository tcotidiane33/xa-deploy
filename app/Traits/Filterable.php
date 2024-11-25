<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $field => $value) {
            $method = 'filter' . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($query, $value);
            } elseif (!is_null($value)) {
                $query->where($field, $value);
            }
        }
        return $query;
    }
}
