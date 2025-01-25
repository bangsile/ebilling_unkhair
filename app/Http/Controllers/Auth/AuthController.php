<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
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

		$token = env('API_TOKEN_SIMAK', 'default_token');

		// Function untuk hit ke API
		function get_data($url)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			if (!empty(curl_error($ch))) {
				$result = print_r(curl_error($ch) . ' - ' . $url);
			}
			curl_close($ch);
			return $result;
		}
		function str_curl($url, $data)
		{
			return $url . '?' . http_build_query($data);
		}

		// Cek user di DB
		$user = User::where('username', $request->username)->first();
		if ($user) {
			if (Hash::check($request->password, $user->password)) {
				Auth::login($user);
				Session::regenerate();
				return redirect()->intended(route('dashboard'));
			} else {
				return back()->with('error', 'Login gagal. Silahkan coba lagi.');
			}
		} else {

			// Login dengan API SIMAK
			$response_admin = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/apiv2/index.php/admin', ['token' => $token, 'username' => $request->username])), TRUE);
			$response_dosen = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/apiv2/index.php/dosen', ['token' => $token, 'nidn' => $request->username])), TRUE);

			// User Admin
			if ($response_admin && $response_admin['status'] == 200) {
				if (Hash::check($request->password, $response_admin['data']['admin']['password'])) {
					User::updateOrCreate(
						['username' => $response_admin['data']['admin']['username']],
						[
							// 'id_admin' => $response_admin['data']['admin']['id_admin'],
							// 'username' => $response_admin['data']['admin']['username'],
							'password' => $response_admin['data']['admin']['password'],
							'name' => $response_admin['data']['admin']['nama_lengkap'],
							'detail' => json_encode([
								'id_admin' => $response_admin['data']['admin']['id_admin'],
								'username' => $response_admin['data']['admin']['username'],
								'password' => $response_admin['data']['admin']['password'],
								'nama_lengkap' => $response_admin['data']['admin']['nama_lengkap'],
								'no_telp' => $response_admin['data']['admin']['no_telp'] ?? '',
								'email' => $response_admin['data']['admin']['email'] ?? '',
								'blokir' => $response_admin['data']['admin']['blokir'],
							]),
						]
					);
					$user_admin = User::where('username', $response_admin['data']['admin']['username'])->first();
					if(!$user_admin->hasRole('admin')) {
						$user_admin->assignRole('admin');
					}
					Auth::login($user_admin);
					Session::regenerate();
					return redirect()->route('dashboard');
				} else {
					return back()->with('error', 'Login gagal. Silahkan coba lagi.');
				}
			}
			// User Dosen
			elseif ($response_dosen['status'] == 200) {
				// if (Hash::check($request->password, $response_dosen['data']['dosen']['password'])) {
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
					if(!$user_dosen->hasRole('dosen')) {
						$user_dosen->assignRole('dosen');
					}
					Auth::login($user_dosen);
					Session::regenerate();
					return redirect()->route('dashboard');
				// }
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
