<?php

namespace Oxytoxin;

use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class FilamentCustomDateFilter extends DateFilter
{
    protected function clauses(): array
    {
        return [
            static::CLAUSE_EQUAL => "DATE EQUALS",
            static::CLAUSE_NOT_EQUAL => "DATE IS NOT",
            static::CLAUSE_ON_AFTER => "DATE ON OR AFTER",
            static::CLAUSE_ON_BEFORE => "DATE ON OR EQUALS",
            static::CLAUSE_GREATER_THAN => "DATE AFTER",
            static::CLAUSE_LESS_THAN => "DATE BEFORE",
            static::CLAUSE_BETWEEN => "DATE BETWEEN",
            static::CLAUSE_NOT_SET => "NO DATE",
        ];
    }
}
