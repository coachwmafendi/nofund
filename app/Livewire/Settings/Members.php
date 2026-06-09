<?php

namespace App\Livewire\Settings;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Members extends Component
{
    public string $inviteEmail = '';
    public UserRole $inviteRole = UserRole::VIEWER;

    public array $members = [];

    public function mount(): void
    {
        $this->loadMembers();
    }

    public function loadMembers(): void
    {
        $this->members = Auth::user()->organization->users()
            ->with('organization')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'is_current' => $user->id === Auth::id(),
                ];
            })
            ->toArray();
    }

    public function sendInvite(): void
    {
        $this->validate([
            'inviteEmail' => 'required|email',
            'inviteRole' => 'required',
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $this->inviteEmail)
            ->where('organization_id', Auth::user()->organization_id)
            ->first();

        if ($existingUser) {
            $this->addError('inviteEmail', 'This email is already a member of this organization.');
            return;
        }

        // Create invited user
        User::create([
            'name' => explode('@', $this->inviteEmail)[0],
            'email' => $this->inviteEmail,
            'organization_id' => Auth::user()->organization_id,
            'role' => $this->inviteRole,
            'status' => UserStatus::INVITED,
            'password' => bcrypt(bin2hex(random_bytes(16))),
        ]);

        $this->inviteEmail = '';
        $this->inviteRole = UserRole::VIEWER;
        $this->loadMembers();

        $this->dispatch('toast', message: 'Invitation sent successfully.', type: 'success');
    }

    public function removeMember(int $userId): void
    {
        $currentUser = Auth::user();

        // Cannot remove yourself
        if ($userId === $currentUser->id) {
            $this->addError('remove', 'You cannot remove yourself from the organization.');
            return;
        }

        // Only admins can remove members
        if ($currentUser->role !== UserRole::ADMIN && $currentUser->role !== UserRole::SUPER_ADMIN) {
            $this->addError('remove', 'Only administrators can remove members.');
            return;
        }

        $user = User::find($userId);
        if ($user) {
            $user->update(['status' => UserStatus::DEACTIVATED]);
            $this->loadMembers();
            $this->dispatch('toast', message: 'Member removed successfully.', type: 'success');
        }
    }

    public function changeRole(int $userId, string $role): void
    {
        $currentUser = Auth::user();

        // Cannot change your own role
        if ($userId === $currentUser->id) {
            $this->addError('role', 'You cannot change your own role.');
            return;
        }

        // Only admins can change roles
        if ($currentUser->role !== UserRole::ADMIN && $currentUser->role !== UserRole::SUPER_ADMIN) {
            $this->addError('role', 'Only administrators can change member roles.');
            return;
        }

        $user = User::find($userId);
        if ($user) {
            $user->update(['role' => UserRole::from($role)]);
            $this->loadMembers();
            $this->dispatch('toast', message: 'Role updated successfully.', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.settings.members');
    }
}