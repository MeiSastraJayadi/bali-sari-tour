<div  class="w-full border border-[#ccc] flex flex-col 
    justify-start items-start rounded-xl mb-3" data-accordion>
    <button class="hover:bg-[#EFEFEFEF] hover:cursor-pointer rounded-xl px-4 py-4 duration-200 
        relative py-2 w-full flex flex-col items-start 
        justify-start" data-accordion-btn>
        @if ($invoice->lunas)
            <div class="bg-green-400 py-1 px-4 text-xs absolute top-2 right-2 text-white rounded-full">Dibayar</div>  
        @else
            <div class="bg-blue-400 py-1 px-4 text-xs absolute top-2 right-2 text-white rounded-full">Menunggu Pembayaran</div>             
        @endif
        <h1 class="text-md mt-2 text-[#333] font-semibold">{{ $invoice -> kode_reservasi }}</h1>
        <h2 class="text-sm text-[#333]">Keberangkatan pada {{ $invoice -> kodeReservasi -> reservasi -> tanggal }}</h2>
        <h3 class="text-sm font-semibold text-[#333] mt-3"><span class="currency">{{ $invoice -> biaya }}</span></h3>
        <i class="fas fa-chevron-down text-[#ccc] transition-transform duration-300 absolute bottom-2 right-2"></i>
    </button>
    <div class="accordion w-full px-4 max-h-0 overflow-hidden 
        transition-all duration-300 ease-in-out" 
        data-accordion-content
    > 
        @if ($invoice->kodeReservasi->reservasi->divalidasi)
        <div class="w-full flex w-full flex-col my-3 items-center justify-start border rounded-xl px-2 py-2 border-[#CCC]">
            <button class="w-full hover:cursor-pointer hover:opacity-90 text-sm mt-3 py-2 text-white bg-[#333] rounded-xl">
                Lihat Invoice
            </button>
        </div>
        @else
        <div class="w-full flex w-full flex-col my-3 items-center justify-start border rounded-xl px-2 py-2 border-[#CCC]">
            <button data-amount="{{ $invoice->biaya }}" 
                data-name="{{ $invoice->kodeReservasi->reservasi->pelanggan->nama_lengkap }}"
                data-email="{{ $invoice->kodeReservasi->reservasi->pelanggan->email }}"
                data-phone="{{ $invoice->kodeReservasi->reservasi->telepon }}"
                data-id="{{ $invoice->kode_reservasi }}" 
                class="payment-button w-full hover:cursor-pointer hover:opacity-90 
                    text-sm py-2 text-white bg-[#333] rounded-xl"
            >
                Bayar
            </button>
            <button data-id="{{ $invoice->kode_reservasi }}"  class="w-full cash-payment-btn hover:cursor-pointer hover:opacity-90 text-sm mt-3 py-2 text-white bg-[#333] rounded-xl">
                Pembayaran Cash
            </button>
        </div>
        @endif
    </div>
</div>