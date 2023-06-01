<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    protected $table = 'posts';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
