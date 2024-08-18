@extends('layouts.admin')

@section('content')
    <h1>Prizes</h1>
    <a href="{{ route('admin.prizes.create') }}">Add Prize</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>当選確率</th>
                <th>当選回数上限</th>
                <th>Group ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prizes as $prize)
                <tr>
                    <td>{{ $prize->name }}</td>
                    <td>{{ $prize->probability }}%</td>
                    <td>{{ $prize->win_count }}回</td>
                    <td>{{ $prize->group_id }}</td>
                    <td>
                        <a href="{{ route('admin.prizes.edit', $prize->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.prizes.destroy', $prize->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
