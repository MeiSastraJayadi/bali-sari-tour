<div class="w-full rounded-xl border border-[#CCC] shadow-xl px-8 py-4 pb-6 
    flex flex-col items-center justify-center"
>
    <h1 class="text-center font-bold text-lg">Filter Reservasi</h1>
    <div class="flex mt-6 justify-center items-center border-b-[rgba(0,0,0,0)] border-2
        w-full px-3 py-2  rounded-t-xl border-l-[#ccc] border-r-[#aaa] border-t-[#ccc]"
    >
        <button id="startDate" class="flex hover:cursor-pointer text-md text-[#aaa] items-center w-full justify-between">
            <div class="flex flex-col justify-start items-start w-full">
                <h2 class="text-md">Tanggal Awal</h2>
                <input class="focus:outline-none text-[#777] text-sm" placeholder="YY-mm-dd" type="datetime" name="dateStart" id="dateStart">
            </div>
            <i class="fas fa-calendar text-[#aaa] mr-3"></i>
        </button>
    </div>
    <div class="flex justify-center items-center border-b-[#aaa] border-2
        w-full px-3 py-2  rounded-b-xl border-l-[#ccc] border-r-[#aaa] border-t-[#ccc]"
    >
        <button id="endDate" class="flex text-md text-[#aaa] items-center w-full justify-between">
            <div class="flex flex-col justify-start items-start w-full">
                <h2 class="text-md">Tanggal Awal</h2>
                <input class="focus:outline-none text-[#777] text-sm" placeholder="YY-mm-dd" type="datetime" name="dateEnd" id="dateEnd">
            </div>
            <i class="fas fa-calendar text-[#aaa] mr-3"></i>
        </button>
    </div>

    <button id="filterInvoiceDate" class="w-full py-3 rounded-xl mt-8 bg-[#333] text-white 
        font-bold shadow-xl text-xl hover:cursor-pointer 
        hover:opacity-90 duration-300">
        Terapkan
    </button>
</div>

<script>

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param); 
    }

    const startDate = getQueryParam("date_start");

    flatpickr("#dateStart", {
        dateFormat: "Y-m-d",
        minDate: "today",
        clickOpens: false, 
        defaultDate: startDate ? startDate : null,
    });

    const endDate = getQueryParam("date_end");

    flatpickr("#dateEnd", {
        dateFormat: "Y-m-d",
        minDate: "today",
        clickOpens: false, 
        defaultDate: endDate ? endDate : null,
    });

    const fp = document.querySelector("#dateStart")._flatpickr;
    document.getElementById("startDate").addEventListener("click", () => {
        fp.open();
    });

    const fp2 = document.querySelector("#dateEnd")._flatpickr;
    document.getElementById("endDate").addEventListener("click", () => {
        fp2.open();
    });

    const button = document.getElementById("filterInvoiceDate");
    button.addEventListener("click", function() {
        const start = document.getElementById("dateStart").value
        const end = document.getElementById("dateEnd").value

        window.location.href = window.location.origin + window.location.pathname + '?date_start='+start+'&date_end='+end
    })

</script>