<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PermintaanModel;
use App\Models\CompanyProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use RealRashid\SweetAlert\Facades\Alert;

class Backend extends Controller
{
    public function signin()
    {
        $data = array(
            'title' => 'Login | ',
            'companyProfile' => CompanyProfile::firstOrFail(),
        );
        return view('backend.login', $data);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $monthlyStart = $today->copy()->startOfMonth();
        $yearlyStart = $today->copy()->startOfYear();
        
        $todayIncome = Transaksi::whereDate('created_at', $today)->sum('total');
        $monthlyIncome = Transaksi::whereBetween('created_at', [$monthlyStart, $today->endOfDay()])->sum('total');
        $yearlyIncome = Transaksi::whereBetween('created_at', [$yearlyStart, $today->endOfDay()])->sum('total');

        $data = [
            'title' => 'Dashboard | ',
            'todayIncome' => $todayIncome,
            'monthlyIncome' => $monthlyIncome,
            'yearlyIncome' => $yearlyIncome,
        ];
        
        return view('backend.dashboard', $data);
    }


    public function profile(Request $request)
    {
        $data = array(
            'title' => 'Profile | ',
            'user' => $request->user(),
        );
        return view('backend.profile', $data);
    }

    public function editCompany()
    {
        $data = array(
            'title' => 'Profile Perusahaan | ',
            'companyProfile' => CompanyProfile::firstOrFail(),
        );
        return view('backend.company_profile', $data);
    }

    public function updateCompany(Request $request)
    {
        $companyProfile = CompanyProfile::firstOrFail();

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update fields
        $companyProfile->name = $request->name;
        $companyProfile->address = $request->address;
        $companyProfile->phone = $request->phone;
        $companyProfile->email = $request->email;
        $companyProfile->website = $request->website;
        $companyProfile->logo = $request->logo ?? 0;
        $companyProfile->description = $request->description;

        // Update image if new one is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($companyProfile->image) {
                $oldImagePath = public_path($companyProfile->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        
            // Process the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $companyProfile->image = 'img/' . $imageName;
        }        

        $companyProfile->save();
        Alert::success('Success', 'Company profile updated successfully.');
        return redirect()->route('companyProfile');
    }
}