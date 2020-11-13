<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
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
        $brands = Brand::orderBy('created_at', 'DESC')->get();
        return view('admin.brand.index', compact('brands'));
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
            'nama_brand' => 'required|string|max:50|unique:brands'
        ]);

        Brand::create($request->except('_token'));

        return redirect()->back()->with('success', 'Brand baru ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $brand = Brand::find($request->brand_id);
        
        $this->validate($request, [
            'nama_brand' => 'required|string|max:50|unique:brands,nama_brand,' . $brand->id
        ]);
        
        $brand->update([
            'nama_brand' => $request->nama_brand,
        ]);

        return redirect()->back()->with('success', 'Nama Brand diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $product = Product::join('brands', 'products.brand_id','=','brands.id')->count();
        if ($product == 0) {
            $brand->delete();
            return redirect()->back()->with('success', 'Berhasil dihapus...');
        }
        return redirect()->back()->with('error', 'Brand ini memiliki produk...');
    }
}
