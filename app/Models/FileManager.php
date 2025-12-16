<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileManager extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_type',
        'storage_type',
        'original_name',
        'file_name',
        'user_id',
        'path',
        'extension',
        'size',
        'external_link',
    ];

    public function upload($to, $file, $name = NULL, $id = NULL)
    {

        try {
            $disk = config('filesystems.default');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            if ($name == '') {
                $file_name = rand(000,999).time() . '.' . $extension;
            } else {
                $file_name = $name . '-' . time() . '.' . $extension;
            }
            $file_name = str_replace(' ', '_', $file_name);

            $options = ($disk === 'public') ? ['visibility' => 'public'] : [];
            $storedPath = 'uploads/' . $to . '/' . $file_name;
            $stored = Storage::disk($disk)->put($storedPath, file_get_contents($file->getRealPath()), $options);
            if ($stored !== true) {
                throw new \RuntimeException("Failed to store uploaded file to disk: {$disk}");
            }

            if($disk == 'public'){
                $target = storage_path('app/public');
                $link = public_path('storage');

                if (!file_exists($link)) {
                    $linked = false;
                    if (function_exists('symlink')) {
                        try {
                            $linked = @symlink($target, $link);
                        } catch (Throwable $e) {
                            $linked = false;
                        }
                    }

                    if (!$linked) {
                        $this->copyFolder($target, $link);
                    }
                }
            }


            $fileManager = (is_null($id)) ? new self() : self::find($id);
            $fileManager = is_null($fileManager) ?  new self() : $fileManager;
            $fileManager->file_type = $file->getMimeType();
            $fileManager->storage_type = $disk;
            $fileManager->original_name = $originalName;
            $fileManager->file_name = $file_name;
            $fileManager->user_id = auth()->id();
            $fileManager->path = $storedPath;
            $fileManager->extension = $extension;
            $fileManager->size = $size;
            $fileManager->save();
           return $fileManager;

        } catch (Throwable $e) {
            Log::error('File upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return NULL;
        }
    }

    protected function copyFolder($src, $dst)
    {
        if (!is_dir($src)) {
            return;
        }

        if (!is_dir($dst)) {
            @mkdir($dst, 0755, true);
        }

        $items = scandir($src);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $srcPath = $src . DIRECTORY_SEPARATOR . $item;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $item;

            if (is_dir($srcPath)) {
                $this->copyFolder($srcPath, $dstPath);
            } else {
                @copy($srcPath, $dstPath);
            }
        }
    }


    public function removeFile()
    {
        $disk = $this->storage_type ?: config('filesystems.default');
        if (Storage::disk($disk)->exists($this->path)) {
            Storage::disk($disk)->delete($this->path);
            return 100;
        }
        return 200;
    }
}
