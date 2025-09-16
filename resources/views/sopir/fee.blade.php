@extends('sopir.layout.app')

@section('title')
Fee
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="fee-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Penjemputan</th>
                    <th>Destinasi</th>
                    <th>Tanggal</th>
                    <th>Fee</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#fee-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('sopir.fee.data') }}',
        pageLength: 4,   // 👈 limit rows per page
        lengthMenu: [[4, 10, 25, 50, -1], [4, 10, 25, 50, "All"]], // 👈 dropdown options
        columns: [
            { data: 'pelanggan', name: 'pelanggan' },
            { data: 'alamat', name: 'alamat' },
            { data: 'destinasi', name: 'destinasi' },
            { data: 'tanggal', name: 'tanggal' }, 
            { data: 'biaya', name: 'biaya' }
        ]
    });
</script>
@endsection