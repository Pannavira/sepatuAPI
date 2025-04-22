<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Dashboard::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('preferred_layout')) {
            $query->where('preferred_layout', $request->preferred_layout);
        }
        
        if ($request->has('widget_settings')) {
            $query->where('widget_settings', $request->widget_settings);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('preferred_layout', 'like', '%' . $request->search . '%')
                  ->orWhere('widget_settings', 'like', '%' . $request->search . '%');
            });
        }
        
        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'], 404);
            }

        return response()->json($get);
    }
}