<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PengeluaranHeader;
use App\Models\PengeluaranDetail;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranHeader::with('user', 'details')
                    ->orderBy('tanggal', 'desc')
                    ->get();
        $data = array(
            'title' => 'Pengeluaran | ',
            'datapengeluaran' => $pengeluaran,
        );
        $title = 'Delete Pengeluaran!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.pengeluaran.index', $data);
    }

    public function show($id)
    {
        $pengeluaranItem = PengeluaranHeader::with('user','details')->findOrFail($id);
        return response()->json($pengeluaranItem);
    }

    public function create()
    {
        $data = array(
            'title' => 'Add Pengeluaran | ',
        );
        return view('backend.pengeluaran.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'details' => 'required|array',
            'details.*.nominal' => 'required|integer|min:1',
            'details.*.keterangan' => 'required|string|max:255',
        ]);

        $pengeluaran = PengeluaranHeader::create([
            'tanggal' => $request->tanggal,
            'user_id' => $request->user_id,
        ]);

        foreach ($request->details as $detail) {
            PengeluaranDetail::create([
                'pengeluaran_header_id' => $pengeluaran->id,
                'nominal' => $detail['nominal'],
                'keterangan' => $detail['keterangan'],
            ]);
        }      

        Alert::success('Success', 'Pengeluaran created successfully.')->autoClose(2000);
        return redirect()->route('pengeluaran.index');
    }

    public function edit($id)
    {
        $pengeluaran = PengeluaranHeader::with('details')->findOrFail($id);

        return response()->json([
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'details' => 'required|array',
            'details.*.nominal' => 'required|integer|min:1',
            'details.*.keterangan' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $pengeluaran = PengeluaranHeader::findOrFail($id);
            $pengeluaran->update([
                'tanggal' => $request->tanggal,
                'user_id' => $request->user_id,
            ]);

            // Remove existing detail rows
            $pengeluaran->details()->delete();

            // Insert new detail rows
            foreach ($request->details as $detail) {
                $pengeluaran->details()->create([
                    'nominal' => $detail['nominal'],
                    'keterangan' => $detail['keterangan'],
                ]);
            }

            DB::commit();

            Alert::success('Success', 'Pengeluaran updated successfully.');
            return redirect()->route('pengeluaran.index');

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Pengeluaran not found']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $pengeluaran = PengeluaranHeader::findOrFail($id);

        // Delete related pengeluaranDetail
        $pengeluaran->details()->delete(); // Assuming the relation name is `details`

        // Finally, delete the pengeluaran
        $pengeluaran->delete();

        Alert::success('Success', 'Pengeluaran deleted successfully.');
        return redirect()->route('pengeluaran.index');
    }
}
