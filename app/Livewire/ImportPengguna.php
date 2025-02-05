<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

use function PHPSTORM_META\type;

class ImportPengguna extends Component
{
    public $judul;
    public $currentStep = 1;

    public $id;
    public $name;
    public $username;
    public $password;
    public $user_simak;
    public $active = 0;
    public $detail;
    public $role_pengguna;

    public $roles = [];

    public function mount($judul)
    {
        $this->judul = $judul;
    }

    public function render()
    {
        return view('livewire.import-pengguna');
    }

    public function check()
    {
        $this->validate([
            'username' => 'required|unique:users,username'
        ]);

        $error = 0;
        $token = get_token();
        if (!$token || $token['status'] != '200') {
            $error++;
            $this->dispatch('alert', type: 'error', message: 'Terjadi kesalahan saat pembuatan token!');
        }

        $response = json_decode(get_data(str_curl(env('API_URL_SIMAK') . '/4pisim4k/index.php/admin', ['token' => $token['data']['token'], 'username' => $this->username])), TRUE);

        if (!$response || $response['status'] != '200') {
            $error++;
            $this->dispatch('alert', type: 'error', message: $response['message']);
        }

        if (!$error) {
            $get = $response['data']['admin'];
            $this->username = $get['username'];
            $this->name = $get['nama_lengkap'];
            $this->password = $get['password'];
            $this->active = ($get['blokir'] == 'N') ? 1 : 0;
            $this->roles = Role::all();
            $this->currentStep = 2;
            $this->user_simak = 1;
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function save()
    {
        $this->validate([
            'role_pengguna' => 'required',
            'name' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => $this->password,
            'user_simak' => $this->user_simak,
            'is_active' => $this->active,
        ]);

        // dd($user);

        $user->assignRole($this->role_pengguna);

        session()->put('success', 'Pengguna berhasil ditambahkan');
        return $this->redirect(route('pengguna.index'));
    }
}
