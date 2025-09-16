@extends('layout.client')

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="md:container mt-24 min-h-screen pb-24 max-sm:px-3 max-sm:w-full">
    <div class="w-full flex max-sm:flex-col max-sm:items-center items-end justify-between bg-[#333] rounded-xl max-sm:px-3 max-sm:py-8 px-10 py-10">
        <div class="flex max-sm:flex-col items-start justify-center max-sm:w-full max-sm:px-3">
            <img 
                src="{{ auth()->user()->profile_photo_url }}" 
                alt="Profil" 
                class="rounded-full
                    right-0 object-cover object-center h-[100px] w-[100px]"
            >
            <div class="flex flex-col max-sm:ml-0 ml-10 max-sm:mt-4 items-start justify-start">
                <div class="flex flex-col">
                    <h1 class="text-md text-white">
                        Nama
                    </h1>
                    <h1 class="text-xl text-white font-bold" id="userName">
                        {{ auth()->user()->pelanggan->nama_lengkap }}
                    </h1>
                </div>

                <div class="flex flex-col mt-5">
                    <h1 class="text-md text-white">
                        Alamat
                    </h1>
                    <h1 class="text-xl text-white font-bold" id="userAddress">
                        {{ auth()->user()->pelanggan->alamat == '' ? '-' : auth()->user()->pelanggan->alamat }}
                    </h1>
                </div>

                <div class="flex flex-col mt-5">
                    <h1 class="text-md text-white">
                        Email
                    </h1>
                    <h1 class="text-xl text-white font-bold" id="userEmail">
                        {{ auth()->user()->email }}
                    </h1>
                </div>

                <div class="flex flex-col mt-5">
                    <h1 class="text-md text-white">
                        Telepon
                    </h1>
                    <h1 class="text-xl text-white font-bold" id="userPhone">
                        {{ auth()->user()->pelanggan->telepon == '' ? '-' : auth()->user()->pelanggan->telepon }}
                    </h1>
                </div>
            </div>
            
        </div>
        <button id="editProfile" class="rounded-full bg-white flex px-8 hover:cursor-pointer 
            py-3 max-sm:w-full max-sm:mt-5 items-center justify-center font-bold text-[#333] shadow-xl text-lg">
            <i class="fas fa-pencil text-[#333] mr-3"></i>
            Edit
        </button>
    </div>

    <h1 class="mt-8 text-xl text-[#333] ml-3 font-bold">
        Reservasi
    </h1>

    <div class="flex max-sm:flex-col-reverse justify-start items-start mt-10 max-sm:mt-4 max-sm:w-full">
        <div class="flex max-sm:mt-8 flex-col items-center max-sm:w-full w-[70%]">
            @foreach ($reservations as $reservation )
                @include('components.reservation-list', ["reservation" => $reservation])
            @endforeach
        </div>
        <div class="flex flex-col w-[30%] pl-12 max-sm:pl-0 max-sm:w-full">
            @include('components.filter-reservation')
            <button id="seeInvoice" class="w-full rounded-xl shadow-xl mt-5 
                bg-[#333] text-white text-lg py-3 hover:cursor-pointer
                hover:opacity-90">
                Lihat Invoice
            </button>
        </div>
    </div>

    <div id="invoicePanel" class="fixed top-0 right-0 bottom-0 border 
        border-[rgba(0,0,0,0)] border-l-[#CCC] min-w-100 px-3
        bg-white shadow-xl z-[200] translate-x-full transition-transform 
        ease-in-out flex items-start justify-center">
        <div class="h-full relative w-full pt-24 overflow-scroll">
            <button id="closePanel" class="text-white rounded-lg shadow-lg w-[30px] 
                h-[30px] bg-[#333] flex items-center justify-center 
                absolute top-2 right-4 hover:cursor-pointer hover:opacity-90"
            >
                <i class="fas fa-close text-white"></i>
            </button>
            @foreach ($invoices as $invoice)
                @include('components.incoice-list', ["invoice" => $invoice])                
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    const invoiceButton = document.getElementById('seeInvoice');
    const closeBtn = document.getElementById('closePanel');

    invoiceButton.addEventListener('click', togglePanel)
    closeBtn.addEventListener('click', togglePanel)

    function togglePanel() {
        const sidePanel = document.getElementById('invoicePanel'); 
        sidePanel.classList.toggle('translate-x-full')
        sidePanel.classList.toggle('translate-x-0')   
    }

    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    });

    $('.currency').each(function() {
        const value = parseFloat($(this).text().replace(/[^0-9.-]+/g,"")); // clean non-numeric
        if (!isNaN(value)) {
        $(this).text(formatter.format(value));
        }
    });

    $(document).on("click", ".payment-button", function () {
        let amount = $(this).data("amount");
        let name   = $(this).data("name");
        let email  = $(this).data("email");
        let phone  = $(this).data("phone");
        let orderId= $(this).data("id");

        $.ajax({
            url: "{{ route('snap-token') }}",
            method: "POST",
            data: JSON.stringify({
                kode_reservasi: orderId,
                biaya: amount,
                pelanggan: name,
                email: email,
                telepon: phone
            }),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                window.snap.pay(res.token, {
                    onSuccess: function (result) {
                        console.log(result)
                        console.log(result.order_id)
                        $.ajax({
                            url: "{{ route('payment-success') }}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // required for Laravel
                            },
                            data: {
                                order_id: result.order_id,
                                signature_key: result.signature_key,
                                gross_amount: result.gross_amount,
                                transaction_id: result.transaction_id,
                                transaction_time: result.transaction_time,
                                transaction_status: result.transaction_status
                            }, 
                            success: function(response) {
                                location.reload();
                            }
                        });
                    },
                    onPending: function (result) {
                        console.log("PENDING", result);
                    },
                    onError: function (result) {
                        console.log("ERROR", result);
                    },
                    onClose: function () {
                        alert("Anda menutup popup tanpa menyelesaikan pembayaran.");
                    }
                });
            },
            error: function (err) {
                console.error("AJAX Error:", err);
            }
        });
    });


    $(document).on('click', '#editProfile', function() {
        const nameDisplay = document.getElementById('userName');
        const emailDisplay = document.getElementById('userEmail');
        const addressDisplay = document.getElementById('userAddress');
        const phoneDisplay = document.getElementById('userPhone');

        const editButton = document.getElementById('editProfile');

        const currentName = nameDisplay.textContent.trim();
        const currentEmail = emailDisplay.textContent.trim();
        const currentAddress = addressDisplay.textContent.trim();
        const currentPhone = phoneDisplay.textContent.trim();

        nameDisplay.outerHTML = `
        <input type="text" 
            id="userNameInput"
            class="text-xl text-white font-bold px-2 py-1 focus:outline-none border-0 border-b-2 border-white" 
            value="${currentName}" />
        `;


        addressDisplay.outerHTML = `
        <input type="text" 
            id="userAddressInput"
            class="text-xl text-white font-bold focus:outline-none px-2 py-1 border-0 border-b-2 border-white" 
            value="${currentAddress}" />
        `;

        phoneDisplay.outerHTML = `
        <input type="email" 
            id="userPhoneInput"
            class="text-xl text-white focus:outline-none font-bold px-2 py-1 border-0 border-b-2 border-white" 
            value="${currentPhone}" />
        `;

        editButton.outerHTML = `
        <div id='endEditButton' class='flex max-sm:mt-5 items-center justify-center'>
            <button id="saveProfile" class="rounded-full hover:cursor-pointer bg-white flex px-8 
            py-3 items-center justify-center font-bold text-[#333] shadow-xl text-lg">
                <i class="fas fa-save text-[#333] mr-3"></i>
                Simpan
            </button>
            <button id="cancelProfile" class="rounded-full hover:cursor-pointer bg-white flex px-8 
            py-3 items-center justify-center ml-2 font-bold text-[#333] shadow-xl text-lg">
                <i class="fas fa-close text-[#333] mr-3"></i>
                Batal
            </button>
        </div>
        `;

        const inputName = document.getElementById('userNameInput');
        inputName.focus();
        const length = inputName.value.length;
        inputName.setSelectionRange(length, length);
    })

    function isEmptyOrSpaces(str) {
        return str.trim().length === 0;
    }

    $(document).on('click', '#saveProfile', function() {
        const nameInput = document.getElementById('userNameInput');
        const addressInput = document.getElementById('userAddressInput');
        const phoneInput = document.getElementById('userPhoneInput');

        const fullname = nameInput.value; 
        const address = addressInput.value; 
        const phone = phoneInput.value;
        const email = "{{ auth()->user()->email }}";

        if (isEmptyOrSpaces(fullname)) return;

        const data = {
            fullname, 
            address, 
            phone, 
            email
        }

        $.ajax({
            url: '{{ route('edit-profile') }}',  
            type: 'POST',            
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // required for Laravel
            },
            success: function (response) {
                window.location.href = window.location.origin + window.location.pathname;
            }
        });



    })

    $(document).on('click', '#cancelProfile', function() {
        const nameInput = document.getElementById('userNameInput');
        const addressInput = document.getElementById('userAddressInput');
        const phoneInput = document.getElementById('userPhoneInput');

        nameInput.outerHTML = `
        <h1 class="text-xl text-white font-bold" id="userName">
            {{ auth()->user()->pelanggan->nama_lengkap }}
        </h1>
        `

        addressInput.outerHTML = `
        <h1 class="text-xl text-white font-bold" id="userAddress">
            {{ auth()->user()->pelanggan->alamat == '' ? '-' : auth()->user()->pelanggan->alamat }}
        </h1>
        `

        phoneInput.outerHTML = `
        <h1 class="text-xl text-white font-bold" id="userPhone">
            {{ auth()->user()->pelanggan->telepon == '' ? '-' : auth()->user()->pelanggan->telepon }}
        </h1>
        `
        const groupCancel = document.getElementById('endEditButton'); 
        groupCancel.outerHTML = `
        <button id="editProfile" class="rounded-full max-sm:w-full max-sm:mt-5 bg-white flex px-8 hover:cursor-pointer
            py-3 items-center justify-center font-bold text-[#333] shadow-xl text-lg">
            <i class="fas fa-pencil text-[#333] mr-3"></i>
            Edit
        </button>`
    })


    
</script>
@endsection