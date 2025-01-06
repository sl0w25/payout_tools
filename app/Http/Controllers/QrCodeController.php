<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\FamilyHead;
use App\Models\LocationInfo;

class QrCodeController extends Controller
{

   
    public function index()
    {
        $attendances = Attendance::paginate(10); 
        return view('welcome', compact('attendances'));
    }

    
    public function store(Request $request)
    {
       // \Log::info($request->all());

        $request->validate([
            'qr_number' => 'required|string',
        ]);
    
        $bene = FamilyHead::where('qr_number', $request->qr_number)->first();
        $alreadyRecorded = Attendance::where('fam_id', $bene->fam_id)
            ->exists();
        $time_in = Attendance::where('fam_id', $bene->fam_id)->first();
       
        if (!$bene) {
            return response()->json(['error' => 'Bene not found!', 'attendances' => Attendance::latest()->get()]);
       
         }else if ($alreadyRecorded) {
            return response()->json(['error' => 'Oops!<br>' . $bene->first_name.' ' . $bene->last_name .' was already paid on ' . $time_in->time_in, 'attendances' => Attendance::latest()->get()]);
        }
        else if($bene) {
    
        $barangay = LocationInfo::where('id', $bene->fam_id)->first();
    
        Attendance::create([
            'fam_id' => $bene->fam_id,
            'barangay' => $barangay->barangay,
            'first_name' => $bene->first_name,
            'middle_name' => $bene->middle_name,
            'last_name' => $bene->last_name,
            'ext_name' => $bene->ext_name,
            'qr_number' => $request->qr_number,
            'status' => 'Paid',
            'amount' => '5000',
            'time_in' => now()->format('Y-m-d h:i A'),
        ]);
    
        Assistance::where('fam_id', $bene->fam_id)
            ->update(['cost' => '5000', 'status' => 'Paid']);
            
    
            return response()->json([
                'message' => 'Successfully Identified!<br>Name: ' . $bene->first_name . ' ' . 
                             ($bene->middle_name ? $bene->middle_name . ' ' : '') . 
                             $bene->last_name . '<br>Barangay: ' . $barangay->barangay,
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