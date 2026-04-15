<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function run()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        
        try {
            // Roda apenas o BD para ser rápido e economizar espaço, ou rode tudo se preferir.
            // Para um laboratório, o BD é o que mais importa no dia a dia.
            Artisan::call('backup:run', ['--only-db' => true]);
            return back()->with('status', 'Backup do banco de dados realizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao gerar backup: ' . $e->getMessage()]);
        }
    }

    public function download($fileName)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $name = config('backup.backup.name');
        
        $path = $name . '/' . $fileName;

        if ($disk->exists($path)) {
            return $disk->download($path);
        }

        return back()->withErrors(['error' => 'Arquivo de backup não encontrado.']);
    }
    
    public function destroy($fileName)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $name = config('backup.backup.name');
        
        $path = $name . '/' . $fileName;

        if ($disk->exists($path)) {
            $disk->delete($path);
            return back()->with('status', 'Backup removido com sucesso!');
        }

        return back()->withErrors(['error' => 'Arquivo não encontrado.']);
    }
}
