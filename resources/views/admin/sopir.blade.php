@extends('admin.layout.app')


@section('title')
<div class="row d-flex justify-content-between pl-2">
    Sopir
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#formModal">
        Tambah
    </button>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table id="sopir-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
  
        <form id="formData" enctype="multipart/form-data">
          <!-- Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Data Sopir</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <!-- Body -->
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Masukan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori Mobil</label>
                <select class="form-control" id="kategori" name="kategori">
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="row">
                <div class="form-group col-lg-8 col-md-6 col-sm-6 col-12">
                    <label for="mobil">Jenis Mobil</label>
                    <input type="text" class="form-control" id="mobil" name="mobil" placeholder="Masukkan jenis mobil" required>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-6 col-12">
                    <label for="tahun">Tahun keluaran</label>
                    <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun keluaran mobil" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                </div>
    
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan no telepon/whatsapp" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4 col-md-6 col-sm-6 col-12">
                    <label for="sopir">Foto Sopir</label>
                    <input type="file" class="form-control" id="sopir" name="sopir" placeholder="Tambahkan foto sopir" required>
                </div>
                <div class="form-group col-lg-8 col-md-6 col-sm-6 col-12">
                    <label for="fotoMobil">Tambahkan foto mobil</label>
                    <input type="file" class="form-control" id="fotoMobil" name="fotoMobil" placeholder="Tambahkan foto mobil" required>
                </div>
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
            <input type="hidden" id="idSopir">
            <div class="form-group">
              <label for="name_update">Nama Lengkap</label>
              <input type="text" class="form-control" id="name_update" name="name_update" placeholder="Masukan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="alamat_update">Alamat</label>
                <input type="text" class="form-control" id="alamat_update" name="alamat_update" placeholder="Masukkan alamat" required>
            </div>
            <div class="form-group">
                <label for="kategori_update">Kategori Mobil</label>
                <select class="form-control" id="kategori_update" name="kategori_update">
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="row">
                <div class="form-group col-lg-8 col-md-6 col-sm-6 col-12">
                    <label for="mobil_update">Jenis Mobil</label>
                    <input type="text" class="form-control" id="mobil_update" name="mobil_update" placeholder="Masukkan jenis mobil" required>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-6 col-12">
                    <label for="tahun_update">Tahun keluaran</label>
                    <input type="text" class="form-control" id="tahun_update" name="tahun_update" placeholder="Tahun keluaran mobil" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="email_update">Email</label>
                    <input type="email" class="form-control" id="email_update" name="email_update" placeholder="Masukkan email" required disabled>
                </div>
    
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                    <label for="telepon_update">Telepon</label>
                    <input type="text" class="form-control" id="telepon_update" name="telepon_update" placeholder="Masukkan no telepon/whatsapp" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4 col-md-6 col-sm-6 col-12">
                    <label for="sopir_update">Foto Sopir</label>
                    <input type="file" class="form-control" id="sopir_update" name="sopir_update" placeholder="Tambahkan foto sopir">
                </div>
                <div class="form-group col-lg-8 col-md-6 col-sm-6 col-12">
                    <label for="fotoMobil_update">Tambahkan foto mobil</label>
                    <input type="file" class="form-control" id="fotoMobil_update" name="fotoMobil_update" placeholder="Tambahkan foto mobil">
                </div>
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

    $("#formData").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this); 

        $.ajax({
            url: "{{ route('admin.sopir.tambah') }}",  
            method: "POST",
            data: formData,
            processData: false,  
            contentType: false,  
            success: function(response) {
                $("#formModal").modal("hide");

                $('#sopir-table').DataTable().ajax.reload(null, false);

                $.toast({
                    heading: 'Success',
                    text: 'Berhasil menyimpan data sopir',
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

    $('#sopir-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.sopir.data') }}',
        pageLength: 4,   // 👈 limit rows per page
        lengthMenu: [[4, 10, 25, 50, -1], [4, 10, 25, 50, "All"]], // 👈 dropdown options
        columns: [
            { data: 'id', name: 'id' },
            { data: 'nama_lengkap', name: 'nama_lengkap' },
            { data: 'alamat', name: 'alamat' },
            { data: 'email', name: 'email' },
            { data: 'telepon', name: 'telepon' }, 
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false 
            }
        ]
    });

    $('#sopir-table').on('click', '.edit-btn', async function () {
        const id = $(this).data('id'); 
        const response = await fetch('/admin/sopir/detail/'+id); 
        const data = await response.json();
        const sopir = data.data.sopir; 
        const mobil = data.data.mobil;

        $('#idSopir').val(sopir.id);
        $('#name_update').val(sopir.nama_lengkap);
        $('#alamat_update').val(sopir.alamat);
        $('#kategori_update').val(mobil.kategori_id).trigger('change');
        $('#mobil_update').val(mobil.nama_mobil);
        $('#tahun_update').val(mobil.tahun);
        $('#email_update').val(sopir.email);
        $('#telepon_update').val(sopir.telepon);

        $('#formModalUpdate').modal('show');
        
    })

    $("#formDataUpdate").on("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this); 

        const id = $('#idSopir').val();

        $.ajax({
            url: '/admin/sopir/update/'+id,  
            method: "POST",
            data: formData,
            processData: false,  
            contentType: false,  
            success: function(response) {
                $("#formModalUpdate").modal("hide");

                $('#sopir-table').DataTable().ajax.reload(null, false);

                $.toast({
                    heading: 'Success',
                    text: 'Berhasil menyimpan data sopir',
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

    $('#sopir-table').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        $.confirm({
            title: 'Hapus data',
            content: 'Yakin ingin menghapus sopir?',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Ok!",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                        $.ajax({
                            url: "/admin/sopir/hapus/"+id,  
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
                                $('#sopir-table').DataTable().ajax.reload(null, false);
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