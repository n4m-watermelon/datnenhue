<?php

namespace App\Models;

use App\Services\Media\UploadsManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFolder extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'media_folders';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(MediaFile::class, 'folder_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentFolder()
    {
        return $this->hasOne(MediaFolder::class, 'id', 'parent');
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function (MediaFolder $folder) {
            if ($folder->isForceDeleting()) {
                $files = MediaFile::where('folder_id', '=', $folder->id)->onlyTrashed()->get();

                $uploadManager = new UploadsManager;

                foreach ($files as $file) {
                    /**
                     * @var MediaFile $file
                     */
                    $uploadManager->deleteFile($file->url);
                    $file->forceDelete();
                }
            } else {
                $files = MediaFile::where('folder_id', '=', $folder->id)->withTrashed()->get();

                foreach ($files as $file) {
                    /**
                     * @var MediaFile $file
                     */
                    $file->delete();
                }
            }
        });

        static::restoring(function ($folder) {
            MediaFile::where('folder_id', '=', $folder->id)->restore();
        });
    }
}
