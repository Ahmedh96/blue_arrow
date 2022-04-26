<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'content',
        'image',
        'user_id'
    ];

    protected $appends = ['image_path'];

    //attributes ---------------------------------------
    public function getImagePathAttribute()
    {
        return Storage::disk('local')->url('public/notes/' . $this->image);
    }// end of getImagePathAttribute


    //Relation Between User and Note
    public function user() {
        return $this->belongsTo(User::class);
    }

    protected $dates = ['deleted_at'];
}
