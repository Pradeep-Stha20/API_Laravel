<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "post"; // this line troubleshoot error while uploading image
    protected $fillable = [
        "title","description", "image"
    ];
}
