<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\UserState;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $role_id = '';
    public $user_state_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'role_id' => ['except' => ''],
        'user_state_id' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['userUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleId()
    {
        $this->resetPage();
    }

    public function updatedUserStateId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'role_id', 'user_state_id']);
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with(['role', 'userState']);

        if (!empty($this->role_id)) {
            $query->where('role_id', $this->role_id);
        }

        if (!empty($this->user_state_id)) {
            $query->where('user_state_id', $this->user_state_id);
        }

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        $roles = Role::all();
        $states = UserState::all();

        return view('livewire.admin.users-table', compact('users', 'roles', 'states'));
    }
}
