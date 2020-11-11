@extends('layouts.app')

@section('title', 'Admin - Add Product')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{route('admin.product.index')}}">Product</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
          
            <form action="{{ route('admin.product.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-lg">
                            <div class="card-header">
                                <h4 class="card-title">Tambah Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Produk</label>
                                    <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" >
                                    <p class="text-danger">{{ $errors->first('nama_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="7">{{ old('keterangan') }}</textarea>
                                    <p class="text-danger">{{ $errors->first('keterangan') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="brand_id">Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option>Pilih</option>
                                        @foreach ($brand as $row)
                                        <option value="{{ $row->id }}" {{ old('brand_id') == $row->id ? 'selected':'' }}>{{ $row->nama_brand }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('brand_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="distributor_id">Distributor</label>
                                    <select name="distributor_id" id="distributor_id" class="form-control">
                                        <option>Pilih</option>
                                        @foreach ($distributor as $row)
                                        <option value="{{ $row->id }}" {{ old('distributor_id') == $row->id ? 'selected':'' }}>{{ $row->nama_distributor }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('distributor_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_masuk">Tanggal Masuk</label>
                                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk') }}" >
                                    <p class="text-danger">{{ $errors->first('tanggal_masuk') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="harga_barang">Harga</label>
                                    <input type="number" name="harga_barang" class="form-control" value="{{ old('harga_barang') }}" >
                                    <p class="text-danger">{{ $errors->first('harga_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stok_barang">Stok Barang</label>
                                    <input type="number" name="stok_barang" class="form-control" value="{{ old('stok_barang') }}" >
                                    <p class="text-danger">{{ $errors->first('stok_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection