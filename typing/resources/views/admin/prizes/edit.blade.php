@extends('layouts.admin')

@section('content')
    <h1>Edit Prize</h1>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.prizes.update', $prize->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $prize->name) }}">
        </div>
        <div class="form-group">
            <label for="probability">当選確率</label>
            <input type="number" id="probability" name="probability" class="form-control" value="{{ old('probability', $prize->probability) }}" max="100" step="0.00001">
        </div>
        <div class="form-group">
            <label for="group_id">当選回数上限</label>
            <input type="number" id="win_count" name="win_count" class="form-control" value="{{ old('group_id', $prize->group_id) }}" min="0">
        </div>
        <div class="form-group">
            <label for="group_id">Group ID</label>
            <input type="number" id="group_id" name="group_id" class="form-control" value="{{ old('group_id', $prize->group_id) }}" min="1">
        </div>
        <div class="form-group">
            <label for="type_id">Type ID:</label>
            <input type="number" name="type_id"  value="{{ old('type_id', $prize->type_id) }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="sub_id">Sub ID:</label>
            <input type="number" name="sub_id" value="{{ old('sub_id', $prize->sub_id) }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="notes">notes:</label>
            <textarea id="notes" name="notes" value="{{ old('notes', $prize->notes) }}" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Prize</button>
    </form>
@endsection
