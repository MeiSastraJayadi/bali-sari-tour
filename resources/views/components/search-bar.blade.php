<div 
    x-data="{ 
        open: false,  
        selected: 'Pilih mobil', 
        search() {
            const waktu = document.getElementById('waktuBerangkat').value;
            const params = new URLSearchParams(window.location.search);

            if (waktu) params.set('waktu', waktu);
            if (this.selected && this.selected !== 'Pilih mobil') {
                params.set('mobil', this.selected);
            } else {
                params.delete('mobil');
            }

            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.location.href = newUrl;
        }
    }"
    x-init="
        const params = new URLSearchParams(window.location.search);
        if (params.has('mobil')) selected = params.get('mobil');
    "
    class="py-1 max-sm:w-[95%] flex max-sm:flex-col border max-sm:items-start items-center justify-center border-[#ccc] shadow-lg rounded-xl bg-white pr-3 max-sm:px-3 max-sm:pb-3"
>

    <!-- Dropdown Mobil -->
    <div class="relative">
        <button @click="open = !open" class="flex hover:cursor-pointer flex-col items-start justify-center py-3 px-10 max-sm:px-0 max-sm:pl-2">
            <h1 class="text-md text-[#333] font-bold">Mobil / Kendaraan</h1>
            <h2 class="text-sm text-[#333] flex items-center gap-1">
                <span x-text="selected"></span>
                <i class="fas fa-car text-[#333]" x-show="selected === 'Pilih mobil'"></i>
            </h2>
        </button>

        <!-- Dropdown List -->
        <div x-show="open" @click.away="open = false"
            class="absolute top-full left-0 mt-1 w-48 bg-white border border-[#ccc] rounded-lg shadow-lg z-10">
            <ul class="text-sm text-[#333]">
                <li @click="selected = 'Pilih mobil'; open = false" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    Semua kategori
                </li>                    
                @foreach ($kategoriMobil as $type)
                    <li @click="selected = '{{ $type->nama_kategori }}'; open = false" 
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        {{ $type->nama_kategori }}
                    </li>                    
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Divider -->
    <div class="rounded-full max-sm:w-full max-sm:h-2 max-sm:bg-[#ccc] h-[80%] border border-[#ccc]"></div>

    <!-- Tanggal -->
    <button id="btnTanggal" class="flex flex-col hover:cursor-pointer items-start justify-center py-3 pl-10 max-sm:pl-2">
        <h1 class="text-md text-[#333] font-bold">Tanggal</h1>
        <div class="flex">
            <input class="focus:outline-none max-sm:w-full md:w-[70%] text-[#333] text-sm" 
                   placeholder="YYYY-MM-DD" type="date" name="waktu" id="waktuBerangkat">
        </div>
    </button>

    <button type="button" @click="search()" 
        class="rounded-xl max-sm:mt-2 max-sm:py-2 max-sm:w-full hover:cursor-pointer hover:opacity-95 text-white bg-[#333] h-[80%] flex items-center justify-center px-5">
        <span class="min-sm:hidden mr-2">Cari sopir</span>
        <i class="fas fa-magnifying-glass text-white"></i>
    </button>
</div>



<script>
    const imageArray = [
        "/images/road-trip-with-raj-sELcHR_bGVs-unsplash.jpg", 
        "/images/alec-favale-Bi_5VsaOLnI-unsplash.jpg", 
        "/images/aron-visuals-1kdIG_258bU-unsplash.jpg", 
        "/images/aron-visuals-lh6cxuDOS8s-unsplash.jpg"
    ]


    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param); 
    }

    const startDate = getQueryParam("waktu");

    flatpickr("#waktuBerangkat", {
        dateFormat: "Y-m-d",
        minDate: "today",
        clickOpens: false, 
        defaultDate: startDate ? startDate : null,
    });

    const fp = document.querySelector("#waktuBerangkat")._flatpickr;
    document.getElementById("btnTanggal").addEventListener("click", () => {
        fp.open();
        console.log("Hello World");
    });



</script>