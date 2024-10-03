@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Instant Win</h1>
    <form action="{{ route('instantwin.select') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary">ガチャを引く</button>
    </form>
    <form action="{{ route('instantwin.selectTen') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-secondary">10連実行</button>
    </form>
</div>
@endsection
