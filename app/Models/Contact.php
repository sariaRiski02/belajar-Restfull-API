<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function address()
    {
        return $this->hasMany(Address::class, "contact_id", "id");
    }
}
