<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\Distributor;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $products = Product::orderBy('created_at', 'ASC')->get();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::orderBy('nama_brand', 'ASC')->get();
        $distributor = Distributor::orderBy('nama_distributor', 'ASC')->get();
        return view('admin.product.create', compact('brand', 'distributor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->middleware('auth');
        $this->validate($request, [
            'nama_barang' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'distributor_id' => 'required|exists:distributors,id',
            'tanggal_masuk' => 'required|date',
            'harga_barang' => 'required|integer',
            'stok_barang' => 'required|integer',
            'keterangan' => 'required|string'
        ]);

        Product::create([
            'nama_barang' => $request->nama_barang,
            'brand_id' => $request->brand_id,
            'distributor_id' => $request->distributor_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'harga_barang' => $request->harga_barang,
            'stok_barang' => $request->stok_barang,
            'keterangan' => $request->keterangan,
        ]);

        return redirect(route('admin.product.index'))->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $brand = Brand::orderBy('nama_brand', 'ASC')->get();
        $distributor = Distributor::orderBy('nama_distributor', 'ASC')->get();
        return view('admin.product.edit', compact('product', 'brand', 'distributor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->middleware('auth');
        $this->validate($request, [
            'nama_barang' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'distributor_id' => 'required|exists:distributors,id',
            'harga_barang' => 'required|integer',
            'stok_barang' => 'required|integer',
            'keterangan' => 'required|string'
        ]);
        
        $product->update([
            'nama_barang' => $request->nama_barang,
            'brand_id' => $request->brand_id,
            'distributor_id' => $request->distributor_id,
            'harga_barang' => $request->harga_barang,
            'stok_barang' => $request->stok_barang,
            'keterangan' => $request->keterangan,
        ]);

        return redirect(route('admin.product.index'))->with('sucess', 'Produk berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }
}
