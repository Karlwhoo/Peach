<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\TaxSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\InventoryConsumption;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Rooms = Room::all();
        $Guests = Guest::all();
        $TaxSettings = TaxSetting::all();

        if (request()->ajax()) {
            return $Bookings = Datatables::of($this->dtQuery())->addColumn('action','layouts.dt_buttons_2')->make(true);
        }
        return view('booking.index',compact('Rooms','Guests','TaxSettings'));
       
    }
    public function dtQuery()
    {
        return Booking::select(
            'bookings.*',
            'rooms.RoomNo as Room',
            'rooms.Price as RoomPrice',
            'bookings.Status as Booking',
            DB::raw("CONCAT(guests.Fname, ' ', guests.Mname, ' ', guests.Lname) as Guest")
        )
        ->leftJoin('rooms', 'bookings.RoomID', '=', 'rooms.id')
        ->leftJoin('guests', 'bookings.GuestID', '=', 'guests.id')
        ->where('bookings.Status', '!=', 'checkout')
        ->where('bookings.Status', '!=', 'cancelled')
        ->addSelect(\DB::raw('DATEDIFF(bookings.CheckOutDate, bookings.CheckInDate) as NumberOfDays'))
        ->addSelect(\DB::raw('DATEDIFF(bookings.CheckOutDate, bookings.CheckInDate) * rooms.Price as TotalPrice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Rooms = Room::all();
        $Guests = Guest::all();
        return view('booking.create',compact('Rooms', 'Guests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'RoomID' => 'required|exists:rooms,id',
            'GuestID' => 'required|exists:guests,id',
            'Category' => 'required|in:walkin,online',
            'Status' => 'required|in:checkin,checkout,reserved,cancelled',
            'CheckInDate' => 'required|date',
            'CheckOutDate' => 'required|date|after:CheckInDate',
            'AddOns' => 'nullable|in:bed,breakfast,none',
            'Tax' => 'required|numeric',
            'AmountPaid' => 'required|numeric',
            'NumberOfDays' => 'required|integer',
            'TotalPrice' => 'required|numeric',
            'TotalBalance' => 'required|numeric',
            'ModeOfPayment' => 'required|in:cash,gcash,bank',
            'RefNo' => 'required_if:ModeOfPayment,gcash,bank',
            'IdType' => 'required|string',
            'IdNumber' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            
            // Store the Tax value as LastSelectedDiscount
            $validated['LastSelectedDiscount'] = $validated['Tax'];
            
            // Create the booking
            $booking = Booking::create($validated);
            
            // Update room status
            Room::find($request->RoomID)->update(['Status' => 1]);
            
            // Handle consumable items deduction
            $consumableItems = Income::where('category_type', 'consumable')
                                   ->where('remaining_quantity', '>', 0)
                                   ->get();
                                   
            foreach ($consumableItems as $item) {
                if ($item->deductStock()) {
                    // Record the consumption
                    InventoryConsumption::create([
                        'income_id' => $item->id,
                        'booking_id' => $booking->id,
                        'quantity_consumed' => 1,
                        'unit_price' => $item->Amount,
                        'notes' => 'Automatically deducted for booking #' . $booking->id
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Booking = Booking::with(['room', 'guest'])
            ->select([
                'bookings.*',
                'tax_settings.Name as TaxName',
                'tax_settings.Percent as TaxPercent'
            ])
            ->leftJoin('tax_settings', function($join) {
                $join->on(DB::raw('tax_settings.Percent'), '=', DB::raw('bookings.LastSelectedDiscount'))
                     ->whereNull('tax_settings.deleted_at');
            })
            ->findOrFail($id);

        return response()->json([
            'id' => $Booking->id,
            'RoomID' => $Booking->RoomID,
            'GuestID' => $Booking->GuestID,
            'CheckInDate' => $Booking->CheckInDate,
            'CheckOutDate' => $Booking->CheckOutDate,
            'Category' => $Booking->Category,
            'Status' => $Booking->Status,
            'AddOns' => $Booking->AddOns,
            'AmountPaid' => $Booking->AmountPaid,
            'TotalBalance' => $Booking->TotalBalance,
            'IdType' => $Booking->IdType,
            'IdNumber' => $Booking->IdNumber,
            'LastSelectedDiscount' => $Booking->LastSelectedDiscount,
            'Tax' => $Booking->Tax,
            'TaxName' => $Booking->TaxName,
            'TaxPercent' => $Booking->TaxPercent,
            'ModeOfPayment' => $Booking->ModeOfPayment,
            'RefNo' => $Booking->RefNo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Booking = Booking::with(['room', 'guest'])
            ->select([
                'bookings.*',
                'tax_settings.Name as TaxName'
            ])
            ->leftJoin('tax_settings', function($join) {
                $join->on('tax_settings.Percent', '=', 'bookings.LastSelectedDiscount')
                     ->whereNull('tax_settings.deleted_at');
            })
            ->findOrFail($id);

        return response()->json([
            'id' => $Booking->id,
            'RoomID' => $Booking->RoomID,
            'GuestID' => $Booking->GuestID,
            'CheckInDate' => $Booking->CheckInDate,
            'CheckOutDate' => $Booking->CheckOutDate,
            'Category' => $Booking->Category,
            'Status' => $Booking->Status,
            'AddOns' => $Booking->AddOns,
            'AmountPaid' => $Booking->AmountPaid,
            'TotalBalance' => $Booking->TotalBalance,
            'IdType' => $Booking->IdType,
            'IdNumber' => $Booking->IdNumber,
            'LastSelectedDiscount' => $Booking->LastSelectedDiscount,
            'TaxName' => $Booking->TaxName,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $validated = $request->validate([
                'RoomID' => 'required|exists:rooms,id',
                'GuestID' => 'required|exists:guests,id',
                'CheckInDate' => 'required|date',
                'CheckOutDate' => 'required|date|after:CheckInDate',
                'Status' => 'required|in:reserved,checkin,checkout,cancelled,rebooked',
                'Tax' => 'required|numeric|min:0|max:100',
                'AddOns' => 'nullable|string',
                'AmountPaid' => 'required|numeric|min:0',
                'TotalBalance' => 'required|numeric',
                'ModeOfPayment' => 'required|string',
                'RefNo' => 'nullable|string',
                'IdType' => 'required|string',
                'IdNumber' => 'required|string'
            ]);

            // Convert Tax from percentage to decimal for storage
            $validated['Tax'] = $validated['Tax'] / 100;
            $validated['LastSelectedDiscount'] = $validated['Tax'];

            // Get the tax name from tax_settings
            $taxSetting = \App\Models\TaxSetting::where('Percent', $validated['Tax'])
                ->whereNull('deleted_at')
                ->first();

            // Update the booking
            $booking->update($validated);

            // Handle room status updates
            switch ($request->Status) {
                case 'checkout':
                case 'cancelled':
                    Room::where('id', $request->RoomID)->update(['Status' => null]);
                    break;
                case 'rebooked':
                case 'reserved':
                case 'checkin':
                    Room::where('id', $request->RoomID)->update(['Status' => 1]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'refresh' => in_array($request->Status, ['checkout', 'cancelled']),
                'tax_name' => $taxSetting ? $taxSetting->Name : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Booking::find($id)->delete();
        return back();
    }
     /**
     * Delete all table list
    */
    public function destroyAll()
    {
        Booking::withTrashed()->delete();
        return back()->with('DestroyAll', '');
    }
    /**
     * View Trash page 
    */
    public function trash()
    {
        $Bookings = Booking::onlyTrashed()->get();
        return view('booking.trash',compact('Bookings'));
    }
    /**
     * table column restore
    */
    public function restore($id)
    {
        Booking::withTrashed()->where('id',$id)->restore();
        return back()->with('Restore', 'Restore SuccessFully !');
    }
    /**
     * Table  all Column list restore
    */
    public function restoreAll()
    {
        Booking::withTrashed()->restore();
        return back()->with('RestoreAll', '');
    }
    /**
     * table remove delete
    */
    public function forceDeleted($id)
    {
        Booking::withTrashed()->where('id',$id)->forceDelete();
        return back()->with('PermanentlyDelete', 'Permanently Delete Completed !');
    }
    /**
     * All table list remove
    */
    public function emptyTrash()
    {
        Booking::onlyTrashed()->forceDelete();
        return back()->with('EmptyTrash', '');
    }
    public function getAvailability(Request $request)
    {
        try {
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $roomType = $request->input('roomType', 'STANDARD KING');

            // Debug log
            \Log::info('Fetching availability for:', [
                'month' => $month,
                'year' => $year,
                'roomType' => $roomType
            ]);

            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth();

            // Get rooms of the specified type
            $rooms = Room::where('Type', 'LIKE', '%' . $roomType . '%')->get();
            
            \Log::info('Found rooms:', ['count' => $rooms->count(), 'ids' => $rooms->pluck('id')]);

            if ($rooms->isEmpty()) {
                return response()->json([
                    'error' => 'No rooms found for type: ' . $roomType,
                    'available_types' => Room::distinct()->pluck('Type')
                ], 404);
            }

            // Get bookings for these rooms
            $bookings = Booking::whereIn('RoomID', $rooms->pluck('id'))
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where(function($q) use ($startDate, $endDate) {
                        $q->where('CheckInDate', '<=', $endDate)
                          ->where('CheckOutDate', '>=', $startDate);
                    });
                })
                ->where('Status', '!=', 'checkout')
                ->where('Status', '!=', 'cancelled')
                ->get();

            \Log::info('Found bookings:', ['count' => $bookings->count()]);

            $availability = [];
            $daysInMonth = $endDate->day;
            $totalRooms = $rooms->count();

            // Process each day of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::createFromDate($year, $month, $day)->startOfDay();
                $roomsBooked = 0;

                // Count bookings for current date
                foreach ($bookings as $booking) {
                    $checkIn = Carbon::parse($booking->CheckInDate)->startOfDay();
                    $checkOut = Carbon::parse($booking->CheckOutDate)->startOfDay();

                    if ($currentDate->between($checkIn, $checkOut)) {
                        $roomsBooked++;
                    }
                }

                $roomsAvailable = $totalRooms - $roomsBooked;

                // Determine status
                $status = 'available';
                if ($roomsBooked >= $totalRooms) {
                    $status = 'booked';
                } elseif ($roomsBooked > 0) {
                    $status = 'stay';
                }

                $availability[] = [
                    'date' => $day,
                    'status' => $status,
                    'rooms_available' => max(0, $roomsAvailable),
                    'total_rooms' => $totalRooms,
                    'rooms_booked' => $roomsBooked
                ];
            }

            \Log::info('Availability calculation complete', [
                'days_calculated' => count($availability),
                'sample_day' => $availability[0] ?? null
            ]);

            return response()->json($availability);

        } catch (\Exception $e) {
            \Log::error('Error in getAvailability:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to get room availability',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    public function getRoomStatus($roomId, $year, $month)
    {
        // Get all bookings for this room in the specified month
        $bookings = Booking::where('RoomID', $roomId)
            ->whereYear('CheckInDate', $year)
            ->whereMonth('CheckInDate', $month)
            ->orWhere(function($query) use ($roomId, $year, $month) {
                $query->where('RoomID', $roomId)
                    ->whereYear('CheckOutDate', $year)
                    ->whereMonth('CheckOutDate', $month);
            })
            ->get();

        $status = [];
        $today = now();
        
        // Get all days in the month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day)->format('Y-m-d');
            
            // Check if room is occupied on this date
            $occupied = false;
            $checkoutSoon = false;
            
            foreach ($bookings as $booking) {
                $checkIn = Carbon::parse($booking->CheckInDate);
                $checkOut = Carbon::parse($booking->CheckOutDate);
                
                if ($currentDate >= $checkIn->format('Y-m-d') && $currentDate < $checkOut->format('Y-m-d')) {
                    $occupied = true;
                    
                    // Check if checkout is within next 2 days
                    if ($checkOut->diffInDays(Carbon::parse($currentDate)) <= 2) {
                        $checkoutSoon = true;
                    }
                    break;
                }
            }
            
            if ($occupied) {
                $status[$currentDate] = $checkoutSoon ? 'checkout-soon' : 'occupied';
            } else {
                $status[$currentDate] = 'available';
            }
        }
        
        return response()->json($status);
    }
    public function viewBookings()
    {
        $bookings = Booking::select(
            'bookings.*',
            'rooms.RoomNo',
            'rooms.Price as RoomPrice',
            DB::raw("CONCAT(guests.Fname, ' ', guests.Mname, ' ', guests.Lname) as GuestName")
        )
        ->leftJoin('rooms', 'bookings.RoomID', '=', 'rooms.id')
        ->leftJoin('guests', 'bookings.GuestID', '=', 'guests.id')
        ->where('bookings.Status', '!=', 'checkout')
        ->where('bookings.Status', '!=', 'cancelled')
        ->orderBy('bookings.created_at', 'desc')
        ->get();

        return view('booking.view_booking', compact('bookings'));
    }
    public function viewCheckouts()
    {
        $checkouts = Booking::select(
            'bookings.*',
            'rooms.RoomNo',
            'rooms.Price as RoomPrice',
            DB::raw("CONCAT(guests.Fname, ' ', guests.Mname, ' ', guests.Lname) as GuestName")
        )
        ->join('rooms', 'bookings.RoomID', '=', 'rooms.id')
        ->join('guests', 'bookings.GuestID', '=', 'guests.id')
        ->where('bookings.Status', 'checkout')
        ->where('bookings.Status', '!=', 'cancelled')
        ->orderBy('bookings.updated_at', 'desc')
        ->get();

        return view('booking.checkouts', compact('checkouts'));
    }
}
