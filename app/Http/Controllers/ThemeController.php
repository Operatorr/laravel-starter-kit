<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Update user's theme preference
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'theme' => 'required|string|in:garden,sunset'
        ]);

        if (Auth::check()) {
            Auth::user()->update([
                'theme' => $request->theme
            ]);

            return response()->json([
                'success' => true,
                'theme' => $request->theme
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ], 401);
    }

    /**
     * Get user's current theme
     */
    public function show(): JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'theme' => Auth::user()->theme ?? 'garden'
            ]);
        }

        return response()->json([
            'theme' => 'garden'
        ]);
    }
}