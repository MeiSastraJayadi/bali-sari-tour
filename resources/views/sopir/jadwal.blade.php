@extends('sopir.layout.app')

@section('title')
Jadwal
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="jadwal-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Sopir</th>
                    <th>Tanggal</th>
                    <th>Note</th>
                    <th>Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#jadwal-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('sopir.jadwal.data') }}',
        pageLength: 4,   // 👈 limit rows per page
        lengthMenu: [[4, 10, 25, 50, -1], [4, 10, 25, 50, "All"]], // 👈 dropdown options
        columns: [
            { data: 'pelanggan', name: 'pelanggan' },
            { data: 'sopir', name: 'sopir' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'note', name: 'note' }, 
            { data: 'action', name: 'action' }
        ]
    });

    $('#jadwal-table').on('click', '.update-btn', function() {
        const id = $(this).data('id');
        $.confirm({
            title: 'Konfirmasi',
            content: 'Yakin konfirmasi untuk jalan?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "Ok!",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                        $.ajax({
                            url: "/sopir/jadwal/konfirmasi/"+id,  
                            method: "GET",
                            processData: false,  
                            contentType: false,  
                            success: function(response) {

                                $.toast({
                                    heading: 'Success',
                                    text: 'Berhasil konfirmasi',
                                    showHideTransition: 'slide',
                                    icon: 'success'
                                })
                                $('#jadwal-table').DataTable().ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                
                                $.toast({
                                    heading: 'Error',
                                    text: 'Terjadi kesalahan dalam menghapus data',
                                    showHideTransition: 'fade',
                                    icon: 'error'
                                })
                            }
                        });
                    }
                },
                cancel: {
                    text: "Batal", 
                    action: function(){
                    }
                } 
            }
        });
    })
</script>
@endsection