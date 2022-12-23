<?php

namespace Aslnbxrz\FileManager\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Filemanager\Helpers\FilemanagerHelper;

/**
 * @property string title
 * @property string path
 * @property string file
 * @property string slug
 * @property string folder
 * @property string ext
 */
class Files extends Model
{
    const DOCS_EXT = 'doc,docx,xls,xlsx,pdf,zip,rar,';
    const IMG_EXT = 'jpeg,jpg,svg,png,';
    const VIDEO_EXT = 'mp4,mpeg,avi,3gp,mov,';

    const MUSIC_EXT = 'mp3,ogg,';

    const AVAILABLE_EXTENSIONS = self::DOCS_EXT . self::IMG_EXT . self::VIDEO_EXT;

    protected $fillable = [
        "title",
        "description",
        "slug",
        "ext",
        "file",
        "folder",
        "domain",
        "user_id",
        "folder_id",
        "path",
        "size",
        "is_front"
    ];

    protected $hidden = ["path"];

    protected $appends = ["url", "thumbnails"];

    public function getIsImage()
    {
        return FileManagerHelper::getImagesExt();
    }

    public function getDist(): string
    {
        return $this->path . '/' . $this->file;
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function getUrlAttribute(): string
    {
        return $this->domain . $this->folder . $this->file;
    }

    public function getThumbnailsAttribute()
    {
        $thumbsImages = FileManagerHelper::getThumbsImage();
        foreach ($thumbsImages as &$thumbsImage) {
            $slug = $thumbsImage['slug'];
            $newFileDist = config('system.STATIC_URL') . $this->folder . $this->slug . "_" . $slug . "." . $this->ext;
            $thumbsImage['src'] = $newFileDist;
            $thumbsImage['path'] = $this->path . $this->slug . "_" . $slug . "." . $this->ext;
        }
        return $thumbsImages;
    }


}
