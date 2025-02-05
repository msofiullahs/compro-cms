<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $appends = [
        'file_url',
        'file_size',
        'author_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uploaded_by');
    }

    protected function authorName() : Attribute
    {
        $author = $this->user->name;
        return new Attribute(
            get: fn () => $author,
        );
    }

    protected function fileUrl() : Attribute
    {
        $fileUrl = Storage::url('photos/'.$this->filename);
        return new Attribute(
            get: fn () => $fileUrl,
        );
    }

    protected function fileSize() : Attribute
    {
        $fileSize = null;
        if (Storage::disk('public')->exists('photos/'.$this->filename)) {
            $fileSize = $this->formatSizeUnits(Storage::disk('public')->size('photos/'.$this->filename));
        }

        return new Attribute(
            get: fn () => $fileSize,
        );
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
