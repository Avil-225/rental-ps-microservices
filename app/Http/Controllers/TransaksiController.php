<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use App\Models\Playstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    private $orderServiceUrl;

    public function __construct()
    {
        $this->orderServiceUrl = env('ORDER_SERVICE_URL', 'http://localhost:3002');
    }

    public function create()
    {
        $user = User::all();
        $playstation = Playstation::all();
        return view('create_transaksi', ['user' => $user, 'playstation' => $playstation]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'pengguna_id' => 'required|integer',
            'playstation_id' => 'required|integer',
            'tanggal' => 'required|date|date_format:Y-m-d',
            'durasiBermain' => 'required|integer',
            'jumlahOrang' => 'required|integer'
        ]);

        // UAS Requirement: Integration with Backend API via Axios (using Laravel Http client which uses Guzzle/Axios-like behavior)
        try {
            $response = Http::post($this->orderServiceUrl . '/orders/create', [
                'productId' => $validateData['playstation_id'],
                'quantity' => $validateData['durasiBermain'],
                'userId' => $validateData['pengguna_id'],
            ]);

            if ($response->successful()) {
                // Still save to local DB to maintain GUI functionality as requested
                Transaksi::create([
                    'pengguna_id' => $validateData['pengguna_id'],
                    'playstation_id' => $validateData['playstation_id'],
                    'tanggal' => $validateData['tanggal'],
                    'durasiBermain' => $validateData['durasiBermain'],
                    'jumlahOrang' => $validateData['jumlahOrang'],
                ]);
                return redirect()->route('list_transaksi')->with('success', 'Order processed via Microservice');
            }
        } catch (\Exception $e) {
            Log::error("Microservice Error: " . $e->getMessage());
        }

        // Fallback or error handling
        return redirect()->route('list_transaksi')->with('error', 'Failed to connect to Order Service');
    }

    public function edit(Transaksi $transaksi)
    {
        $user = User::all();
        $playstation = Playstation::all();
        $trans = Transaksi::Where('id', $transaksi->id)->first();
        return view('edit_transaksi', ['user' => $user, 'playstation' => $playstation, 'transEdit' => $trans]);
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validateData = $request->validate([
            'pengguna_id' => 'required|integer',
            'playstation_id' => 'required|integer',
            'tanggal' => 'required|date|date_format:Y-m-d',
            'durasiBermain' => 'required|integer',
            'jumlahOrang' => 'required|integer'
        ]);

        $transaksi->update([
            'pengguna_id' => $validateData['pengguna_id'],
            'playstation_id' => $validateData['playstation_id'],
            'tanggal' => $validateData['tanggal'],
            'durasiBermain' => $validateData['durasiBermain'],
            'jumlahOrang' => $validateData['jumlahOrang'],
        ]);

        return redirect()->route('list_transaksi');
    }

    public function read(Request $request)
    {
        $data_transaksi = Transaksi::all();
        return view('list_transaksi', compact('data_transaksi'));
    }

    public function destroy(Request $request, Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('list_transaksi');
    }
}
