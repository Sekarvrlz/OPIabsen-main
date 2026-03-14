<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorModel extends Model
{
    protected $table = 'operator';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['username','password'];
}