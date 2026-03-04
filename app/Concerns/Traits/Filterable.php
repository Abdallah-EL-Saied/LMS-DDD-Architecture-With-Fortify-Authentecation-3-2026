<?php

namespace App\Concerns\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Filterable
{
    /*
    |--------------------------------------------------------------------------
    | MAIN FILTER ENTRY POINT
    |--------------------------------------------------------------------------
    |
    | Applies search + filters to the query builder.
    |
    */

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        if (empty($filters)) {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Advanced mapped search (search + columns)
        |--------------------------------------------------------------------------
        */
        if (isset($filters['search'], $filters['columns'])) {
            $this->applyMappedSearch(
                $query,
                $filters['search'],
                $filters['columns']
            );

            unset($filters['search'], $filters['columns']);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Generic search fallback
        |--------------------------------------------------------------------------
        */
        if (isset($filters['search'])) {
            $this->applySearch(
                $query,
                $filters['search'],
                $this->searchable ?? []
            );

            unset($filters['search']);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Standard field filters
        |--------------------------------------------------------------------------
        */
        foreach ($filters as $field => $value) {
            $this->applyFilter($query, $field, $value);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERIC SEARCH
    |--------------------------------------------------------------------------
    |
    | Searches term across allowed searchable columns.
    |
    */

    protected function applySearch(Builder $query, string $term, array $columns): void
    {
        if (!$term || empty($columns)) {
            return;
        }

        // Allow only searchable columns
        $allowed = $this->searchable ?? [];
        $columns = array_map('trim', $columns);
        $columns = array_intersect($columns, $allowed);

        if (empty($columns)) {
            return;
        }

        $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $this->applyLike($q, $column, $term, 'or');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ADVANCED MAPPED SEARCH
    |--------------------------------------------------------------------------
    |
    | Supports:
    | - One value across multiple columns (OR)
    | - Value-column mapping (AND)
    |
    */

    protected function applyMappedSearch(Builder $query, string $search, string $columns): void
    {
        $values = array_map('trim', explode(',', $search));
        $cols = array_map('trim', explode(',', $columns));

        // Protect columns
        $allowed = $this->searchable ?? [];
        $cols = array_intersect($cols, $allowed);

        if (empty($cols)) {
            return;
        }

        $query->where(function ($q) use ($values, $cols) {

            // Single value → search in all columns (OR)
            if (count($values) === 1) {
                foreach ($cols as $col) {
                    $this->applyLike($q, $col, $values[0], 'or');
                }
                return;
            }

            // Equal count → map value to column (AND)
            if (count($values) === count($cols)) {
                foreach ($cols as $i => $col) {
                    $this->applyLike($q, $col, $values[$i], 'and');
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | STANDARD FILTER
    |--------------------------------------------------------------------------
    |
    | Applies filtering to allowed fields only.
    | Supports relation filtering using dot notation.
    |
    */

    protected function applyFilter(Builder $query, string $field, $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        // Extract base field for protection
        $baseField = Str::contains($field, '.')
            ? explode('.', $field)[0]
            : $field;

        if (!in_array($baseField, $this->filterable ?? [])) {
            return;
        }

        // Relation filtering
        if (Str::contains($field, '.')) {
            [$relation, $column] = explode('.', $field);

            $query->whereHas($relation, function ($q) use ($column, $value) {
                $this->applyOperator($q, $column, $value);
            });

            return;
        }

        $this->applyOperator($query, $field, $value);
    }

    /*
    |--------------------------------------------------------------------------
    | LIKE SEARCH (Relation Aware)
    |--------------------------------------------------------------------------
    */

    protected function applyLike(
        Builder $query,
        string $column,
        string $value,
        string $boolean = 'and'
    ): void {

        $method = $boolean === 'or' ? 'orWhere' : 'where';

        if (Str::contains($column, '.')) {
            [$relation, $field] = explode('.', $column);

            $relationMethod = $boolean === 'or'
                ? 'orWhereHas'
                : 'whereHas';

            $query->$relationMethod($relation, function ($q) use ($field, $value) {
                $q->where($field, 'LIKE', "%{$value}%");
            });

            return;
        }

        $query->$method($column, 'LIKE', "%{$value}%");
    }

    /*
    |--------------------------------------------------------------------------
    | OPERATOR HANDLER
    |--------------------------------------------------------------------------
    |
    | Supports:
    | - Exact match
    | - Between
    | - Custom operator
    | - whereIn
    | - NULL / NOT NULL
    | - JSON column filtering
    |
    */

    protected function applyOperator(Builder $query, string $column, $value): void
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        // BETWEEN
        if (is_array($value) && isset($value['from'], $value['to'])) {
            $query->whereBetween($column, [$value['from'], $value['to']]);
            return;
        }

        // WHERE IN
        if (is_array($value) && isset($value['in']) && is_array($value['in'])) {
            $query->whereIn($column, $value['in']);
            return;
        }

        // CUSTOM OPERATOR
        if (is_array($value) && isset($value['operator'], $value['value'])) {
            $query->where($column, $value['operator'], $value['value']);
            return;
        }

        // NULL SUPPORT
        if ($value === 'null') {
            $query->whereNull($column);
            return;
        }

        if ($value === 'not_null') {
            $query->whereNotNull($column);
            return;
        }

        // JSON COLUMN SUPPORT
        if (Str::contains($column, '->')) {
            $query->where($column, $value);
            return;
        }

        // Default exact match
        $query->where($column, $value);
    }

    /*
    |--------------------------------------------------------------------------
    | MULTI COLUMN SORT
    |--------------------------------------------------------------------------
    |
    | Example:
    | sort=name,-created_at
    |
    */

    public function scopeSort(Builder $query, ?string $sort = null): Builder
    {
        if (!$sort) {
            return $query;
        }

        $fields = array_map('trim', explode(',', $sort));

        foreach ($fields as $field) {

            $direction = str_starts_with($field, '-') ? 'desc' : 'asc';
            $column = ltrim($field, '-');

            if (in_array($column, $this->sortable ?? [])) {
                $query->orderBy($column, $direction);
            }
        }

        return $query;
    }
}