@extends('admin.layout.app')

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

<!-- Modal -->
<div class="modal fade" id="formModalUpdate" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
  
        <form id="formDataUpdate" enctype="multipart/form-data">
          <!-- Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Update Data Sopir</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <!-- Body -->
          <div class="modal-body">
            <input type="hidden" id="idJadwal">
            <div class="form-group">
              <label for="nama_pelanggan">Nama Pelanggan</label>
              <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukan nama pelanggan" required disabled>
            </div>

            <div class="form-group">
                <label for="alamat_update">Alamat</label>
                <input type="text" class="form-control" id="alamat_update" name="alamat_update" placeholder="Masukkan alamat" required disabled>
            </div>

            <div class="form-group">
                <label for="destinasi_update">Destinasi</label>
                <input type="text" class="form-control" id="destinasi_update" name="destinasi_update" placeholder="Masukkan destinasi" required>
            </div>

            <div class="form-group">
                <label for="pax_update">Jumlah Orang</label>
                <input type="number" class="form-control" id="pax_update" name="pax_update" placeholder="Masukkan jumlah orang" required>
            </div>

            <div class="form-group">
                <label for="fee_update">Fee / Biaya</label>
                <input type="number" class="form-control" id="fee_update" name="fee_update" placeholder="Masukkan besaran fee" required>
            </div>

            <div class="form-group">
                <label for="mobil">Sopir</label>
                <select class="form-control" id="mobil" name="mobil">
                    @foreach ($mobil as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_mobil }} - {{ $item->owner->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="email_update">Email</label>
                    <input type="email" class="form-control" id="email_update" name="email_update" placeholder="Masukkan email" required disabled>
                </div>
    
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="telepon_update">Telepon</label>
                    <input type="text" class="form-control" id="telepon_update" name="telepon_update" placeholder="Masukkan no telepon/whatsapp" required disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="note_update">Note</label>
                <textarea name="note_update" id="note_update" class="form-control"  cols="30" rows="10" disabled></textarea>
            </div>

          </div>
  
          <!-- Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
  
      </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#jadwal-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.jadwal.data') }}',
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

    $('#jadwal-table').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        $.confirm({
            title: 'Hapus data',
            content: 'Yakin ingin menghapus jadwal?',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Ok!",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                        $.ajax({
                            url: "/admin/jadwal/hapus/"+id,  
                            method: "GET",
                            processData: false,  
                            contentType: false,  
                            success: function(response) {

                                $.toast({
                                    heading: 'Success',
                                    text: 'Berhasil menghapus data',
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

    $('#jadwal-table').on('click', '.edit-btn', async function () {
        const id = $(this).data('id'); 
        const response = await fetch('/admin/jadwal/detail/'+id); 
        const data = await response.json();
        const jadwal = data.data;
        
        $('#idJadwal').val(jadwal.id);
        $('#nama_pelanggan').val(jadwal.nama);
        $('#alamat_update').val(jadwal.alamat);
        $('#destinasi_update').val(jadwal.destinasi);
        $('#pax_update').val(jadwal.pax);
        $('#fee_update').val(jadwal.biaya);
        $('#mobil').val(jadwal.mobil_id);
        $('#email_update').val(jadwal.email);
        $('#telepon_update').val(jadwal.telepon);
        $('#note_update').val(jadwal.note);

        $('#formModalUpdate').modal('show');
        
    })

    $("#formDataUpdate").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this); 

        const id = $('#idJadwal').val();

        $.ajax({
            url: '/admin/jadwal/update/'+id,  
            method: "POST",
            data: formData,
            processData: false,  
            contentType: false,  
            success: function(response) {
                $("#formModalUpdate").modal("hide");

                $('#jadwal-table').DataTable().ajax.reload(null, false);

                $.toast({
                    heading: 'Success',
                    text: 'Berhasil mengubah data jadwal',
                    showHideTransition: 'slide',
                    icon: 'success'
                })
            },
            error: function(xhr) {
                
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan dalam menyimpan data',
                    showHideTransition: 'fade',
                    icon: 'error'
                })
            }
        });
    });
</script>
@endsection