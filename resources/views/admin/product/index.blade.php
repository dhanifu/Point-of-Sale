@extends('layouts.app')

@section('title', 'Admin - Product')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
        <li class="breadcrumb-item active">Product</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">List Product
                                <div class="float-right">
                                    <button type="button" class="btn btn-success" onclick="document.location.href='{{route('admin.product.create')}}'">
                                        Tambah produk
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
                                            <th style="width: 1%">#</th>
                                            <th style="width: 30%">Nama</th>
                                            <th style="width: 20%">Brand</th>
                                            <th style="width: 20%">Distributor</th>
                                            <th style="width: 15%">Tgl Masuk</th>
                                            <th style="width: 15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $val->nama_barang }}</strong></td>
                                            <td>{{ $val->brand->nama_brand }}</td>
                                            <td>{{ $val->distributor->nama_distributor }}</td>
                                            <td>{{ date('d-m-Y', strtotime($val->tanggal_masuk)) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalShow"
                                                        data-namabarang="{{$val->nama_barang}}" data-brand="{{$val->brand->nama_brand}}"
                                                        data-distributor="{{$val->distributor->nama_distributor}}" data-tanggal="{{date('d-m-Y', strtotime($val->tanggal_masuk))}}"
                                                        data-harga="Rp {{number_format($val->harga_barang)}}" data-stok="{{$val->stok_barang}}" data-keterangan="{{$val->keterangan}}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='{{route('admin.product.edit',$val->id)}}'">
                                                    <i class="fa fa-edit"></i>
                                                </button>
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


<div class="modal fade" id="modalShow" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modalShowLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowLabel">Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <table>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td id="nama_barang"></td>
                            </tr>
                            <tr>
                                <td>Brand</td>
                                <td>:</td>
                                <td id="brand"></td>
                            </tr>
                            <tr>
                                <td>Distributor</td>
                                <td>:</td>
                                <td id="distributor"></td>
                            </tr>
                            <tr>
                                <td style="width: 100px">Tanggal Masuk</td>
                                <td>:</td>
                                <td id="tanggal_masuk"></td>
                            </tr>
                            <tr>
                                <td>Harga Barang</td>
                                <td>:</td>
                                <td id="harga_barang"></td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td>:</td>
                                <td id="stok_barang"></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td id="keterangan"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
                            <h4 class="display-5">Apakah anda yakin ingin menghapus produk ini?</h4>
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
        $('#modalShow').on('show.bs.modal', function(e){
            $(this).find('#nama_barang').text($(e.relatedTarget).data('namabarang'));
            $(this).find('#brand').text($(e.relatedTarget).data('brand'));
            $(this).find('#distributor').text($(e.relatedTarget).data('distributor'));
            $(this).find('#tanggal_masuk').text($(e.relatedTarget).data('tanggal'));
            $(this).find('#harga_barang').text($(e.relatedTarget).data('harga'));
            $(this).find('#stok_barang').text($(e.relatedTarget).data('stok'));
            $(this).find('#keterangan').text($(e.relatedTarget).data('keterangan'));
        })

        function deleteData(product_id){
            let id = product_id;
            let url = '{{route("admin.product.destroy", ":id")}}';
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url)
        }
        function formSubmit(){
            $('#deleteForm').submit()
        }
    </script>
@endsection