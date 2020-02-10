<?php

namespace Sil\Scaffold;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class SilScaffoldUser extends Authenticatable {
    protected $table = 'scaffold_users';
    protected $primaryKey = 'id';
}