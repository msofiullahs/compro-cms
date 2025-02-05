<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    use SoftDeletes;

    protected $appends = [
        'banner_url',
        'author_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'author');
    }

    protected function authorName() : Attribute
    {
        $author = $this->user->name;
        return new Attribute(
            get: fn () => $author,
        );
    }

    protected function bannerUrl() : Attribute
    {
        $bannerUrl = null;
        if (!empty($this->banner)) {
            $banner = Media::find($this->banner);
            if (Storage::disk('public')->exists('photos/'.$banner->filename)) {
                $bannerUrl = Storage::url('photos/'.$banner->filename);
            }
        }
        return new Attribute(
            get: fn () => $bannerUrl,
        );
    }
}
