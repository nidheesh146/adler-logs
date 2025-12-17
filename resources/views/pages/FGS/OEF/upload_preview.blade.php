@extends('layouts.default')
@section('content')
<div class="container">
    <h1>Preview Excel Data</h1>

    <!-- Inline CSS for grey rows -->
    <style>
        .bg-light {
            background-color: #f8f9fa; /* Light grey color */
        }
    </style>

    <!-- Display the data in a table -->
    <form action="{{ route('save_oef_item') }}" method="post">
        @csrf
        <input type="hidden" name="oef_id" value="{{ $oef_id }}">
        <input type="hidden" name="data" value="{{ json_encode($data) }}">

        <table class="table">
            <thead>
                <tr>
                    <th>SKU Code</th>
                    <th>Quantity</th>
                    <th>Discount</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                <tr class="{{ $row[4] == 0 ? 'bg-light' : '' }}"> <!-- Apply 'bg-light' class if status_type is 0 -->
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td>{{ $row[2] }}</td>
                    <td>{{ $row[3] }}</td>
                    <td>{{ $row[4] }}</td> <!-- This is the status_type -->
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
