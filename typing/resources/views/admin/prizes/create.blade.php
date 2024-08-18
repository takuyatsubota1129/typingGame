@extends('layouts.admin')

@section('content')
    <h1>Add Prize</h1>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.prizes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="probability">当選確率</label>
            <input type="number" id="probability" name="probability" class="form-control" value="{{ old('probability') }}" max="100" step="0.00001">
        </div>
        <div class="form-group">
            <label for="group_id">当選回数上限</label>
            <input type="number" id="win_count" name="win_count" class="form-control" value="{{ old('group_id') }}" min="0">
        </div>
        <div class="form-group">
            <label for="group_id">Group ID</label>
            <input type="number" id="group_id" name="group_id" class="form-control" value="{{ old('group_id') }}" min="1">
        </div>
        <div class="form-group">
            <label for="type_id">Type ID:</label>
            <input type="number" name="type_id" class="form-control" value="{{ old('type_id') }}" required>
        </div>
        <div class="form-group">
            <label for="sub_id">Sub ID:</label>
            <input type="number" name="sub_id" value="{{ old('sub_id') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="notes">notes:</label>
            <textarea name="notes" id="notes" value="{{ old('notes') }}" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Prize</button>
    </form>
@endsection
