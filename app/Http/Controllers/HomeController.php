<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Transaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function countTotal($transactions)
    {
        $total = 0;
        if ($transactions->count() > 0) {
            $sub_total = $transactions->pluck('total_harga')->all();
            $total = array_sum($sub_total);
        }

        return $total;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $id = Auth::user()->id;
        $transaction = Transaction::orderBy('created_at', 'DESC')->get();
        if (Auth::user()->hasRole('kasir')) {
            $transHari = Transaction::where('tanggal_beli',date('Y-m-d'))->where('user_id',$id)->count();
            $omsetHari = Transaction::where('tanggal_beli',date('Y-m-d'))->where('user_id',$id)->sum('total_harga');
            // dd();
            return view('home', compact('transHari', 'omsetHari'));
        }elseif (Auth::user()->hasRole('admin')) {
            # code...
        }elseif (Auth::user()->hasRole('manager')) {
            return view('home', ['total'=>$this->countTotal($transaction)]);
        }
        return view('home');
    }
}
