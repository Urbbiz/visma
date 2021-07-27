<?php

namespace Syllable\Database;

class DatabaseQueryBuilder
{
    private array $fields = [];
    private string $from = '';
    private array $where = [];

    public function select(array $fields = ['*']): DatabaseQueryBuilder // jeigu nepaduodu jokio fields, imes automatiskai zvaigzdute
    {
        $this->fields = $fields;
        return $this;
    }
    public function where(string $where): DatabaseQueryBuilder
    {
        $this->where[] = $where;
        return $this;
    }

    public function from(string $from): DatabaseQueryBuilder
    {
        $this->from = $from;
        return $this;
    }


    public function __toString(): string
    {
        $selectString = sprintf(
            'SELECT %s FROM %s',
            join(', ', $this->fields),
            $this->from
        );

        if (!empty($this->where)) {
            $whereString = sprintf(
                ' WHERE %s',
                join(' AND', $this->where)
            );
            $selectString .= $whereString;
        }

        return $selectString;
    }
}
