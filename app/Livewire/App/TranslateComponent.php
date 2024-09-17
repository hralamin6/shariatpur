<?php

namespace App\Livewire\App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Translate;
use Livewire\WithPagination;

class TranslateComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    public $searchBy = 'key';
    public $search = '';
    public $itemPerPage=20;
    public $orderBy = 'id';
    public $orderDirection = 'asc';
//    public $translations;  // All translations
    public $activeLocale='bn';  // All translations
    public $locale, $group, $key, $value, $status;  // Fields for new translation
    public $editTranslationId = null;  // For editing a translation
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function importBlade()
    {
        $this->authorize('app.translates.edit');
        $translationKeys = $this->extractTranslationKeys();
        $existingKeys = Translate::where('locale', $this->activeLocale)
            ->pluck('key')
            ->toArray();

        // Filter out only new keys
        $newKeys = array_diff($translationKeys, $existingKeys);

        foreach ($newKeys as $key) {
            // Use a default value or an empty string if the translation is not found
            $value = __($key);
            Translate::updateOrCreate(
                [
                    'locale' => $this->activeLocale,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                    'group' => 'json',
                    'status' => 1,
                ]
            );
        }
        $this->alert('success', __('Translations imported successfully.'));

  }
    public function orderByDirection($field)
    {
        if ($this->orderBy == $field){

            $this->orderDirection==='asc'? $this->orderDirection='desc': $this->orderDirection='asc';
        }else{
            $this->orderBy = $field;
            $this->orderDirection==='asc';

        }
    }
    private function extractTranslationKeys()
    {
        $this->authorize('app.translates.edit');

        $keys = [];
        $files = File::allFiles(resource_path('views'));

        foreach ($files as $file) {
            $contents = File::get($file);

            // Regex to match @lang() or __() calls
            preg_match_all('/__\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $contents, $matches);
            if (!empty($matches[1])) {
                $keys = array_merge($keys, $matches[1]);
            }

            preg_match_all('/@lang\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $contents, $matches);
            if (!empty($matches[1])) {
                $keys = array_merge($keys, $matches[1]);
            }
        }
        $phpFiles = File::allFiles(app_path());
        foreach ($phpFiles as $file) {
            if ($file->getExtension() === 'php') {
                $contents = File::get($file);
                preg_match_all('/__\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $contents, $matches);
                if (!empty($matches[1])) {
                    $keys = array_merge($keys, $matches[1]);
                }
            }
        }
        // Scan JSON files in resources/lang

        return array_unique($keys); // Remove duplicate keys
    }


    public function importTranslations()
    {
        $this->authorize('app.translates.edit');

        // Define the path to the en.json file
        $jsonFilePath = resource_path("lang/{$this->activeLocale}.json");

        // Check if the file exists
        if (!File::exists($jsonFilePath)) {
            $this->alert('error', __('the file does not exist.'));
            return;
        }

        // Read the file contents
        $jsonContent = File::get($jsonFilePath);
        $translations = json_decode($jsonContent, true);

        if ($translations === null) {
            $this->alert('error', __('invalid json content.'));
            return;
        }

        // Import the translations into the database
        foreach ($translations as $key => $value) {
            Translate::updateOrCreate(
                [
                    'locale' => $this->activeLocale,    // Assuming 'en' locale
                    'group'  => 'json', // Since these are usually non-grouped translations
                    'key'    => $key,
                ],
                [
                    'value'  => $value,
                    'status' => 1,        // Assuming 'active' status
                ]
            );
        }

        $this->alert('error', __('translations imported successfully.'));
    }

    public function clearTranslations()
    {
        $this->authorize('app.translates.edit');

        Translate::where('locale', $this->activeLocale)->delete();
        $this->alert('error', __('translations deleted successfully.'));

    }
    public function exportTranslations()
    {
        $this->authorize('app.translates.edit');

        // Fetch all translations for the 'en' locale
        $translations = Translate::where('locale', $this->activeLocale)->get();

        // Prepare the translations array in the format needed for JSON
        $translationArray = [];
        foreach ($translations as $translation) {
            $translationArray[$translation->key] = $translation->value;
        }
//        dd($translationArray);

        // Define the path to the en.json file
        $jsonFilePath = resource_path("lang/{$this->activeLocale}.json");


        // Store the translations in JSON format (overwriting en.json)
        File::put($jsonFilePath, json_encode($translationArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // Provide feedback to the user
        $this->alert('error', __('translations exported successfully.'));
    }

    public function getDataProperty()
    {
// Fetch all translations
        return Translate::where($this->searchBy, 'like', '%'.$this->search.'%')->when($this->activeLocale, function ($query) {
            return $query->where('locale', $this->activeLocale);
        })->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->itemPerPage)->withQueryString();

    }

    public function createTranslation()
    {
        $this->authorize('app.translates.create');
        $this->validate([
            'locale' => 'required|string',
            'group' => 'required|string',
            'key' => 'required|string',
            'value' => 'nullable|string',
            'status' => 'integer|between:0,1',
        ]);

// Create a new translation
        Translate::create([
            'locale' => $this->locale,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $this->value,
            'status' => $this->status ?? 0,
        ]);

// Reset form fields and refresh translations
        $this->resetFields();
        $this->translations = Translate::all();
    }

    public function editTranslation($id)
    {
        $this->authorize('app.translates.edit');
        $translation = Translate::findOrFail($id);
        $this->editTranslationId = $id;
        $this->locale = $translation->locale;
        $this->group = $translation->group;
        $this->key = $translation->key;
        $this->value = $translation->value;
        $this->status = $translation->status;
    }

    public function updateTranslation()
    {
        $this->authorize('app.translates.edit');
        $this->validate([
            'locale' => 'required|string',
            'group' => 'required|string',
            'key' => 'required|string',
            'value' => 'nullable|string',
            'status' => 'integer|between:0,1',
        ]);

// Update translation
        $translation = Translate::findOrFail($this->editTranslationId);
        $translation->update([
            'locale' => $this->locale,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $this->value,
            'status' => $this->status,
        ]);

// Reset form fields and refresh translations
        $this->resetFields();
        $this->translations = Translate::all();
    }

    public function deleteTranslation($id)
    {
        $this->authorize('app.translates.delete');
        Translate::destroy($id);
        $this->translations = Translate::all();  // Refresh the list
    }

    private function resetFields()
    {
// Reset all fields to their default state
        $this->locale = '';
        $this->group = '';
        $this->key = '';
        $this->value = '';
        $this->status = 0;
        $this->editTranslationId = null;
    }

    public function render()
    {
        $this->authorize('app.translates.index');

        $translations = $this->data;
        return view('livewire.app.translate-component', compact('translations'));
    }
}
