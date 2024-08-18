@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <p>Welcome to your dashboard!</p>
                    <a href="{{ route('stage') }}" class="btn btn-primary">ステージ選択</a>
                    <a href="{{ route('instantwin.form') }}" class="btn btn-success">ガチャ</a>
                    <a href="{{ route('preparation') }}" class="btn btn-info">装備画面</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
