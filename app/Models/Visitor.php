<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Visitor extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'organization', 'photo'
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }

        // If it's already a full URL (from external sources), return as is
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        // Otherwise, generate storage URL
        return Storage::url($this->photo);
    }

    public function hasPhoto()
    {
        return !empty($this->photo);
    }

    public function deletePhoto()
    {
        if ($this->photo && !filter_var($this->photo, FILTER_VALIDATE_URL)) {
            Storage::delete($this->photo);
        }
        $this->update(['photo' => null]);
    }
}
