<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('education_level', 'like', "%{$search}%");
            });
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'name' => 'required|string',
            'education_level' => 'required|string',
            'parent_name' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Nonaktif',
        ]);

        $student = Student::create($data);

        return response()->json(['message' => 'Student created', 'student' => $student], 201);
    }

    public function getStats()
    {
        // Hitung total siswa
        $total = Student::count();

        // Cek jika database kosong
        if ($total == 0) {
            // --- INI ADALAH FALLBACK ANDA ---
            // Kembalikan data dummy sesuai gambar [image_85b806.png]
            return response()->json([
                'aktif' => 26,
                'nonaktif' => 26,
                'most_education' => 'SD'
            ]);
        }

        // --- INI ADALAH LOGIKA ASLI ---
        // Jika data ada, hitung data aslinya
        $aktif = Student::where('status', 'Aktif')->count();
        $nonaktif = Student::where('status', 'Nonaktif')->count();
        
        $mostEducationQuery = Student::select('education_level', DB::raw('COUNT(*) as count'))
            ->groupBy('education_level')
            ->orderBy('count', 'DESC')
            ->first();

        return response()->json([
            'aktif' => $aktif,
            'nonaktif' => $nonaktif,
            'most_education' => $mostEducationQuery ? $mostEducationQuery->education_level : 'N/T' // N/T = Tidak Terdefinisi
        ]);
    }
}
