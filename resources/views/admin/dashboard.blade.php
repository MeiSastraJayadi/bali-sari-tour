@extends('admin.layout.app')

@section('content')
    <div class="row">
        @include('admin.components.blue-card', ["amount" => $total_pelanggan, "title" => "Pelanggan"])
        @include('admin.components.green-card', ["amount" => $revenue, "title" => "Revenue"])
        @include('admin.components.yellow-card', ["amount" => $total_registrasi, "title" => "Registrasi bulan ini"])
        @include('admin.components.red-card', ["amount" => $total_sopir, "title" => "Sopir"])
    </div>
    <div class="row">
        @include('admin.components.graph', ["id" => "chartRegistrasi", "data" => $total_registrasi_pelanggan])
        @include('admin.components.graph2', ["id" => "chartRevenue", "data" => $total_revenue])
    </div>
    <div class="row">
        @include('admin.components.calendar')
    </div>
@endsection