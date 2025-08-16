<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
	public function index()
	{
		if (Auth::check()) {
			return redirect()->route('dashboard');
		}
		return view('pages.auth.login');
	}

	public function store(Request $request)
	{

		// Validasi input dari form
		$request->validate([
			'username' => 'required|string',
			'password' => 'required|string',
		],  [
			'username.required' => 'Username/NIDN wajib diisi.',
			'password.required' => 'Password wajib diisi.',
		]);

		// Cek user di DB
		$user = User::where('username', $request->username)->first();
		if ($user) {
			if (!$user->is_active) {
				return back()->with('error', 'Akun anda sedang dinonaktifkan!!');
			}

			if (!$user->user_simak) {
				if (!Hash::check($request->password, $user->password)) {
					return back()->with('error', 'Login gagal. Silahkan coba lagi.');
				}

				Auth::login($user);
				Session::regenerate();
				return redirect()->intended(route('dashboard'));
			}

			$token = get_token();
			if (!$token || $token['status'] != '200') {
				return back()->with('error', 'Terjadi kesalahan saat pembuatan token!');
			}
			$token = $token['data']['token'];

			$response = json_decode(get_data(str_curl(env('API_URL_SIMAK') . '/4pisim4k/index.php/admin', ['token' => $token, 'username' => $request->username])), TRUE);
			// dd($token, $response);
			if (!$response || $response['status'] != '200') {
				return back()->with('error', 'Identitas ' . $request->username . ' tidak di kenali!');
			}

			$get = $response['data']['admin'];
			if ($get['blokir'] == 'Y') {
				return back()->with('error', 'Akun anda sedang dinonaktifkan!!');
			}

			if (!password_verify($request->password, $get['password'])) {
				return back()->with('error', 'Login gagal. Silahkan coba lagi.');
			}

			Auth::login($user);
			Session::regenerate();
			return redirect()->intended(route('dashboard'));
		} else {

			$token = get_token();
			if (!$token || $token['status'] != '200') {
				return back()->with('error', 'Terjadi kesalahan saat pembuatan token!');
			}
			$token = $token['data']['token'];
			// Login dengan API SIMAK
			$response_dosen = json_decode(get_data(str_curl(env('API_URL_SIMAK') . '/apiv2/dosen', ['token' => $token, 'nidn' => $request->username])), TRUE);
			// User Admin
			if ($response_dosen['status'] == 200) {
				if (password_verify($request->password, $response_dosen['data']['dosen']['password'])) {
					User::updateOrCreate(
						['username' => $response_dosen['data']['dosen']['nidn']],
						[
							'password' => $response_dosen['data']['dosen']['password'],
							'name' => $response_dosen['data']['dosen']['nama_dosen'],
							'role' => "dosen",
							'detail' => json_encode([
								'id_dosen' => $response_dosen['data']['dosen']['id_dosen'],
								'nama_dosen' => $response_dosen['data']['dosen']['nama_dosen'],
								'gelar_depan' => $response_dosen['data']['dosen']['gelar_depan'] ?? '',
								'gelar_belakang' => $response_dosen['data']['dosen']['gelar_belakang'],
								'nip' => $response_dosen['data']['dosen']['nip'],
								'nidn' => $response_dosen['data']['dosen']['nidn'],
								'id_fakultas' => $response_dosen['data']['dosen']['id_fakultas'],
								'id_program_studi' => $response_dosen['data']['dosen']['id_prodi'],
								'nama_fakultas' => $response_dosen['data']['head']['nama_fakultas'],
								'nama_program_studi' => $response_dosen['data']['head']['nama_program_studi'],
								'email' => $response_dosen['data']['dosen']['email'],
								'jenis_kelamin' => $response_dosen['data']['dosen']['jenis_kelamin'],
								'status_aktif' => $response_dosen['data']['dosen']['nama_status_aktif'],
							]),
						]
					);
					$user_dosen = User::where('username', $response_dosen['data']['dosen']['nidn'])->first();
					if (!$user_dosen->hasRole('dosen')) {
						$user_dosen->assignRole('dosen');
					}
					Auth::login($user_dosen);
					Session::regenerate();
					return redirect()->route('dashboard');
				}
				// return back()->with('error', 'Login gagal. Silahkan coba lagi.');
			}
			return back()->with('error', 'Login gagal. Silahkan coba lagi.');
		}
	}

	public function destroy(Request $request)
	{
		// Hapus session untuk logout
		Session::forget('auth');
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}
}
