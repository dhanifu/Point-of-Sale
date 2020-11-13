@extends('layouts.app')

@section('title', 'Kasir - Transaction')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('kasir.home')}}">Home</a></li>
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
                                    <button type="button" class="btn btn-success shadow"
                                            data-toggle="modal" data-target="#modalBuat">
                                        Buat transaksi baru
                                    </button>
                                </div>
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%">#</th>
                                            <th>Transaksi</th>
                                            <th>Kasir</th>
                                            <th>Tgl/waktu Beli</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @while ($r = mysqli_fetch_assoc($sql))
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$r['kd_transaksi']}}</td>
                                            <td>{{Auth::user()->name}}</td>
                                            <td>{{$r['tanggal_beli']}}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" title="Beli produk lainnya"
                                                        onclick="document.location.href='{{route('kasir.transaction.add', $r['kd_transaksi'])}}'">
                                                    <i class="fa fa-plus"></i> More
                                                </button>
                                            </td>
                                        </tr>
                                        @endwhile
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



{{-- MODAL BUAT --}}
<div class="modal fade" id="modalBuat" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modalBuatLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuatLabel">Buat Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kasir.transaction.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="brand_id" id="brand_id">
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <label for="product_id" class="col-md-3 col-form-label">Nama Produk</label>
                            <div class="col-md-9">
                                <select name="product_id" class="form-control" id="product_id">
                                    <option data-harga="kosong">Pilih</option>
                                    @foreach ($products as $item)
                                        <option value="{{$item->id}}" data-harga="{{$item->harga_barang}}"
                                            {{ old('product_id') == $item->id ? 'selected':'' }}>
                                            {{$item->nama_barang}}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-danger">{{ $errors->first('nama_brand') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="jumlah_beli" class="col-md-3 col-form-label">Jumlah Beli</label>
                            <div class="col-md-9">
                                <input type="number" name="jumlah_beli" id="jumlah_beli" class="form-control"
                                        value="{{old('jumlah_beli')}}">
                                <p class="text-danger">{{ $errors->first('jumlah_beli') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="total_harga" class="col-md-3 col-form-label">Total Harga</label>
                            <div class="col-md-9">
                                <input type="number" name="total_harga" id="total_harga" class="form-control" readonly>
                                <p class="text-danger">{{ $errors->first('total_harga') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $('select').change(function(){
            let harga = $(this).find(':selected').data('harga')
            
            $('#jumlah_beli').keyup(function(){
                let jumlah_beli = $('#jumlah_beli').val()
                let total = parseInt(harga) * parseInt(jumlah_beli)

                if (harga == "kosong") {
                    total = ""
                }

                if (jumlah_beli == "") {
                    total = ""
                }

                if(!isNaN(total)){
                    $('#total_harga').val(total)
                }
            })
        })
    </script>
@endsection