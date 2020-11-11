@extends('layouts.app')

@section('title', 'Admin - Distributor')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
        <li class="breadcrumb-item active">Distributor</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                
                <div class="col-md-4">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">Distributor Baru</h4>
                        </div>
                        <div class="card-body">
                          
                            <form action="{{ route('admin.distributor.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_distributor" class="col-form-label">Nama Distributor:</label>
                                    <input type="text" name="nama_distributor" id="nama_distributor" class="form-control"
                                            value="{{old('nama_distributor')}}" required>
                                    <p class="text-danger" id="error">{{ $errors->first('nama_distributor') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon" class="col-form-label">No.Telepon:</label>
                                    <input type="number" name="no_telepon" id="no_telepon" class="form-control"
                                            value="{{old('no_telepon')}}" required>
                                    <p class="text-danger" id="error">{{ $errors->first('no_telepon') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="col-form-label">Alamat:</label>
                                    <textarea name="alamat" id="alamat" class="form-control" required>{{old('alamat')}}</textarea>
                                    <p class="text-danger" id="error">{{ $errors->first('alamat') }}</p>
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
                            <h4 class="card-title">List Distributor</h4>
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
                                            <th style="width: 25%">Nama Distributor</th>
                                            <th>Alamat</th>
                                            <th style="width: 10%">No.Telepon</th>
                                            <th style="width: 17%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($distributors as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $val->nama_distributor }}</strong></td>
                                            <td>{{ $val->alamat }}</td>
                                            <td>{{ $val->no_telepon }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit"
                                                        data-id="{{$val->id}}" data-nama="{{$val->nama_distributor}}"
                                                        data-alamat="{{$val->alamat}}" data-telepon="{{$val->no_telepon}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$val->id}})" 
                                                    data-target="#modalDelete" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
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



{{-- MODAL EDIT DISTRIBUTOR --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Distributor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.distributor.update') }}" method="post" id="editDist">
                <input type="hidden" name="distributor_id" id="distributor_id">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_distributor_edit" class="col-form-label">Nama Distributor:</label>
                        <input type="text" name="nama_distributor" id="nama_distributor_edit" class="form-control"
                                value="{{old('nama_distributor')}}" required>
                        <p class="text-danger">{{ $errors->first('nama_distributor') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon_edit" class="col-form-label">No.Telepon:</label>
                        <input type="number" name="no_telepon" id="no_telepon_edit" class="form-control"
                                value="{{old('no_telepon')}}" required>
                        <p class="text-danger">{{ $errors->first('no_telepon') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="alamat_edit" class="col-form-label">Alamat:</label>
                        <textarea name="alamat" id="alamat_edit" class="form-control" required>{{old('alamat')}}</textarea>
                        <p class="text-danger">{{ $errors->first('alamat') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Submit</button>
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
                            <h4 class="display-5">Apakah anda yakin ingin menghapus distributor ini?</h4>
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
            $('.container-fluid #error').hide()
        @endif
        
        $('#modalEdit').on('show.bs.modal', function(event){
            let button = $(event.relatedTarget)
                brand_id = button.data('id')
                nama_distributor = button.data('nama')
                alamat = button.data('alamat')
                no_telepon = button.data('telepon')
                modal = $(this)
            
            modal.find('#editDist #distributor_id').val(brand_id)
            modal.find('.modal-body #nama_distributor_edit').val(nama_distributor)
            modal.find('.modal-body #alamat_edit').val(alamat)
            modal.find('.modal-body #no_telepon_edit').val(no_telepon)
        });

        function deleteData(product_id){
            let id = product_id;
            let url = '{{route("admin.distributor.destroy", ":id")}}';
            url = url.replace(':id', id);
            console.log(url)
            $('#deleteForm').attr('action', url)
        }
        function formSubmit(){
            $('#deleteForm').submit()
        }
    </script>
@endsection