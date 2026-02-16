<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $fillable = ['filename', 'filepath', 'filetype', 'fileable_id', 'fileable_type'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
