<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slip;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class SlipController extends Controller
{
    public function index()
    {
        $slips = Slip::all();
        $slips->transform(function ($slip) {
            $slip->formatted_gp = 'Rp ' . number_format($slip->gp, 0, ',', '.');
            $slip->formatted_hutang = 'Rp ' . number_format($slip->hutang, 0, ',', '.');
    
            $slip->gaji_diterima = ($slip->gp + $slip->inssentif + $slip->uang_makan + $slip->bonus) - ($slip->pot_uang_makan + $slip->pot_kasbon);
            $slip->formatted_gaji_diterima = 'Rp ' . number_format($slip->gaji_diterima, 0, ',', '.');
    
            return $slip;
        });
        $data = array(
            'title' => 'Slip | ',
            'dataslip' => $slips,
        );
        $title = 'Delete Slip Gaji!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.slip.index', $data);
    }

    public function show($id)
    {
        $slipItem = Slip::with('user')->findOrFail($id);
        return response()->json($slipItem);
    }

    public function create()
    {
        $data = array(
            'title' => 'Add Slip Gaji | ',
        );
        return view('backend.slip.create', $data);
    }

    public function store(Request $request)
    {
        dd($request->all());
        // Slip::create($request->all());

        // Alert::success('Success', 'slip created successfully.');

        // return redirect()->route('slip.index');
    }

    public function edit(Slip $slip)
    {
        $data = array(
            'title' => 'Edit Slip | ',
            'slip' => $slip,
        );
        return view('backend.slip.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $slip = Slip::findOrFail($id);
            $slip->update($request->all());
            Alert::success('Success', 'slip updated successfully.');

            return redirect()->route('slip.index');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    public function destroy($id)
    {
        $slip = Slip::findOrFail($id);
        $slip->delete();
        Alert::success('Success', 'slip deleted successfully.');

        return redirect()->route('slip.index');
    }

    public function print($id)
    {
        $slip = Slip::with('user')->findOrFail($id);

        // Format tanggal to "10.08.2025"
        $tanggal = \Carbon\Carbon::parse($slip->tanggal)->format('d.m.Y');

        // Create filename like "andi-10.08.2025.pdf"
        $filename = $slip->nama . '-' . $tanggal . '.pdf';

        $pdf = FacadePdf::loadView('backend.slip.print_slip', compact('slip'));
        $pdf->setPaper('A5', 'portrait');

        return $pdf->stream($filename);
    }

}
