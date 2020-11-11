@extends('layouts.app')

@section('active-dashboard', 'active')

@role('manager')
    @section('title', 'Manager - Dashboard')
@elserole('admin')
    @section('title', 'Admin - Dashboard')
@elserole('kasir')
    @section('title', 'Kasir - Dashboard')
@endrole

@section('content')
<main class="main">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    @role('manager')
        @include('module-dashboard.manager')
    @elserole('admin')
        @include('module-dashboard.admin')
    @elserole('kasir')
        @include('module-dashboard.kasir')
    @endrole
</main>
@endsection