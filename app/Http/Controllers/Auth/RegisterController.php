<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class RegisterController extends Controller
{
    public function showRegister()
    {
        $programmes = Programme::where('is_active', true)->orderBy('sort_order')->get();
        return view('auth.register', compact('programmes'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'student');

        // Base validation rules
        $rules = [
            'role' => 'required|in:student,supervisor',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string',
        ];

        // Role-specific validation
        if ($role === 'student') {
            $rules['matric_number'] = 'nullable|string|unique:users,matric_number';
            $rules['programme_id'] = 'required|exists:programmes,id';
        } else {
            $rules['staff_id'] = 'required|string|unique:users,staff_id';
            $rules['department'] = 'required|string|max:255';
            $rules['faculty'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // Create user
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role === 'supervisor' ? 'supervisor' : 'student',
            'phone' => $validated['phone'] ?? null,
            'status' => 'pending',
        ];

        if ($role === 'student') {
            $userData['matric_number'] = $validated['matric_number'] ?? null;
        } else {
            $userData['staff_id'] = $validated['staff_id'];
            $userData['department'] = $validated['department'];
            $userData['faculty'] = $validated['faculty'];
        }

        $user = User::create($userData);

        // Create student profile if registering as student
        if ($role === 'student') {
            $user->student()->create([
                'programme_id' => $validated['programme_id'],
                'status' => 'pending',
            ]);
        }

        $message = $role === 'student'
            ? 'Registration submitted. Your account is awaiting admin approval.'
            : 'Supervisor registration submitted. Your account is awaiting admin approval.';

        return redirect('/login')->with('success', $message);
    }
}
