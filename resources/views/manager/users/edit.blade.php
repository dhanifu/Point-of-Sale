@extends('layouts.app')

@section('title', 'Manager - Add User')

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="{{route('manager.home')}}">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{route('manager.user.index')}}">User</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <form action="{{route('manager.user.update',$user->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control shadow-sm" value="{{ $user->name }}">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control shadow-sm" value="{{$user->email}}">
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control shadow-sm">
                                        <option value="">Pilih</option>
                                        @foreach ($roles as $role)
                                            <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('role') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="password">password</label>
                                    <input type="password" name="password" id="password" class="form-control shadow-sm">
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                </div>
                                <button class="btn btn-primary shadow">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection