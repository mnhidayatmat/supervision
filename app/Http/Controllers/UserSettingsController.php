<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    /**
     * Update user's theme preference.
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        Auth::user()->update([
            'theme' => $request->theme,
        ]);

        return response()->json([
            'success' => true,
            'theme' => $request->theme,
        ]);
    }
}
