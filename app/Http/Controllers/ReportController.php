<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Auth;
use App\User;
use App\Transaction;

class ReportController extends Controller
{
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
    private function countItem($transaction)
    {
        $total = 0;
        if ($transaction->count() > 0) {
            foreach ($transaction as $t) {
                $jml_beli = $t->pluck('jumlah_beli')->all();
                $total = array_sum($jml_beli);
            }
        }
        dd($total);

        return $total;
    }
    private function countKd($kd_transaksi, $user_id)
    {
        $total = Transaction::where('kd_transaksi', $kd_transaksi)
                            ->where('user_id', $user_id)->count();
        return $total;
    }


    public function indexProduct()
    {
        return view('reports.product.index');
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
            'total' => $this->countTotal($transactions)
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
