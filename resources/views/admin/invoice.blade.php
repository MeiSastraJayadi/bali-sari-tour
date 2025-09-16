@extends('admin.layout.app')

@section('title')
Invoice
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="invoice-table" class="table table-bordered table-striped">
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

<!-- Modal -->
<div class="modal fade" id="formGenerateInvoice" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
  
        <form id="formInvoice" enctype="multipart/form-data">
          <!-- Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Generate Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <!-- Body -->
          <div class="modal-body">
            <input type="hidden" id="kodeReservasi">
            <div class="form-group">
              <label for="nama_pelanggan">Nama Pelanggan</label>
              <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukan nama pelanggan" required disabled>
            </div>

            <div class="form-group">
                <label for="destinasi_update">Destinasi</label>
                <input type="text" class="form-control" id="destinasi_update" name="destinasi_update" placeholder="Masukkan destinasi" required disabled>
            </div>

            <div class="form-group">
                <label for="fee_update">Fee / Biaya</label>
                <input type="number" class="form-control" id="fee_update" name="fee_update" placeholder="Masukkan besaran fee" required>
            </div>


            <div class="form-group">
                <label for="note_update">Note</label>
                <textarea name="note_update" id="note_update" class="form-control"  cols="30" rows="10" disabled></textarea>
            </div>

          </div>
  
          <!-- Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Generate</button>
          </div>
        </form>
  
      </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#invoice-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.invoice.data') }}',
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

    $('#invoice-table').on('click', '.generate-btn', async function () {
        const id = $(this).data('id'); 
        const responseData = await fetch('/admin/invoice/detail/'+id); 
        const response = await responseData.json();
        const data = response.data;
        const reservasi = data.reservasi; 
        const kode_reservasi = data.kode_reservasi; 
        
        $('#kodeReservasi').val(kode_reservasi.kode);
        $('#nama_pelanggan').val(reservasi.nama);
        $('#destinasi_update').val(reservasi.destinasi);
        $('#fee_update').val(reservasi.biaya);
        $('#note_update').val(reservasi.note);

        $('#formGenerateInvoice').modal('show');
        
    })

    $("#formInvoice").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this); 

        const id = $('#kodeReservasi').val();

        $.ajax({
            url: '/admin/invoice/generate-invoice/'+id,  
            method: "POST",
            data: formData,
            processData: false,  
            contentType: false,  
            success: function(response) {
                $("#formGenerateInvoice").modal("hide");

                $('#invoice-table').DataTable().ajax.reload(null, false);

                $.toast({
                    heading: 'Success',
                    text: 'Berhasil generate invoice',
                    showHideTransition: 'slide',
                    icon: 'success'
                })
            },
            error: function(xhr) {
                
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan dalam generate invoice',
                    showHideTransition: 'fade',
                    icon: 'error'
                })
            }
        });
    });

</script>
@endsection