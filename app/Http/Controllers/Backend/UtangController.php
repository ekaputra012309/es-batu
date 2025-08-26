<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UtangBesar;
use App\Models\CicilUtang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class UtangController extends Controller
{
    protected function redirectBackToDashboardIfNeeded()
    {
        $previousUrl = url()->previous();

        try {
            $route = app('router')->getRoutes()->match(Request::create($previousUrl));
            $previousRoute = $route->getName();
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            $previousRoute = null;
        }

        // Fallback by checking path
        if ($previousRoute === 'dashboard' || str_contains($previousUrl, '/dashboard')) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('transaksi.index');
    }

    public function storeUtang(Request $request, $transaksiId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
        ]);
        // dd($request->all());
        UtangBesar::create([
            'table_transaksi_id' => $transaksiId,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'user_id' => $request->user_id,
        ]);

        Alert::success('Success', 'Utang Besar Add successfully.')->autoClose(2000);
        return $this->redirectBackToDashboardIfNeeded();
    }

    public function storeCicilUtang(Request $request)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.tanggal' => 'required:date',
            'details.*.nominal' => 'required|numeric',
        ]);
        // dd($request->all());
        $utang = UtangBesar::findOrFail($request->table_utang_id);

        foreach ($request->details as $detail) {
            CicilUtang::create([
                'table_utang_id' => $request->table_utang_id,
                'tanggal' => $detail['tanggal'],
                'nominal' => $detail['nominal'],
                'user_id' => $request->user_id,
            ]);
        }

        // Hitung total cicilan yang sudah dibayar
        $totalCicilan = $utang->cicilans()->sum('nominal');

        // Jika total cicilan >= nominal utang → status lunas
        $status = $totalCicilan >= $utang->nominal ? 1 : 0;

        $utang->update([
            'status' => $status,
        ]);

        Alert::success('Success', 'Cicil Utang Add successfully.')->autoClose(2000);
        return $this->redirectBackToDashboardIfNeeded();
    }

    public function cicilUtang(Request $request, $id)
    {
        $utang = UtangBesar::with('cicilans')->findOrFail($id);

        $request->validate([
            'nominal' => 'required|numeric|min:1'
        ]);

        $totalBayarUtang = $utang->cicilans->sum('nominal');
        // $totalBayar = $utang->bayars ? $utang->bayars->sum('nominal') : 0;

        $sisaUtang = $utang->nominal - $totalBayarUtang;

        if ($request->nominal > $sisaUtang) {
            return back()->withErrors(['nominal' => 'Nominal melebihi sisa pembayaran']);
        }

        CicilUtang::create([
            'table_utang_id' => $utang->id,
            'tanggal' => now()->toDateTimeString(),
            'nominal' => $request->nominal,
            'user_id' => auth()->id(),
        ]);
        
        // Update status if fully paid
        $utang->refresh(); // refresh model
        if ($utang->cicilans->sum('nominal') >= $utang->nominal) {
            $utang->status = "1"; // Lunas
            $utang->save();
        }

        Alert::success('Berhasil', 'Pembayaran cicilan hutang berhasil ditambahkan');
        return $this->redirectBackToDashboardIfNeeded();
    }
}
