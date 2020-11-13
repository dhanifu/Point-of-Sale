<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Auth;
use App\Brand;
use App\Distributor;
use App\Product;
use App\User;
use App\Transaction;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function countTotal($querys, $field)
    {
        $total = 0;
        if ($querys->count() > 0) {
            $sub_total = $querys->pluck($field)->all();
            $total = array_sum($sub_total);
        }

        return $total;
    }
    private function countItem($query, $field)
    {
        $total = 0;
        if ($query->count() > 0) {
            foreach ($query as $t) {
                $jml_beli = $t->pluck($field)->all();
                $total = array_sum($jml_beli);
            }
        }

        return $total;
    }
    private function countProduct($products)
    {
        $product = $products->count();
        
        return $product;
    }
    private function countKd($kd_transaksi, $user_id)
    {
        $total = Transaction::where('kd_transaksi', $kd_transaksi)
                            ->where('user_id', $user_id)->count();
        return $total;
    }


    public function indexProduct(Request $request)
    {
        $products = Product::with('brand', 'distributor');
        $brands = Brand::orderBy('nama_brand', 'ASC')->get();
        $distributors = Distributor::orderBy('nama_distributor')->get();

        // if (!empty($request->brand_id)) {
        //     $products = $products->where('brand_id', $request->brand_id);
        // }elseif (!empty($request->distributor_id)) {
        //     $products = $products->where('distributor_id', $request->distributor_id);
        // }else{
        //     $products = $products;
        // }

        if (!empty($request->brand_id) && !empty($request->distributor_id)) {
            $products = $products->where('brand_id', $request->brand_id)->where('distributor_id', $request->distributor_id);
        }elseif (!empty($request->brand_id)) {
            $products = $products->where('brand_id', $request->brand_id);
        }elseif (!empty($request->distributor_id)) {
            $products = $products->where('distributor_id', $request->distributor_id);
        }else{
            $products = $products;
        }

        if ($request->urutan == "terbanyak") {
            $products = $products->orderBy('stok_barang', "DESC");
        } elseif ($request->urutan = "tersedikit") {
            $products = $products->orderBy('stok_barang', "ASC");
        }else {
            $products = $products;
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $this->validate($request, [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date'
            ]);

            $start_date = Carbon::parse($request->start_date)->format('Y-m-d').' 00:00:01';
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d').' 23:59:59';

            $products = $products->whereBetween('created_at', [$start_date,$end_date])->get();
        } else {
            $products = $products->orderBy('created_at','DESC')->get();
        }
        
        return view('reports.product.index', [
            'products'=>$products,
            'brands'=>$brands,
            'distributors'=>$distributors,
            'totalItem'=>$this->countProduct($products)
        ]);
    }

    public function pdfProduct(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d').' 00:00:01';
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d').' 23:59:59';
        if(!empty($request->urutan) && $request->urutan == "terbanyak"){
            $urutan = "DESC";
        } elseif (!empty($request->urutan) && $request->urutan == "tersedikit") {
            $urutan = "ASC";
        }else{
            $urutan = "ASC";
        }

        if(!empty($request->brand_id) && !empty($request->distributor_id)){
            $product = Product::with('brand','distributor')->whereBetween('created_at', [$start_date,$end_date])
                            ->where('brand_id', $request->brand_id)->where('distributor_id',$request->distributor_id)
                            ->orderBy('stok_barang', $urutan)->get();
            $brand = Brand::where('id',$request->brand_id)->first();
            $distributor = Distributor::where('id',$request->distributor_id)->first();
        }elseif (!empty($request->brand_id)) {
            $product = Product::with('brand','distributor')->whereBetween('created_at', [$start_date,$end_date])
                            ->where('brand_id', $request->brand_id)
                            ->orderBy('stok_barang', $urutan)->get();
            $brand = Brand::where('id',$request->brand_id)->first();
            $distributor = "-";
        }elseif (!empty($request->distributor_id)) {
            $product = Product::with('brand','distributor')->whereBetween('created_at', [$start_date,$end_date])
                            ->where('distributor_id',$request->distributor_id)
                            ->orderBy('stok_barang', $urutan)->get();
            $brand = "-";
            $distributor = Distributor::where('id',$request->distributor_id)->first();
        }else{
            $product = Product::with('brand','distributor')->whereBetween('created_at', [$start_date,$end_date])
                            ->orderBy('stok_barang', $urutan)->get();
            $brand = "-";
            $distributor = "-";
        }

        // dd($product, $brand, $distributor);
        // return view('reports.product.pdf', [
        //     'product' => $product,
        //     'start_date' => $request->start_date,
        //     'end_date' => $request->end_date,
        //     'brand' => $brand,
        //     'distributor' => $distributor,
        // ]);
        $pdf = PDF::setOptions(['dpi'=>150, 'defaultFont'=>'sans-serif'])
                ->loadView('reports.product.pdf', [
                    'product' => $product,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'brand' => $brand,
                    'distributor' => $distributor,
                ]);
        return $pdf->stream();
    }





    // Transaction Report
    public function indexTransaction(Request $request)
    {
        $users = User::role('kasir')->orderBy('name', 'ASC')->get();
        $transactions = Transaction::orderBy('created_at', 'DESC')->with('product');

        if (!empty($request->user_id)) {
            $transactions = $transactions->where('user_id', $request->user_id);
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $this->validate($request, [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date'
            ]);

            $start_date = Carbon::parse($request->start_date)->format('Y-m-d').' 00:00:01';
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d').' 23:59:59';

            $transactions = $transactions->whereBetween('created_at', [$start_date, $end_date])->get();
        } else {
            $transactions = $transactions->take(10)->skip(0)->get();
        }
        
        return view('reports.transaction.index', [
            'users' => $users,
            'transactions' => $transactions,
            'total' => $this->countTotal($transactions, 'total_harga')
        ]);
    }

    public function pdfTransaction(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d').' 00:00:01';
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d').' 23:59:59';
        $user_id = $request->user_id;

        $transaction = Transaction::whereBetween('created_at', [$start_date,$end_date])->where('user_id', $user_id)
                                ->with('user', 'product')->get();
        $user = User::find($user_id);
        
        $pdf = PDF::setOptions(['dpi'=>150, 'defaultFont'=>'sans-serif'])
                ->loadView('reports.transaction.pdf', [
                        'transaction'=>$transaction,
                        'user'=>$user,
                        'start_date'=>$request->start_date,
                        'end_date'=>$request->end_date
                    ]);
        return $pdf->stream();
    }
}
