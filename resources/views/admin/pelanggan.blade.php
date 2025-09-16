@extends('admin.layout.app')

@section('title')
Pelanggan
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="pelanggan-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#pelanggan-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.pelanggan.data') }}',
        pageLength: 4,   // 👈 limit rows per page
        lengthMenu: [[4, 10, 25, 50, -1], [4, 10, 25, 50, "All"]], // 👈 dropdown options
        columns: [
            { data: 'id', name: 'id' },
            { data: 'nama_lengkap', name: 'nama_lengkap' },
            { data: 'email', name: 'email' },
            { data: 'telepon', name: 'telepon' }
        ]
    });
</script>
@endsection