<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Role;
use App\Models\User;
use App\Models\Bayar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    protected function generateInvoiceNumber($userId)
    {
        // Format the date
        $date = date('ymd');

        // Get the last transaction for the user on that date
        $lastTransaction = Transaksi::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine the next number
        $nextNumber = $lastTransaction ? (int) substr($lastTransaction->no_inv, -3) + 1 : 1;
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "INV{$userId}-{$date}-{$formattedNumber}";
    }

    public function index()
    {
        $transaksi = Transaksi::with('user', 'details', 'bayar')
                    ->orderBy('created_at', 'desc')
                    ->get();
        $userId = auth()->user()->id;
        $no_inv = $this->generateInvoiceNumber($userId);
        $data = array(
            'title' => 'Transaksi | ',
            'datatransaksi' => $transaksi,
            'no_inv' => $no_inv,
        );
        $title = 'Delete Transaksi!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.transaksi.index', $data);
    }

    public function show($id)
    {
        $transaksiItem = transaksi::with('user', 'role')->findOrFail($id);
        return response()->json($transaksiItem);
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $no_inv = $this->generateInvoiceNumber($userId);
        $data = array(
            'title' => 'Add Transaksi | ',
            'no_inv' => $no_inv,
        );
        return view('backend.transaksi.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'details' => 'required|array',
            'details.*.berat' => 'required|integer|min:1',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga' => 'required|numeric|min:0',
            'details.*.satuan' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'nominal' => 'nullable|numeric|min:0',
        ]);
        // dd($request->all());
        
        $total = collect($request->details)->sum(function ($detail) {
            return $detail['qty'] * $detail['harga'];
        });

        // ✅ 1. ADD ITEM TO EXISTING TRANSAKSI
        if ($request->filled('transaksi_id')) {
            $transaksi = Transaksi::findOrFail($request->transaksi_id);

            // Add new detail items
            foreach ($request->details as $detail) {
                TransaksiDetail::create([
                    'table_transaksi_id' => $transaksi->id,
                    'no_inv' => $transaksi->no_inv,
                    'berat' => $detail['berat'],
                    'qty' => $detail['qty'],
                    'harga' => $detail['harga'],
                    'satuan' => $detail['satuan'],
                    'user_id' => $request->user_id,
                ]);
            }

            // Add payment if status is Lunas (0)
            if ($request->status == '0' && $request->filled('nominal')) {
                Bayar::create([
                    'table_transaksi_id' => $transaksi->id,
                    'nominal' => $request->nominal,
                    'user_id' => $request->user_id,
                ]);
            }

            // Update total and status
            $transaksi->update([
                'total' => $transaksi->total + $total,
                'status' => $request->status,
            ]);

            Alert::success('Success', 'Item berhasil ditambahkan ke transaksi.')->autoClose(2000);
            return redirect()->route('transaksi.index');
        }

        $no_inv = $this->generateInvoiceNumber($request->user_id);

        $transaksi = Transaksi::create([
            'no_inv' => $no_inv,
            'total' => $total,
            'user_id' => $request->user_id,
            'customer' => $request->customer ?? '',
            'status' => $request->status,
        ]);

        foreach ($request->details as $detail) {
            TransaksiDetail::create([
                'table_transaksi_id' => $transaksi->id,
                'no_inv' => $no_inv,
                'berat' => $detail['berat'],
                'qty' => $detail['qty'],
                'harga' => $detail['harga'],
                'satuan' => $detail['satuan'],
                'user_id' => $request->user_id,
            ]);
        }

        if ($request->status == '0') {
            Bayar::create([
                'table_transaksi_id' => $transaksi->id,
                'nominal' => $request->nominal,
                'user_id' => $request->user_id,
            ]);
        }        

        Alert::success('Success', 'Transaksi created successfully.')->autoClose(2000);
        session()->flash('print_transaction_id', $transaksi->id);
        return redirect()->route('transaksi.index');
    }

    public function edit(transaksi $transaksi)
    {
        $user = User::where('id', '!=', 1)->get();
        $role = Role::where('kode_role', '!=', 'superadmin')->get();
        $data = array(
            'title' => 'Edit Transaksi | ',
            'transaksi' => $transaksi,
            'datauser' => $user,
            'datarole' => $role,
        );
        return view('backend.transaksi.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $transaksi = transaksi::findOrFail($id);
            $transaksi->update($request->all());
            Alert::success('Success', 'transaksi updated successfully.');

            return redirect()->route('transaksi.index');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Delete related TransaksiDetail
        $transaksi->details()->delete(); // Assuming the relation name is `details`

        // Delete related Bayar
        Bayar::where('table_transaksi_id', $transaksi->id)->delete();

        // Finally, delete the transaksi
        $transaksi->delete();

        Alert::success('Success', 'Transaksi deleted successfully.');
        return redirect()->route('transaksi.index');
    }

    public function print($id)
    {
        $transaksi = Transaksi::with('details', 'user', 'bayar')->findOrFail($id);
        $pdf = FacadePdf::loadView('backend.transaksi.print_transaksi', compact('transaksi'));
        // $pdf->setPaper('A7', 'portrait');
        $pdf->setPaper([0, 0, 219, 620], 'portrait');
        return $pdf->stream(''.$transaksi->no_inv.'.pdf');
    }

    public function clearSession()
    {
        session()->forget('print_transaction_id');
        return response()->json(['success' => true]);
    }

    public function laporan()
    {   
        $data = array(
            'title' => 'Laporan | ',
        );        
        return view('backend.transaksi.laporan', $data);
    }

    public function cetakLaporan(Request $request)
    {
        $startDate = Carbon::parse($request->startDate)->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->endDate)->endOfDay()->toDateTimeString();

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);
    
        $transaksiQuery = Transaksi::with('user', 'details', 'bayar')->orderBy('created_at', 'desc');

        // Add date filtering only if both dates are provided
        if ($startDate && $endDate) {
            $transaksiQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $transaksi = $transaksiQuery->get();

        $data = array(
            'title' => 'Laporan | ',
            'datatransaksi' => $transaksi,
            'startDate' => $startDate,
            'endDate' => $endDate,
        );        
        // dd($data);
        $pdf = FacadePdf::loadView('backend.transaksi.print_laporan', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Laporan-'.$startDate.'-'.$endDate.'.pdf');
    }

    public function cicil(Request $request, $id)
    {
        $transaksi = Transaksi::with('bayar')->findOrFail($id);

        $request->validate([
            'nominal' => 'required|numeric|min:1'
        ]);

        $totalBayar = $transaksi->bayar->sum('nominal');
        // $totalBayar = $transaksi->bayars ? $transaksi->bayars->sum('nominal') : 0;

        $sisa = $transaksi->total - $totalBayar;

        if ($request->nominal > $sisa) {
            return back()->withErrors(['nominal' => 'Nominal melebihi sisa pembayaran']);
        }

        Bayar::create([
            'table_transaksi_id' => $transaksi->id,
            'nominal' => $request->nominal,
            'user_id' => auth()->id(),
        ]);

        // Update status if fully paid
        $transaksi->refresh(); // refresh model
        if ($transaksi->bayar->sum('nominal') >= $transaksi->total) {
            $transaksi->status = "0"; // Lunas
            $transaksi->save();
        }

        Alert::success('Berhasil', 'Pembayaran cicilan berhasil ditambahkan');
        return redirect()->route('transaksi.index');
    }

}
