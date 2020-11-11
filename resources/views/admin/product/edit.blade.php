@extends('layouts.app')

@section('title', 'Admin - Edit Product')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{route('admin.product.index')}}">Product</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
          
            <form action="{{ route('admin.product.update', $product->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-lg">
                            <div class="card-header">
                                <h4 class="card-title">Edit Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Produk</label>
                                    <input type="text" name="nama_barang" class="form-control" value="{{ $product->nama_barang }}">
                                    <p class="text-danger">{{ $errors->first('nama_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="7">{{ $product->keterangan }}</textarea>
                                    <p class="text-danger">{{ $errors->first('keterangan') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="brand_id">Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option>Pilih</option>
                                        @foreach ($brand as $row)
                                        <option value="{{ $row->id }}" {{ $product->brand_id == $row->id ? 'selected':'' }}>{{ $row->nama_brand }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('brand_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="distributor_id">Distributor</label>
                                    <select name="distributor_id" class="form-control">
                                        <option>Pilih</option>
                                        @foreach ($distributor as $row)
                                        <option value="{{ $row->id }}" {{ $product->distributor_id == $row->id ? 'selected':'' }}>{{ $row->nama_distributor }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('distributor_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="harga_barang">Harga</label>
                                    <input type="number" name="harga_barang" class="form-control" value="{{ $product->harga_barang }}">
                                    <p class="text-danger">{{ $errors->first('harga_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stok_barang">Stok</label>
                                    <input type="number" name="stok_barang" class="form-control" value="{{ $product->stok_barang }}">
                                    <p class="text-danger">{{ $errors->first('stok_barang') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">Update</button>
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