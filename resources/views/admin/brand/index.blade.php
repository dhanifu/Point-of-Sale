@extends('layouts.app')

@section('title', 'Admin - Brand')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
        <li class="breadcrumb-item active">Merek</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
              	
                <div class="col-md-4">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">Merek Baru</h4>
                        </div>
                        <div class="card-body">
                          
                            <form action="{{ route('admin.brand.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_brand">Nama Merek</label>
                                    <input type="text" name="nama_brand" id="nama_brand" class="form-control" value="{{old('nama_brand')}}" required>
                                    <p class="text-danger" id="nama_brand_error">{{ $errors->first('nama_brand') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">Tambah</button>
                                </div>
                            </form>
                          
                        </div>
                    </div>
                </div>
              
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">List Merek</h4>
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
                                            <th>#</th>
                                            <th>Merek</th>
                                            <th>Created At</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brands as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $val->nama_brand }}</strong></td>
                                            <td>{{ $val->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit"
                                                        data-id="{{$val->id}}" data-namabrand="{{$val->nama_brand}}">
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





{{-- MODAL --}}
<div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.brand.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="brand_id" id="brand_id">
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <label for="edit_nama_brand" class="col-md-3 col-form-label">Nama Merek</label>
                            <div class="col-md-9">
                                <input id="edit_nama_brand" type="text" class="form-control"
                                    name="nama_brand" value="{{ old('nama_brand') }}" required>
                                <p class="text-danger">{{ $errors->first('nama_brand') }}</p>
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
                            <h4 class="display-5">Apakah anda yakin ingin menghapus brand ini?</h4>
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
        @if(count($errors) > 0)
            $('#modalEdit').modal('show');
            $('.container-fluid #nama_brand_error').hide()
        @endif
        
        $('#modalEdit').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let brand_id = button.data('id')
                nama_brand = button.data('namabrand');
            let modal = $(this)

            modal.find('.modal-body #brand_id').val(brand_id)
            modal.find('.modal-body #nama_brand').val(nama_brand)
        })

        function deleteData(product_id){
            let id = product_id;
            let url = '{{route("admin.brand.destroy", ":id")}}';
            url = url.replace(':id', id);
            console.log(url)
            $('#deleteForm').attr('action', url)
        }
        function formSubmit(){
            $('#deleteForm').submit()
        }
    </script>
@endsection