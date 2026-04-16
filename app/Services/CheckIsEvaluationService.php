<?php


namespace App\Services;
use Illuminate\Database\Eloquent\Builder;


class CheckIsEvaluationService
{

    protected array $evaluationWords = [
        'assesment',
        'evaluation',
        'exam',
        'e',
        'sment',
        'evalua',
    ];
    protected array $evaluationPrefixes = ['ce', 'e'];
    protected array $evaluationNotStartsWith = ['c'];

    public function __construct()
    {
    }

    public function checkEvaluation(String $name): Bool
    {
        $name = strtolower($name);
        $matches = false;
        foreach ($this->evaluationWords as $word) {
            if (str_contains($name, $word)) {
                $matches = true;
                break;
            }
        }
        foreach ($this->evaluationNotStartsWith as $prefix) {
            if (!str_starts_with($name, $prefix)) {
                $matches = true;
                break;
            }
        }
        foreach ($this->evaluationPrefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                $matches = true;
                break;
            }
        }
        return $matches;
    }

    public function filterEvaluationQuery(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where(function ($q) {
                foreach ($this->evaluationWords as $word) {
                    $q->orWhere('nombre', 'like', "%$word%");
                }
            })
            ->orWhere(function ($q) {
                foreach ($this->evaluationPrefixes as $prefix) {
                    $q->orWhere('nombre', 'like', "$prefix%");
                }
            })
            ->orWhere(function ($q) {
                foreach ($this->evaluationNotStartsWith as $prefix) {
                    $q->orWhere('nombre', 'not like', "$prefix%");
                }
            });
        });
    }
}
