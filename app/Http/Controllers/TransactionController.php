<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Brand;
use App\Distributor;
use App\Product;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
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
        // $transactions = Transaction::select('kd_transaksi')->distinct()->get();
        // $transactions = DB::select("SELECT kd_transaksi FROM transactions GROUP BY kd_transaksi (SELECT * FROM transactions)");
        // dd($transactions);
        
        $products = Product::where('stok_barang', '>', 0)->orderBy('nama_barang', 'ASC')->get();

        $con = mysqli_connect('localhost','root','','dshop');
        $sql = mysqli_query($con, 'SELECT * FROM transactions WHERE user_id = '.Auth::user()->id.' GROUP BY kd_transaksi ORDER BY created_at DESC');
        
        return view('transaction.index', compact('products', 'sql'));
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
            'product_id' => 'required|exists:products,id',
            'jumlah_beli' => 'required|integer',
            'total_harga' => 'required|integer'
        ]);

        $product = Product::find($request->product_id);
        $kode_transaksi = Str::random(25);
        $user_id = Auth::user()->id;
        $total_harga = $request->jumlah_beli * $product->harga_barang;
        $sisa_stok = $product->stok_barang - $request->jumlah_beli;
        
        Transaction::create([
            'kd_transaksi' => $kode_transaksi,
            'product_id' => $request->product_id,
            'user_id' => $user_id,
            'jumlah_beli' => $request->jumlah_beli,
            'total_harga' => $total_harga,
            'tanggal_beli' => date('Y-m-d')
        ]);
        
        $product->update([
            'stok_barang' => $sisa_stok
        ]);

        return redirect()->back()->with('success', 'transaksi berhasil');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTransaction($kode_transaksi)
    {
        $transactions = Transaction::where('kd_transaksi', $kode_transaksi)->orderBy('created_at', 'DESC')->get();
        $products = Product::orderBy('nama_barang', 'ASC')->get();

        return view('transaction.add', compact('transactions', 'products', 'kode_transaksi'));
    }
    public function storeMore(Request $request, $kode_transaksi)
    {
        $this->middleware('auth');
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'jumlah_beli' => 'required|integer',
            'total_harga' => 'required|integer'
        ]);

        $product = Product::find($request->product_id);
        $user_id = Auth::user()->id;
        $total_harga = $request->jumlah_beli * $product->harga_barang;
        $sisa_stok = $product->stok_barang - $request->jumlah_beli;
        
        Transaction::create([
            'kd_transaksi' => $kode_transaksi,
            'product_id' => $request->product_id,
            'user_id' => $user_id,
            'jumlah_beli' => $request->jumlah_beli,
            'total_harga' => $total_harga,
            'tanggal_beli' => date('Y-m-d')
        ]);
        
        $product->update([
            'stok_barang' => $sisa_stok
        ]);

        return redirect()->back()->with('success', 'transaksi berhasil');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect(route('kasir.transaction.index'))->with('success', 'Data transaksi berhasil dihapus');
    }
}
