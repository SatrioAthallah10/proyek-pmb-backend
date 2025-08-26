<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
// --- [PENAMBAHAN] Mengimpor model MenuPermission ---
use App\Models\MenuPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Throwable;

class AdminController extends Controller
{
    // --- [PENAMBAHAN FITUR BARU] ---

    /**
     * Mengambil semua pengaturan visibilitas menu untuk semua peran.
     * Hanya bisa diakses oleh 'kepala_bagian'.
     */
    public function getMenuPermissions()
    {
        // Mengambil semua permission dan mengelompokkannya berdasarkan peran
        $permissions = MenuPermission::all()->groupBy('role')->map(function ($items) {
            return $items->keyBy('menu_key');
        });

        return response()->json($permissions);
    }

    /**
     * Memperbarui pengaturan visibilitas menu.
     * Hanya bisa diakses oleh 'kepala_bagian'.
     */
    public function updateMenuPermissions(Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|array',
            'permissions.*.*' => 'required|boolean',
        ]);

        try {
            foreach ($request->permissions as $role => $menus) {
                foreach ($menus as $menu_key => $is_visible) {
                    MenuPermission::updateOrCreate(
                        ['role' => $role, 'menu_key' => $menu_key],
                        ['is_visible' => $is_visible]
                    );
                }
            }
            return response()->json(['message' => 'Pengaturan menu berhasil diperbarui.']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil menu yang diizinkan untuk admin yang sedang login.
     * Ini lebih efisien daripada mengambil semua dan memfilter di frontend.
     */
    public function getMyMenuPermissions()
    {
        $user = Auth::user();
        $role = $user->role;

        // Kepala Bagian dapat melihat semua menu
        if ($role === 'kepala_bagian') {
            return response()->json([
                'dashboard' => true,
                'konfirmasi-pembayaran' => true,
                'manajemen-pendaftar' => true,
                'mahasiswa-aktif' => true,
                'tambah-staff' => true,
                'pengaturan-menu' => true, // Menu baru
            ]);
        }

        // Untuk peran lain, ambil dari database
        $permissions = MenuPermission::where('role', $role)
            ->where('is_visible', true)
            ->pluck('menu_key')
            ->flip() // Mengubah value menjadi key untuk pencarian O(1) di frontend
            ->map(function () {
                return true; // value menjadi true
            });

        return response()->json($permissions);
    }

    // --- [AKHIR DARI FITUR BARU] ---


    public function registerStaff(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'staff',
            ]);

            return response()->json([
                'message' => 'Akun staff berhasil dibuat.',
                'user' => $user
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal membuat akun staff: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $query = User::whereNull('role');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->get();
        return response()->json($users);
    }

    public function getStats()
    {
        $stats = [
            'total_pendaftar' => User::whereNull('role')->count(),
            'pendaftaran_awal_selesai' => User::whereNull('role')->where('pendaftaran_awal', true)->count(),
            'pembayaran_selesai' => User::whereNull('role')->where('pembayaran', true)->count(),
            'daftar_ulang_selesai' => User::whereNull('role')->where('daftar_ulang', true)->count(),
        ];
        return response()->json($stats);
    }

    public function getUserDetails(User $user)
    {
        $user->load('paymentConfirmedByAdmin', 'dafulConfirmedByAdmin');
        return response()->json($user);
    }

    public function getActiveStudents(Request $request)
    {
        $query = User::where('daftar_ulang', true)->whereNull('role');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $activeStudents = $query->orderBy('name', 'asc')->get();
        return response()->json($activeStudents);
    }

    public function updateActiveStudentDetails(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'jadwal_kuliah' => ['required', Rule::in(['Pagi', 'Malam'])],
            'prodi_pilihan' => ['required', 'string', 'max:255'],
        ]);

        try {
            $user->update([
                'jadwal_kuliah' => $validatedData['jadwal_kuliah'],
                'prodi_pilihan' => $validatedData['prodi_pilihan'],
            ]);
            return response()->json(['message' => 'Data mahasiswa berhasil diperbarui.', 'user' => $user]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memperbarui data mahasiswa: ' . $e->getMessage()], 500);
        }
    }

    public function confirmInitialRegistration(User $user)
    {
        try {
            $user->update([
                'pendaftaran_awal' => true,
                'formulir_pendaftaran_completed' => true,
                'formulir_pendaftaran_status' => 'Sudah Mengisi Formulir',
            ]);
            return response()->json(['message' => 'Pendaftaran awal berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Pendaftaran Awal Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function confirmPayment(User $user)
    {
        try {
            $user->update([
                'pembayaran' => true,
                'pembayaran_form_completed' => true,
                'pembayaran_form_status' => 'Pembayaran Sudah Dikonfirmasi',
                'administrasi_status' => 'Sudah Lolos Administrasi',
                'administrasi_completed' => true,
                'tes_seleksi_status' => 'Belum Mengikuti Tes',
                'payment_confirmed_by' => Auth::id(),
                'payment_confirmed_at' => now(),
            ]);
            return response()->json(['message' => 'Pembayaran berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Pembayaran Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function confirmReRegistration(User $user)
    {
        try {
            $user->update([
                'daftar_ulang' => true,
                'pembayaran_daful_completed' => true,
                'pembayaran_daful_status' => 'Pembayaran Sudah Dikonfirmasi',
                'daful_confirmed_by' => Auth::id(),
                'daful_confirmed_at' => now(),
            ]);
            return response()->json(['message' => 'Daftar ulang berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Daftar Ulang Gagal: ' . $e->getMessage()], 500);
        }
    }
}
