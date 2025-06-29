<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
class BookingController extends Controller {
    public function __construct() {
        $this->middleware('auth:sanctum'); // <-- Perubahan: Semua method di sini memerlukan autentikasi
    }
    // READ ALL bookings (untuk user yang login)
    public function index() {
        $bookings = Auth::user()->bookings()->with('package')->get();
        return response()->json($bookings);
    }
    // CREATE booking
    public function store(Request $request) {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $package = Package::find($request->package_id);
        if (!$package || !$package->is_available) {
            return response()->json(['message' => 'Package is not available.'], 400);
        }
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $durationInDays = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $package->daily_price * $durationInDays;
        $bookingCode = 'INV-' . Str::upper(Str::random(8));
        while (Booking::where('booking_code', $bookingCode)->exists()) {
            $bookingCode = 'INV-' . Str::upper(Str::random(8));
        }
        $overlappingBookings = Booking::where('package_id', $package->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($query) use ($startDate, $endDate) {
                          $query->where('start_date', '<', $startDate)
                                ->where('end_date', '>', $endDate);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed', 'rented'])
            ->count();
        if ($overlappingBookings > 0) {
            return response()->json(['message' => 'Package is not available for the selected dates.'], 400);
        }
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $request->package_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_code' => $bookingCode,
        ]);
        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking->load('package')], 201);
    }
    // READ ONE booking (hanya untuk user yang bersangkutan)
    public function show(Booking $booking) {
        // Pastikan user yang login adalah pemilik booking ini atau admin
        if (Auth::user()->id !== $booking->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized to view this booking.'], 403);
        }
        return response()->json($booking->load('user', 'package'));
    }
    // UPDATE status booking (khusus admin, perlu pengecekan role di sini)
    public function updateStatus(Request $request, Booking $booking) {
        if (Auth::user()->role !== 'admin') { // <-- Pengecekan role admin
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'status' => 'required|in:pending,confirmed,rented,completed,cancelled',
        ]);
        $booking->status = $request->status;
        $booking->save();
        return response()->json(['message' => 'Booking status updated successfully', 'booking' => $booking]);
    }
}