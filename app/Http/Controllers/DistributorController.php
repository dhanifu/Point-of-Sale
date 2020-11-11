<?php

namespace App\Http\Controllers;

use App\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributors = Distributor::orderBy('created_at', 'DESC')->get();
        return view('admin.distributor.index', compact('distributors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_distributor' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telepon' => 'required|max:15'
        ]);

        Distributor::create($request->except('_token'));

        return redirect()->back()->with('success', 'Distributor baru ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Distributor  $distributor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dist = Distributor::find($request->distributor_id);

        $this->validate($request, [
            'nama_distributor' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telepon' => 'required|max:15'
        ]);
        
        $dist->update([
            'nama_distributor' => $request->nama_distributor,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon
        ]);

        return redirect()->back()->with('success', 'Distributor berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Distributor  $distributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Distributor $distributor)
    {
        if ($distributor->with('products')->count() == 0) {
            $distributor->delete();
            return redirect()->back()->with('success', 'Distributor berhasil dihapus');
        }
        
        return redirect()->back()->with('error', 'Distributor ini memiliki produk');
    }
}
