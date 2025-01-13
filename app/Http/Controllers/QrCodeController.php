<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\Aurora;
use App\Models\Bataan;
use App\Models\Bulacan;
use App\Models\Nueva;
use App\Models\Pampanga;
use App\Models\Tarlac;
use App\Models\Zamb;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\FamilyHead;
use App\Models\LocationInfo;
use Illuminate\Support\Facades\DB;

class QrCodeController extends Controller
{

    
    public function index()
    {
        $attendances = Attendance::paginate(10); 
        return view('welcome', compact('attendances'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'qr_number' => 'required|string',
        ]);
        
    
        $bene = FamilyHead::where('qr_number', $request->qr_number)->first();

    
        if (!$bene) {
            return response()->json([
                'error' => 'Beneficiary not found!',
                'attendances' => Attendance::latest()->get(),
            ]);
        }
    
        $attendanceRecord = Attendance::where('fam_id', $bene->fam_id)->first();
    
        if ($attendanceRecord) {
            return response()->json([
                'error' => 'Oops!<br>' . $bene->first_name . ' ' . $bene->last_name . 
                            ' was already paid on ' . $attendanceRecord->time_in,
                'attendances' => Attendance::latest()->get(),
            ]);
        
        } else{

           DB::transaction(function () use ($bene, $request) {
                Attendance::create([
                    'fam_id' => $bene->fam_id,
                    'province' => $bene->province,
                    'municipality' => $bene->municipality,
                    'barangay' => $bene->barangay,
                    'first_name' => $bene->first_name,
                    'middle_name' => $bene->middle_name,
                    'last_name' => $bene->last_name,
                    'ext_name' => $bene->ext_name,
                    'qr_number' => $request->qr_number,
                    'status' => 'Paid',
                    'amount' => '5000',
                    'time_in' => now()->format('Y-m-d h:i A'),
                ]);
        
                Assistance::where('fam_id', $bene->fam_id)->update(['cost' => '5000', 'status' => 'Paid']);
                FamilyHead::where('fam_id', $bene->fam_id)->update(['status' => 'Paid']);
                
                if ($bene->province == 'Aurora') {
                    Aurora::where('municipality', $bene->municipality)->increment('paid');
                    Aurora::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Bulacan') {
                    Bulacan::where('municipality', $bene->municipality)->increment('paid');
                    Bulacan::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Bataan') {
                    Bataan::where('municipality', $bene->municipality)->increment('paid');
                    Bataan::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Nueva Ecija') {
                    Nueva::where('municipality', $bene->municipality)->increment('paid');
                    Nueva::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Pampanga') {
                    Pampanga::where('municipality', $bene->municipality)->increment('paid');
                    Pampanga::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Tarlac') {
                    Tarlac::where('municipality', $bene->municipality)->increment('paid');
                    Tarlac::where('municipality', $bene->municipality)->decrement('unpaid');
                }
                if ($bene->province == 'Zambales') {
                    Zamb::where('municipality', $bene->municipality)->increment('paid');
                    Zamb::where('municipality', $bene->municipality)->decrement('unpaid');
                }
          });
        

        return response()->json([
            'message' => 'Successfully Identified!<br>Name: ' . $bene->first_name . ' ' . 
                         ($bene->middle_name ? $bene->middle_name . ' ' : '') . 
                         $bene->last_name . '<br>Barangay: ' . $bene->barangay,
            'attendances' => Attendance::latest()->get(),
        ]);

    }
    
    
        
    }
    
    

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance record removed successfully.');
    }
}