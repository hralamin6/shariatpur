<?php

namespace App\Livewire\App;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RoleComponent extends Component
{
    use LivewireAlert;
    #[Validate('required|unique:roles,name')]
    public $name;
    #[Validate([
        'permissions' => 'required|array',
        'permissions.*' => ['nullable', 'integer', 'exists:permissions,id'],
    ],attribute: [
        'permissions.*' => 'permissions',
    ])]
    public $permissions = [];
    public $checkModules=[];
    public $modules;
    public $role;
    public $allCheckBox=false;
    public $moduleCheckBoxes=[];
    public function mount()
    {
        // Fetch modules with permissions
        $this->modules = Module::with('permissions')->get();
    }
    public function getDataProperty()
    {
        return Role::paginate(10)->withQueryString();
    }
    public function render()
    {
        $this->authorize('app.roles.index');

        $items = $this->data;
        return view('livewire.app.role-component', compact('items'));
    }

    public function createRole()
    {
        $this->authorize('app.roles.create');

        $this->validate();
        Role::create(['name' => $this->name, 'slug'=> Str::slug($this->name)])->permissions()->sync($this->permissions);
        $this->reset('permissions', 'name', 'allCheckBox');
        $this->alert('success', __('Data saved successfully!'));
    }
    public function updateRole()
    {
        $this->authorize('app.roles.edit');

        $this->validate([
            'name' => ['required', 'min:2', 'max:44', Rule::unique('roles', 'name')->ignore($this->role['id'])]
        ]);
        $this->role->update(['name' => $this->name, 'slug'=> Str::slug($this->name)]);
            $this->role->permissions()->sync($this->permissions);
        $var = $this->role->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");

        $this->reset('permissions', 'name', 'role', 'allCheckBox');
        $this->alert('success', __('Data updated successfully!'));
    }

    public function updatedModuleCheckBoxes($value, $moduleId)
    {
//        dd('m:'.$moduleId.' v:'. $value);
            $permissionIds = $this->modules->find($moduleId)->permissions->pluck('id')->toArray();
            if ($value) {
                $this->permissions = array_unique(array_merge($this->permissions, $permissionIds));
            } else {
                $this->permissions = array_diff($this->permissions, $permissionIds);
            }
    }
    public function updatedAllCheckBox($value)
    {

            if ($value) {
                $this->permissions = Permission::pluck('id')->toArray();
            } else {
                $this->permissions = [];
            }

    }

    public function editRole(Role $role)
    {
//        dd($role->permissions);
        $this->role = $role;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('id')->toArray();
}
    public function deleteSingle(Role $user)
    {
        $this->authorize('app.roles.delete');
        $user->delete();
        $this->alert('success', __('Data deleted successfully'));
    }
}
