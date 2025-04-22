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
        
        if ($request->has('layout')) {
            $query->where('preferred_layout', $request->layout);
        }
        
        if ($request->has('search')) {
            $query->where('widget_settings', 'like', '%' . $request->search . '%');
        }
        
        $dashboards = $query->get();
        
        if ($dashboards->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dashboard configurations not found'
            ], 404);
        }
        
        return response()->json($dashboards);
    }

    public function show($id)
    {
        $dashboard = Dashboard::find($id);
        
        if (!$dashboard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak di temukan'
            ], 404);
        }
        
        return response()->json($dashboard);
    }
}