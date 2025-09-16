@extends('admin.layout.app')

@section('content')
    <div class="row">
        @include('admin.components.blue-card', ["amount" => "30", "title" => "Pelanggan"])
        @include('admin.components.green-card', ["amount" => "+53%", "title" => "Revenue"])
        @include('admin.components.yellow-card', ["amount" => "44", "title" => "Registrasi bulan ini"])
        @include('admin.components.red-card', ["amount" => "20", "title" => "Sopir"])
    </div>
    <div class="row">
        @include('admin.components.graph', ["id" => "chartReservasi"])
        @include('admin.components.graph', ["id" => "chartRevenue"])
    </div>
    <div class="row">
        @include('admin.components.calendar')
    </div>
@endsection