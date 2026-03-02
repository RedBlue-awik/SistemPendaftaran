@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold">Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
    @if(auth()->user()->role === 'admin')
        <p><a href="{{ route('admin.users.index') }}">Manage Users</a></p>
    @endif
</div>
@endsection
