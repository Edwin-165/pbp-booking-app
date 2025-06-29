<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Equipment;
use Illuminate\Http\Request;

class PackageController extends Controller {
    // READ ALL (Public)
    public function index() {
        $packages = Package::all();
        return response()->json($packages);
    }
    // READ ONE (Public)
    public function show(Package $package) {
        return response()->json($package);
    }
    // CREATE (Protected)
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:packages,name',
            'description' => 'required|string',
            'daily_price' => 'required|numeric|min:0',
            'is_available' => 'boolean',
        ]);
        $package = Package::create($request->all());
        return response()->json(['message' => 'Package created successfully', 'package' => $package], 201);
    }
    // UPDATE (Protected)
    public function update(Request $request, Package $package) {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:packages,name,' . $package->id,
            'description' => 'sometimes|required|string',
            'daily_price' => 'sometimes|required|numeric|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        $package->update($request->all()); // Langsung update dari semua request yang valid

        return response()->json(['message' => 'Package updated successfully', 'package' => $package]);
    }
    // DELETE (Protected)
    public function destroy(Package $package) {
        $package->delete();
        return response()->json(['message' => 'Package deleted successfully'], 204);
    }
}