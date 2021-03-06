@extends('layouts.app')

@section('title', 'Kasir - Add Transaction')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('kasir.home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{route('kasir.transaction.index')}}">Transaction</a></li>
        <li class="breadcrumb-item active">Add more Transaction ({{$kode_transaksi}})</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <form action="{{route('kasir.transaction.store-more', $kode_transaksi)}}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="product_id">Produk</label>
                                        <select name="product_id" id="product_id" class="form-control">
                                            <option data-harga="kosong">Pilih</option>
                                            @foreach ($products as $item)
                                                <option value="{{$item->id}}" data-harga="{{$item->harga_barang}}"
                                                    data-stok="{{$item->stok_barang}}"
                                                    {{ old('product_id') == $item->id ? 'selected':'' }}>
                                                    {{$item->nama_barang}} (stok: {{$item->stok_barang}})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">{{ $errors->first('product_id') }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="jumlah_beli">Jumlah Beli</label>
                                        <input type="number" name="jumlah_beli" class="form-control" id="jumlah_beli" placeholder="Jumlah Beli"
                                                value="{{old('jumlah_beli')}}">
                                        <p class="text-danger">{{ $errors->first('jumlah_beli') }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="total_harga">Total Harga</label>
                                        <input type="number" name="total_harga" id="total_harga" class="form-control" readonly>
                                        <p class="text-danger">{{ $errors->first('total_harga') }}</p>
                                    </div>
                                </div>
                                <button class="btn btn-primary" id="sumbit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">List transaksi kode: {{$kode_transaksi}}</h4>
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
                                            <th style="width: 1px">#</th>
                                            <th>Produk</th>
                                            <th>Kasir</th>
                                            <th>Jumlah Beli</th>
                                            <th>Total Harga</th>
                                            <th>Tgl beli</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $val)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$val->product->nama_barang}}</td>
                                                <td>{{$val->user->name}}</td>
                                                <td>{{$val->jumlah_beli}}</td>
                                                <td>Rp {{number_format($val->total_harga)}}</td>
                                                <td>{{$val->created_at->format('d-m-Y ~ H:i')}}</td>
                                                <td>
                                                    <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$val->id}})" 
                                                        data-target="#modalDelete" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
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


{{-- MODAL DELETE --}}
<div class="modal fade" id="modalDelete" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <h4 class="display-5">Apakah anda yakin ingin menghapus ini?</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="formSubmit()">Ya, hapus</button>
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
                stok = $(this).find(':selected').data('stok')
            
            $('#jumlah_beli').keyup(function(){
                let jumlah_beli = $('#jumlah_beli').val()
                let sisa_stok = parseInt(stok) - parseInt(jumlah_beli)
                let total = parseInt(harga) * parseInt(jumlah_beli)

                if (sisa_stok < 0) {
                    $('#sumbit').hide()
                    alert('Stok tidak mencukupi, hanya bisa membeli <= '+stok+" produk")
                    let total = ""
                }else{
                    $('#sumbit').show()
                }

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

        function deleteData(transaction_id){
            let id = transaction_id;
            let url = '{{route("kasir.transaction.destroy", ":id")}}';
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url)
        }
        function formSubmit(){
            $('#deleteForm').submit()
        }
    </script>
@endsection