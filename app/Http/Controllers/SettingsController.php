<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $backups = [];
        if (auth()->user() && auth()->user()->email === 'admin@admin.com') {
            $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
            $disk = Storage::disk($diskName);
            $name = config('backup.backup.name');
            
            if ($disk->exists($name)) {
                $files = $disk->files($name);
                
                foreach ($files as $file) {
                    if (substr($file, -4) == '.zip') {
                        $backups[] = [
                            'file_path' => $file,
                            'file_name' => str_replace($name . '/', '', $file),
                            'file_size' => $disk->size($file),
                            'last_modified' => $disk->lastModified($file),
                        ];
                    }
                }
                
                usort($backups, function ($a, $b) {
                    return $b['last_modified'] <=> $a['last_modified'];
                });
            }
        }
        
        return view('settings.index', compact('backups'));
    }
}
