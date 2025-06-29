<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
class EquipmentController extends Controller {
    // READ ALL (Public)
    public function index() {
        $equipment = Equipment::all();
        return response()->json($equipment);
    }
    // READ ONE (Public)
    public function show(Equipment $equipment) {
        return response()->json($equipment);
    }
    // CREATE (Protected)
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:equipment,name',
            'total_quantity' => 'required|integer|min:0',
        ]);
        $equipment = Equipment::create($request->all());
        return response()->json(['message' => 'Equipment created successfully', 'equipment' => $equipment], 201);
    }
    // UPDATE (Protected)
    public function update(Request $request, Equipment $equipment) {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:equipment,name,' . $equipment->id,
            'total_quantity' => 'sometimes|required|integer|min:0',
        ]);
        $equipment->update($request->all());
        return response()->json(['message' => 'Equipment updated successfully', 'equipment' => $equipment]);
    }
    // DELETE (Protected)
    public function destroy(Equipment $equipment) {
        if ($equipment->packages()->exists()) {
            return response()->json(['message' => 'Cannot delete equipment: It is part of one or more packages.'], 409);
        }
        $equipment->delete();
        return response()->json(['message' => 'Equipment deleted successfully'], 204);
    }
}