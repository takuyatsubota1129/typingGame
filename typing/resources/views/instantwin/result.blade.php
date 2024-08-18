@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Instant Win Result</h1>
    @if(!empty($results))
        @foreach($results as $result)
            @php
                $prize = $result['prize'];
                $winnerName = $result['winner_name'];
                $probability = $prize->probability;
                $alertClass = '';
                if ($probability >= 75) {
                    $alertClass = 'alert-success';
                } elseif ($probability >= 50) {
                    $alertClass = 'alert-info';
                } elseif ($probability >= 25) {
                    $alertClass = 'alert-warning';
                } else {
                    $alertClass = 'alert-danger';
                }
            @endphp
            <div class="alert {{ $alertClass }}">
                <p>Congratulations! You won: {{ $prize->name }} ({{ $probability }}%)</p>
                <p>Winner: {{ $winnerName['last_name'] }} {{ $winnerName['first_name'] }} ({{ $winnerName['gender'] }})</p>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            No prize won.
        </div>
    @endif
    <a href="{{ route('instantwin.form') }}" class="btn btn-primary">Try Again</a>
    <form action="{{ route('instantwin.selectTen') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-secondary">Try 10 Times</button>
    </form>
</div>
@endsection
