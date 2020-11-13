@extends('layouts.app')

@section('title', 'Manager - Product Report')

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
                                    <form action="{{route('manager.product-report.pdf')}}" method="get" target="_blank">
                                        <input type="hidden" name="urutan" value="{{request()->get('urutan')}}">
                                        <input type="hidden" name="brand_id" value="{{request()->get('brand_id')}}">
                                        <input type="hidden" name="distributor_id" value="{{request()->get('distributor_id')}}">
                                        <input type="hidden" name="start_date" value="{{request()->get('start_date')}}">
                                        <input type="hidden" name="end_date" value="{{request()->get('end_date')}}">
                                        @if(!empty(request()->get('start_date')) && !empty(request()->get('end_date')))
                                        <button class="btn btn-primary shadow">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-dark shadow"
                                                onclick="document.location.href='{{route('manager.product-report.index')}}'">
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
                                        <small class="text-muted">Total Produk</small>
                                        <br>
                                        <strong class="h4">{{$totalItem}}</strong>
                                    </div>
                                </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%">#</th>
                                            <th>Produk</th>
                                            <th>Brand</th>
                                            <th>Distributor</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Tgl ditambah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$row->nama_barang}}</td>
                                                <td>{{$row->brand->nama_brand}}</td>
                                                <td>{{$row->distributor->nama_distributor}}</td>
                                                <td>{{$row->harga_barang}}</td>
                                                <td>{{$row->stok_barang}}</td>
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
            <form action="{{ route('manager.product-report.index') }}" method="GET">
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
                            <label for="urutan">Urutan Stok</label>
                            <select name="urutan" id="urutan" class="form-control">
                                <option value="">Pilih</option>
                                <option value="tersedikit" {{request()->get('urutan')=="tersedikit"?'selected':''}}>Tersedikit</option>
                                <option value="terbanyak" {{request()->get('urutan')=="terbanyak"?'selected':''}}>Terbanyak</option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="brand_id">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ request()->get('brand_id') == $brand->id ? 'selected':'' }}>
                                    {{ $brand->nama_brand }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <label for="distributor_id">Distributor</label>
                            <select name="distributor_id" id="distributor_id" class="form-control">
                                <option value="">Pilih</option>
                                @foreach ($distributors as $dist)
                                <option value="{{ $dist->id }}"
                                    {{ request()->get('distributor_id') == $dist->id ? 'selected':'' }}>
                                    {{ $dist->nama_distributor }}
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