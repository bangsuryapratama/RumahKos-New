<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
        public function index()
        {
            $totalRooms     = Room::count();
            $availableRooms = Room::where('status', 'available')->count();
            $occupiedRooms  = Room::where('status', 'occupied')->count();
            $totalRevenue   = Payment::sum('amount');


            

            return view('admin.dashboard', compact(
                'totalRooms',
                'availableRooms',
                'occupiedRooms',
                'totalRevenue'
            ));
        }

}
