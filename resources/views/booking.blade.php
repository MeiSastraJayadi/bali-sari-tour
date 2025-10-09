@extends('layout.client')

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="md:container mt-5 min-h-screen flex max-sm:flex-col max-sm:px-3
    items-center justify-center">
    <img src="{{ request()->getSchemeAndHttpHost() }}/{{ $image }}" 
        class="border border-[#DDD] max-sm:w-full w-[35%] h-[450px] object-center object-cover rounded rounded-xl shadow-xl" 
        alt="{{ 'Image -'.$name }}">
    <div class="flex flex-col ml-12 max-sm:ml-0 max-sm:w-full max-sm:mt-4">
        <div class="flex items-center justify-start max-sm:ml-2">
            <img src="{{ $profilePicture }}" alt="{{ $name }}" class="rounded rounded-full">
            <div class="flex flex-col ml-5">
                <h1 class="text-xl text-[#333]">{{ $name }}</h1>
                <h2 class="text-md text-[#555]">{{ $email }}</h2>
            </div>
        </div>
        <h1 class="text-2xl font-bold mt-3 max-sm:ml-2">{{ $type }}</h1>
        <h2 class="text-lg max-sm:ml-2">{{ $address }}</h1>
        <h3 class="text-lg max-sm:ml-2">{{ $age }}</h3>
        <div class="flex items-center justify-start my-4">
            @if ($available)
                <div class="bg-green-400 rounded-full w-[10px] h-[10px] mr-3 max-sm:ml-2"></div>
                <h4 class="text-green-400 text-sm">Siap mengantar pada {{ $date }}</h4>
            @else
                <div class="bg-red-400 rounded-full w-[10px] h-[10px] mr-3 max-sm:ml-2"></div>
                <h4 class="text-red-400 text-sm">Sopir tidak dapat mengantar pada {{ $date }}</h4>
            @endif
        </div>
        @if ($available)
            <button id="bookBtn" class="w-full text-xl p-3 text-white 
                bg-[#333] mt-4 rounded-xl shadow-xl hover:cursor-pointer hover:opacity-80
                active:scale-90 duration-300">
                Pesan Sekarang
            </button>
        @else
            <a href="{{ route('home') }}" class="w-full text-center text-xl p-3 text-white 
                bg-[#333] mt-4 rounded-xl shadow-xl hover:cursor-pointer hover:opacity-80
                active:scale-90 duration-300">
                Kembali
            </a>
        @endif
    </div>
</div>


<div id="reservationForm" class="fixed hidden top-0 bottom-0 flex items-center justify-center 
    left-0 right-0 z-[60] bg-[rgba(0,0,0,.3)]">
    <div class="px-8 max-sm:px-3 py-8 pt-12 bg-white shadow-xl border border-[#BBB] flex 
        flex-col w-[50%] max-sm:w-[95%] rounded rounded-xl relative"
    >
        <i id="closeBtn" class="fas fa-close text-[#555] hover:cursor-pointer top-3 right-3 absolute"></i>
        <input name="nama" type="text" class="mt-5 pl-2 py-2 mb-3 text-md rounded rounded-xl 
            border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Nama Lengkap" 
            value="{{ auth()->user() ? auth()->user()->pelanggan->nama_lengkap : '' }}"
            required>
        <input name="alamat" type="text" class="pl-2 py-2 mb-3 text-md rounded rounded-xl 
            border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Alamat Penjemputan" required>
        <input name="destinasi" type="text" class="pl-2 py-2 mb-3 text-md rounded rounded-xl 
            border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Destinasi" required>
        <div class="flex justify-center items-center mb-3 max-sm:flex-col">
            <input name="email" type="text" class="pl-2 w-[39%] max-sm:w-full mr-[1%] py-2 mb-2 text-md rounded rounded-xl 
                border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Email" value="{{ auth()->user() ? auth()->user()->email : '' }}" required>
            <input name="telepon" type="text" class="pl-2 w-[39%] max-sm:w-full mr-[1%] py-2 mb-2 text-md rounded rounded-xl 
                border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Telepon" required>
            <input name="guest" type="text" class="pl-2 py-2 w-[20%] max-sm:w-full mb-2 text-md rounded rounded-xl 
                border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Jumlah Orang" required>
        </div>

        <h1 class="text-md font-bold mt-2 mb-1 text-[#555]">Waktu Berangkat</h1>
        <input value="{{ $defaultDate }}" id="dateTime" name="time" type="date" class="pl-2 py-2 mb-3 text-md rounded rounded-xl 
            border-2 border-[#BBB] focus:outline-none text-[#333]" placeholder="Destinasi" required>
        <textarea 
            name="note" 
            id="noteText" 
            placeholder="Masukkan detail waktu dan detail lainnya"
            class="border-2 border-[#BBB] pl-2 pt-2 text-[#333] text-md focus:outline-none rounded-xl"
            rows="5"></textarea>
        <div class="w-full flex items-center justify-center">
            <button id="nextReservation" class="hover:cursor-pointer hover:opacity-90 text-white text-xl py-4 bg-[#333] rounded 
            rounded-xl shadow-xl font-bold mt-3 w-[100%]">Lihat Detail Reservasi</button>
        </div>
    </div>
</div>

<div id="reservationDetail" class="fixed hidden top-0 bottom-0 flex items-center justify-center 
    left-0 right-0 z-[60] bg-[rgba(0,0,0,.3)]">
    <div class="px-8 max-sm:px-3 py-8 pt-12 bg-white shadow-xl border border-[#BBB] flex 
        flex-col w-[50%] max-sm:w-[95%] rounded rounded-xl relative max-h-[80vh] overflow-hidden"
    >
        <i id="closeBtnDetail" class="fas fa-close text-[#555] hover:cursor-pointer top-3 right-3 absolute"></i>
        <div class="flex flex-col items-start justify-center my-1">
            <h3 class="text-md text-[#777] font-bold">Sopir</h3>
            <h2 class="text-xl font-bold text-[#333]">{{ $name }}</h2>
        </div>
        <div class="flex flex-col items-start justify-center my-1">
            <h3 class="text-md text-[#777] font-bold">Mobil</h3>
            <h2 class="text-xl font-bold text-[#333]">{{ $type }}</h2>
        </div>
        <div class="flex justify-start items-start my-1 max-sm:flex-wrap">
            <div class="flex flex-col items-start justify-center my-1 w-[34%] max-sm:w-[49%] mr-[1%]">
                <h3 class="text-md text-[#777] font-bold">Tempat Penjemputan</h3>
                <h2 class="text-xl font-bold text-[#333]" id="addressLabel">Badung</h2>
            </div>
            <div class="flex flex-col items-start justify-center my-1 w-[34%] max-sm:w-[50%] mr-[1%] max-sm:mr-0">
                <h3 class="text-md text-[#777] font-bold">Destinasi</h3>
                <h2 class="text-xl font-bold text-[#333]" id="destinationLabel">Sanur</h2>
            </div>
            <div class="flex flex-col items-start justify-center my-1 w-[30%] max-sm:w-full">
                <h3 class="text-md text-[#777] font-bold">Waktu Berangkat</h3>
                <h2 class="text-xl font-bold text-[#333]" id="timeLabel">17 Juli 2025</h2>
            </div>
        </div>
        <div class="flex flex-col items-start justify-center my-1 mb-4">
            <h3 class="text-md text-[#777] font-bold">Note</h3>
            <p class="text-lg font-bold text-[#333] overflow-scroll h-30" id="noteLabel">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed viverra, 
                libero vitae volutpat dapibus, turpis arcu malesuada libero, vitae 
                tristique nisi est a nulla. Cras dictum neque vel arcu hendrerit, 
                nec dignissim ipsum iaculis. Integer pretium justo eget turpis fermentum, 
                in feugiat ligula finibus. Sed ut risus ac neque rhoncus mattis. Aenean 
                venenatis, libero sed suscipit egestas, nibh neque malesuada sapien, nec 
                dignissim nisl magna vel ipsum. Suspendisse sit amet sagittis nulla. Nulla 
                facilisi. Ut fermentum lorem sed augue porta, id porta odio pretium. Sed 
                quis nibh in lorem scelerisque ultrices. Mauris tincidunt quam id purus 
                pretium, ut ornare mi venenatis. Sed cursus metus in nisl euismod, et 
                lacinia nunc pulvinar. Aliquam erat volutpat. In vitae risus sed elit 
                aliquet gravida ac sit amet sapien. Etiam bibendum nunc at erat hendrerit, 
                ac volutpat magna volutpat. Vestibulum egestas, lorem ut scelerisque 
                rhoncus, turpis magna sagittis arcu, a bibendum augue turpis sit amet 
                purus.
            </p>
        </div>
        <div class="flex items-center justify-center">
            <div class="w-full flex items-center justify-center">
                <button id="submitReservation" class="hover:cursor-pointer hover:opacity-90 text-white text-xl py-4 bg-[#333] rounded 
                rounded-xl shadow-xl font-bold mt-3 w-[100%]">Kirim Reservasi</button>
            </div>
            <div class="w-full flex items-center justify-center mx-3">
                <button id="backReservation" class="hover:cursor-pointer hover:opacity-90 text-[#333] text-xl py-4 bg-white rounded 
                rounded-xl shadow-xl font-bold mt-3 w-[100%] border-2 border-[#333]">Kembali</button>
            </div>
        </div>
    </div>
</div>

<div id="reservationResponse" class="fixed hidden top-0 bottom-0 flex items-center justify-center 
    left-0 right-0 z-[60] bg-[rgba(0,0,0,.3)]">
    <div class="px-8 py-8 pt-12 bg-white shadow-xl border border-[#BBB] flex 
        flex-col items-center justify-center w-[95%] max-sm:w-full rounded rounded-xl relative max-h-[80vh] overflow-hidden"
    >
        <img src="/images/logo.png" alt="" class="w-30">
        <h1 class="text-xl font-bold text-[#555] text-center pt-8">
            Berhasil membuat permintaaan reservasi. 
        </h1>
        <h1 class="text-xl font-bold text-center text-[#555] pt-1">
            Silahkan cek status reservasi pada halaman reservasi
        </h1>
        <div class="w-full flex items-center justify-center mt-10">
            <a href="{{ route('home') }}" class="hover:cursor-pointer hover:opacity-90 text-white text-xl py-4 bg-[#333] rounded 
            rounded-xl shadow-xl font-bold mt-3 w-[100%] flex items-center justify-center">Kembali</a>
        </div>
    </div>
</div>


<div id="loadingData" class="fixed top-0 z-[100] hidden bottom-0 flex items-center justify-center 
    left-0 right-0 z-[60] bg-[rgba(0,0,0,.3)]">
    <div class="w-10 h-10 border-4 border-gray-200 border-t-[#333] rounded-full animate-spin" role="status" aria-label="Loading"></div>
</div>


<div id="errorMessage" class="hidden fixed top-0 left-0 right-0 z-[200] flex items-center py-3 justify-center">
    <div class="bg-white min-w-[40vw] rounded-xl shadow-xl border border-[#DDD] px-2 py-2 flex items-center relative">
        <div class="h-10 bg-red-600 pl-1 rounded-full"></div>
        <h1 id="errorLabel" class="text-md font-bold ml-5 text-[#555]">
            Isi kolom nama lengkap terlebih dahulu
        </h1>
        <i id="closeBtnError" class="fas text-xs fa-close text-[#555] hover:cursor-pointer top-3 right-3 absolute"></i>
    </div>
</div>

@endsection

@section('script')
<script>
    const params = new URLSearchParams(window.location.search);
    
    const button = document.getElementById('bookBtn'); 
    const reservationForm = document.getElementById('reservationForm'); 
    const reservationDetail = document.getElementById('reservationDetail'); 
    const reservationResponse = document.getElementById('reservationResponse');
    const loadingData = document.getElementById('loadingData');  
    button.addEventListener('click', function() {
        reservationForm.classList.remove('hidden');

        if (params.has('date')) {
            const timeInput = document.getElementById('dateTime'); 
            timeInput.value = params.get('date'); 
        } 

    })

    const close = document.getElementById('closeBtn'); 
    close.addEventListener('click', function() {
        document.querySelector('input[name="nama"]').value = '{{ auth()->user() ? auth()->user()->pelanggan->nama_lengkap : "" }}'; 
        document.querySelector('input[name="alamat"]').value = ''; 
        document.querySelector('input[name="destinasi"]').value = ''; 
        document.querySelector('input[name="email"]').value = '{{ auth()->user() ? auth()->user()->email : "" }}'; 
        document.querySelector('input[name="telepon"]').value = '';
        document.querySelector('input[name="guest"]').value = '';
        document.querySelector('input[name="time"]').value = '{{ $defaultDate }}'; 
        document.getElementById('noteText').value = '';
        reservationForm.classList.add('hidden');
    })

    const closeError = document.getElementById('closeBtnError'); 
    closeError.addEventListener('click', function() {
        const errorMessage = document.getElementById('errorMessage'); 
        errorMessage.classList.add('hidden');
    })

    const submit = document.getElementById('submitReservation'); 
    submit.addEventListener('click', function() {
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        
        if (!isLoggedIn) {
            window.location.href = "{{ route('client-login') }}";
        } else {
            console.log("Has been login");
            fetchProtectedData(); 
        }
    })

    const nextButton = document.getElementById('nextReservation'); 
    nextButton.addEventListener('click', getReservationData); 

    let name = ''; 
    let address = ''; 
    let destination = ''; 
    let email = ''; 
    let phone = ''; 
    let pax = ''; 
    let note = ''; 
    let time = ''; 

    const closeDetail = document.getElementById('closeBtnDetail'); 
    closeDetail.addEventListener('click', function() {
        name = ''; 
        address = ''; 
        destination = ''; 
        email = ''; 
        phone = ''; 
        pax = ''; 
        note = ''; 
        time = ''; 
        document.querySelector('input[name="nama"]').value = ''; 
        document.querySelector('input[name="alamat"]').value = ''; 
        document.querySelector('input[name="destinasi"]').value = ''; 
        document.querySelector('input[name="email"]').value = ''; 
        document.querySelector('input[name="telepon"]').value = '';
        document.querySelector('input[name="guest"]').value = '';
        document.querySelector('input[name="time"]').value = ''; 
        document.getElementById('noteText').value = '';
        reservationDetail.classList.add('hidden'); 
    })

    const back = document.getElementById('backReservation');
    back.addEventListener('click', backReservation); 


    function getFormattedTime(date) {
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        let now; 
        if (date.trim() === "") {
            now = new Date();
        } else {
            now = new Date(date); 
        }
        const day = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        return `${day} ${month} ${year}`;
    }

    function getReservationData() {
        name = document.querySelector('input[name="nama"]').value; 
        address = document.querySelector('input[name="alamat"]').value; 
        destination = document.querySelector('input[name="destinasi"]').value; 
        email = document.querySelector('input[name="email"]').value; 
        phone = document.querySelector('input[name="telepon"]').value;
        pax = document.querySelector('input[name="guest"]').value;
        note = document.getElementById('noteText').value ?? '';
        time = getFormattedTime(document.getElementById('dateTime').value); 

        console.log(document.getElementById('dateTime').value); 

        const listString = [name, address, destination, email, phone, pax]; 
        const listCol = ["nama", "alamat", "destinasi", "email", "no telepon", "jumlah tamu"]; 
        let idx = 0; 
        const hasEmpty = listString.some((item, index) => {
                if (item.trim() === "") {
                    idx = index
                    return true
                } else {
                    return false;
                };
            });

        if (hasEmpty) {
            const errorLabel = document.getElementById('errorLabel');
            const errorMessage = document.getElementById('errorMessage'); 
            errorMessage.classList.remove('hidden');
            errorLabel.textContent = `Isi kolom ${listCol[idx]} terlebih dahulu`
            return;
        }

        reservationForm.classList.add('hidden');
        reservationDetail.classList.remove('hidden'); 

        document.getElementById('addressLabel').textContent = address; 
        document.getElementById('destinationLabel').textContent = destination; 
        document.getElementById('timeLabel').textContent = time;
        document.getElementById('noteLabel').textContent = note;
    }

    function backReservation() {
        reservationDetail.classList.add('hidden');
        reservationForm.classList.remove('hidden'); 

        document.querySelector('input[name="nama"]').value = name; 
        document.querySelector('input[name="alamat"]').value = address; 
        document.querySelector('input[name="destinasi"]').value = destination; 
        document.querySelector('input[name="email"]').value = email; 
        document.querySelector('input[name="telepon"]').value = phone;
        document.querySelector('input[name="guest"]').value = pax;
        document.querySelector('input[name="time"]').value = time; 
        document.getElementById('noteText').value = note;
    }

    async function fetchProtectedData() {
        loadingData.classList.remove('hidden'); 
        const id = '{{ $car_id }}'
        const user = '{{ Auth::user() != null ? Auth::user() -> email : '' }}'
        try {
            const payload = {
                id,
                name, 
                address, 
                destination, 
                email, 
                phone, 
                pax, 
                note,
                time, 
                user
            }
            const response = await fetch('/book/create-reservation', {
                method: 'POST',
                credentials: 'same-origin', 
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body : JSON.stringify(payload)
            });

            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}`);
            }

            const data = await response.json();
            if (data.status) {
                reservationDetail.classList.add('hidden'); 
                reservationResponse.classList.remove('hidden'); 
            }

            loadingData.classList.add('hidden'); 
            
        } catch (err) {
            console.error('Fetch error:', err);
        }
    }

</script>
@endsection