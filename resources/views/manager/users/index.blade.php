@extends('layouts.app')

@section('title', 'Manager - Manage User')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('manager.home')}}">Home</a></li>
        <li class="breadcrumb-item active">User</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="card-title">List User
                                <div class="float-right">
                                    <button type="button" class="btn btn-success shadow" data-toggle="modal" data-target="#modalTambah"
                                            title="Add an User">
                                        <i class="fa fa-plus"></i>
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
                                            <th style="width: 20%">Email</th>
                                            <th style="width: 7%">Role</th>
                                            <th style="width: 15%">Dibuat pada tanggal</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $val->name }}</strong></td>
                                            <td>{{ $val->email }}</td>
                                            <td>
                                                @foreach ($val->getRoleNames() as $role)
                                                    <label for="" class="badge badge-info">{{ $role }}</label>
                                                @endforeach
                                            </td>
                                            <td>{{ $val->created_at->format('d M Y ~ H:i') }}</td>
                                            <td>
                                                <a href="{{route('manager.user.edit',$val->id)}}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
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

{{-- MODAL EDIT USER --}}
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('manager.user.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control"
                                value="{{old('name')}}">
                        <p class="text-danger">{{ $errors->first('name') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                                value="{{old('email')}}">
                        <p class="text-danger">{{ $errors->first('email') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="role" class="col-form-label">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($roles as $r)
                                <option value="{{$r->name}}">{{ucfirst($r->name)}}</option>
                            @endforeach
                        </select>
                        <p class="text-danger">{{ $errors->first('role') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <p class="text-danger">{{ $errors->first('password') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                            <h4 class="display-5">Apakah anda yakin ingin menghapus user ini?</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="formSubmit()">Ya, hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

</main>
@endsection

@section('js')
    <script>
        @if($errors->count() > 0)
            $('#modalTambah').modal('show')
        @endif

        function deleteData(user_id){
            let id = user_id;
            let url = '{{route("manager.user.destroy", ":id")}}';
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url)
        }

        function formSubmit(){
            $('#deleteForm').submit()
        }
    </script>
@endsection