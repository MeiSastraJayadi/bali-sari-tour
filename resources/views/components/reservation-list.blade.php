<div class="rounded-xl mb-6 relative border border-[#ccc] px-8 py-8 max-sm:px-3 max-sm:py-3 shadow-xl 
    flex max-sm:flex-col justify-between items-start w-full">
    <div class="flex max-sm:mt-8 max-sm:w-full max-sm:flex-col items-center justify-start">
        <img 
            src="{{ $reservation->mobil->owner->user->profile_photo_url }}" 
            alt="Profil" 
            class="rounded-full
                right-0 object-cover object-center h-[80px] w-[80px]"
        >
        <div class="flex flex-col items-start justify-start ml-10 max-sm:ml-0">
            <h1 class="text-lg max-sm:w-full max-sm:text-md max-sm:text-center text-[#333] font-semibold">{{ $reservation->kode_reservasi->kode }}</h1>
            <h1 class="text-md max-sm:w-full max-sm:text-sm max-sm:text-center text-[#333] font-thin">Keberangkatan pada {{ $reservation -> tanggal }}</h1>
            <h1 class="text-lg max-sm:w-full max-sm:text-md max-sm:text-center mt-5 text-[#333] font-semibold">{{ $reservation->mobil->owner->nama_lengkap }}</h1>
            <h1 class="text-md max-sm:w-full max-sm:text-sm max-sm:text-center text-[#333] font-thin">{{ $reservation->mobil->nama_mobil }}</h1>
        </div>
    </div>
    <div class="rounded-full max-sm:absolute px-5 py-1 {{ $reservation->divalidasi ? 'bg-[#0B8219]' : 'bg-[#0B92E6]'}}">
        @if ( $reservation->kode_reservasi->invoice != null )
            <h1 class="text-white text-xs">{{ $reservation->divalidasi ? 'Dibayar' : 'Menunggu Pembayaran' }}</h1>
        @else
            <h1 class="text-white text-xs">Menunggu Konfirmasi</h1>
        @endif
    </div>
    @if ($reservation->divalidasi)
        @if (!$reservation->kode_reservasi->konfirmasi)
            <button data-id="{{ $reservation->kode_reservasi->kode }}" class="text-white konfirmasi-btn max-sm:mt-4 max-sm:text-md max-sm:py-2 text-sm hover:cursor-pointer hover:opacity-90 rounded-xl shadow-xl 
                px-4 py-1 max-sm:relative max-sm:w-full absolute min-sm:bottom-5 min-sm:right-5 bg-[#333]">
                Konfirmasi Jalan
            </button>
        @else
            @if (!$reservation->kode_reservasi->konfirmasi->konfirmasi_pelanggan)
                <button data-id="{{ $reservation->kode_reservasi->kode }}" class="text-white konfirmasi-btn max-sm:mt-4 max-sm:text-md max-sm:py-2 text-sm hover:cursor-pointer hover:opacity-90 rounded-xl shadow-xl 
                    px-4 py-1 max-sm:relative max-sm:w-full absolute min-sm:bottom-5 min-sm:right-5 bg-[#333]">
                    Konfirmasi Jalan
                </button>
            @endif
        @endif
    @endif
</div>

<script>
    $('.konfirmasi-btn').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "/book/konfirmasi/"+id,  
            method: "GET",
            processData: false,  
            contentType: false,  
            success: function(response) {
                location.reload()
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
    })
</script>