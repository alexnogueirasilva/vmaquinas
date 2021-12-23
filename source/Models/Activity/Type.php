<?php

namespace Source\Models\Activity;

use Source\Core\Model;

class Type extends Model
{
    public function __construct()
    {
        parent::__construct("tipos", ["id"], ["nome"]);
    }

}
