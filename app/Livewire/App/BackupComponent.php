<?php

namespace App\Livewire\App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use function Laravel\Prompts\alert;

class BackupComponent extends Component
{
    use LivewireAlert;
    public $output;

    public function bytesToHumanReadable($bytes, $decimalPlaces = 2) {
        if ($bytes < 0) {
            return "Invalid byte value";
        }

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimalPlaces}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
    }

    public function diskDelete($file_name)
    {
        $this->authorize('app.backups.delete');

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name'). '/'.$file_name)) {
            $disk->delete(config('backup.backup.name'). '/'.$file_name);
            $this->alert('success', __('Data deleted!'));
        }else{
            $this->alert('error', __('Data not found!'));

        }

    }

    public function backupCreate()
    {
        $this->authorize('app.backups.create');

        Artisan::call('backup:run');
       $this->output = Artisan::output();
        $this->alert('success', __('Backup created successfully!'));

    }
    public function backupClean()
    {
        $this->authorize('app.backups.delete');

        Artisan::call('backup:clean');
       $this->output = Artisan::output();
        $this->alert('success', __('Backup cleaned successfully!'));

    }

    public function backupDownload($file_name)
    {
        $this->authorize('app.backups.download');

        $file = config('backup.backup.name'). '/'.$file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            return response()->stream(
                function () use ($file) {
                    echo Storage::get($file);
                },
                200,
                [
                    'Content-Type' => Storage::mimeType($file),
                    'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
                ]
            );
        }
    }
    public function render()
    {
        $this->authorize('app.backups.index');

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];
        foreach ($files as $file){
            if (substr($file, -4)==='.zip' && $disk->exists($file)){
                $file_name = str_replace(config('backup.backup.name'). '/', '', $file);
                $backups[] = [
                    'file_path' => $file,
                    'file_name' => $file_name,
                    'file_size' => $this->bytesToHumanReadable($disk->size($file)),
                    'created_at' => Carbon::parse($disk->lastModified($file))->diffForHumans(),
                    'download_link' => ''
                ];
            }
            $backups = array_reverse($backups);
        }
        return view('livewire.app.backup-component', compact('backups'));
    }

}
