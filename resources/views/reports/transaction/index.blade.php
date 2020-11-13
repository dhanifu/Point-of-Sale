@extends('layouts.app')

@section('title', 'Manager - Transaction Report')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('manager.home')}}">Home</a></li>
        <li class="breadcrumb-item active">Transaction</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">Transaction
                                <div class="float-right">
                                    <form action="{{route('manager.transaction-report.pdf')}}" method="get" target="_blank">
                                        <input type="hidden" name="user_id" value="{{request()->get('user_id')}}">
                                        <input type="hidden" name="start_date" value="{{request()->get('start_date')}}">
                                        <input type="hidden" name="end_date" value="{{request()->get('end_date')}}">
                                        @if(!empty(request()->get('start_date')) && !empty(request()->get('end_date')) && !empty(request()->get('user_id')))
                                        <button class="btn btn-primary shadow" data-target="_blank">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-dark shadow"
                                                onclick="document.location.href='{{route('manager.transaction-report.index')}}'">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-success shadow"
                                                data-toggle="modal" data-target="#modalFilter">
                                            <i class="fa fa-filter"></i> Filter Transaksi
                                        </button>
                                </form>
                                </div>
                            </h4>
                        </div>
                        <div class="card-body">
                                <div class="col-md-3" style="margin: -20px 0 10px 0">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Pendapatan</small>
                                        <br>
                                        <strong class="h4">Rp {{number_format($total)}}</strong>
                                    </div>
                                </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%">#</th>
                                            <th>Produk</th>
                                            <th>Nama Kasir</th>
                                            <th>Email Kasir</th>
                                            <th>Tgl/waktu Beli</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$row->product->nama_barang}}</td>
                                                <td>{{$row->user->name}}</td>
                                                <td>{{$row->user->email}}</td>
                                                <td>{{$row->created_at->format('d-m-Y')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



<div class="modal fade" id="modalFilter" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modalFilterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFilterLabel">Filter Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('manager.transaction-report.index') }}" method="GET">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <label for="">Mulai Tanggal</label>
                            <input type="date" name="start_date" 
                                class="form-control {{ $errors->has('start_date') ? 'is-invalid':'' }}"
                                id="start_date"
                                value="{{ request()->get('start_date') }}">
                        </div>
                        <div class="row">
                            <label for="">Sampai Tanggal</label>
                            <input type="date" name="end_date" 
                                class="form-control {{ $errors->has('end_date') ? 'is-invalid':'' }}"
                                id="end_date"
                                value="{{ request()->get('end_date') }}">
                        </div>
                        <div class="row">
                            <label for="">Kasir</label>
                            <select name="user_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request()->get('user_id') == $user->id ? 'selected':'' }}>
                                    {{ $user->name }} - {{ $user->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
