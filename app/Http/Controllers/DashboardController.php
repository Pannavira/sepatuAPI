<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    // GET /api/dashboards
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
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('preferred_layout', 'like', '%' . $request->search . '%')
                  ->orWhere('widget_settings', 'like', '%' . $request->search . '%');
            });
        }

        $dashboards = $query->get();

        if ($dashboards->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($dashboards);
    }

    // POST /api/dashboards
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'preferred_layout' => 'nullable|string|max:255',
            'widget_settings' => 'nullable|string'
        ]);

        $dashboard = Dashboard::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard created successfully',
            'data' => $dashboard
        ], 201);
    }

    // GET /api/dashboards/{id}
    public function show($id)
    {
        $dashboard = Dashboard::find($id);

        if (!$dashboard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dashboard not found'
            ], 404);
        }

        return response()->json($dashboard);
    }

    // PUT/PATCH /api/dashboards/{id}
    public function update(Request $request, $id)
    {
        $dashboard = Dashboard::find($id);

        if (!$dashboard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dashboard not found'
            ], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|required|integer',
            'preferred_layout' => 'nullable|string|max:255',
            'widget_settings' => 'nullable|string'
        ]);

        $dashboard->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard updated successfully',
            'data' => $dashboard
        ]);
    }

    // DELETE /api/dashboards/{id}
    public function destroy($id)
    {
        $dashboard = Dashboard::find($id);

        if (!$dashboard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dashboard not found'
            ], 404);
        }

        $dashboard->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard deleted successfully'
        ]);
    }
}
