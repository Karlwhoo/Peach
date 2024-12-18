<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Rooms      = Room::all();
        $Users      = User::all();
        $Employes   = Employee::all();
        $Guests     = Guest::all();
        $Banks      = Bank::all();
        $BankLedger = BankLedger::all();

        // Get recent bookings with room and guest information
        $RecentBookings = Booking::select(
            'bookings.*',
            'rooms.RoomNo',
            'bookings.CheckInDate',
            'bookings.CheckOutDate',
            DB::raw("CONCAT(guests.Fname, ' ', guests.Mname, ' ', guests.Lname) as GuestName")
        )
        ->leftJoin('rooms', 'bookings.RoomID', '=', 'rooms.id')
        ->leftJoin('guests', 'bookings.GuestID', '=', 'guests.id')
        ->whereIn('bookings.Status', ['checkin', 'Reserved'])
        ->orderBy('bookings.created_at', 'desc')
        ->limit(5)
        ->get();

        // Add this to fetch recent checkouts
        $RecentCheckouts = Booking::select(
            'bookings.*',
            'rooms.RoomNo',
            DB::raw("CONCAT(guests.Fname, ' ', guests.Mname, ' ', guests.Lname) as GuestName")
        )
        ->join('rooms', 'bookings.RoomID', '=', 'rooms.id')
        ->join('guests', 'bookings.GuestID', '=', 'guests.id')
        ->where('bookings.Status', 'checkout')
        ->orderBy('bookings.updated_at', 'desc')
        ->limit(5)
        ->get();

        $TotalRooms       = $Rooms->count();
        $TotalFreeRooms   = $Rooms->where('Status',0)->count();
        $TotalBookedRooms = $Rooms->where('Status',1)->count();
        $TotalFloor       = $Rooms->where('Floor')->count();
        $TotalUser        = $Users->count();
        $TotalEmployee    = $Employes->count();
        $TotalGuest       = $Guests->count();
        $TotalBank        = $Banks->count();
        $TotalAccountNo   = $Banks->where('AccountNo')->count();
        $TotalWithdraw    = $BankLedger->where('Withdraw')->count();
        $TotalDeposit     = $BankLedger->where('Deposit')->count();

        return view('home', compact(
            'Rooms',
            'TotalRooms',
            'TotalBookedRooms',
            'TotalFreeRooms', 
            'TotalUser', 
            'TotalEmployee', 
            'TotalFloor', 
            'TotalGuest', 
            'TotalBank', 
            'TotalAccountNo', 
            'TotalWithdraw', 
            'TotalDeposit',
            'RecentBookings',
            'RecentCheckouts'
        ));
    }
}
