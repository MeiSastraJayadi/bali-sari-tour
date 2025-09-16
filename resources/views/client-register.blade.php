@extends('layout.client-auth')

@section('form')
<form method="POST" action="{{ route('client-register-logic') }}" class="flex flex-col max-sm:w-full items-center justify-center">
    @csrf
    <div class="w-full py-2 px-3 bg-white mt-5 shadow-xl mb-4 rounded-full flex items-center">
        <input name="name" id="name" type="text" class="min-w-[20vw] max-sm:w-full border-none focus:outline-none" placeholder="Nama">
        <i class="fas fa-envelope text-[#333]"></i>
    </div>
    <div class="w-full py-2 px-3 shadow-xl bg-white mb-4 mt-2 rounded-full flex items-center">
        <input name="email" id="email" type="email" class="min-w-[20vw] max-sm:w-full border-none focus:outline-none" placeholder="Email">
        <i class="fas fa-lock text-[#333]"></i>
    </div>
    <div class="w-full py-2 px-3 shadow-xl bg-white mb-4 mt-2 rounded-full flex items-center">
        <input name="password" id="password" type="password" class="min-w-[20vw] max-sm:w-full border-none focus:outline-none" placeholder="Password">
        <i class="fas fa-lock text-[#333]"></i>
    </div>
    <button 
        type="submit"
        class="w-full py-3 hover:cursor-pointer hover:opacity-80 bg-[#333] text-white font-bold rounded-full mt-4 shadow-xl"
    >
        Register
    </button>

    @if ($errors->any())
        <div class="text-red-600 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
@endsection