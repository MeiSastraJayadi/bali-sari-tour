@extends('auth.layout.main')

@section('form')
<form method="POST" action="{{ route('admin.auth.login') }}" class="flex flex-col items-center justify-center">
    @csrf
    <div class="w-full py-2 px-3 bg-white mt-5 shadow-xl mb-4 rounded-full flex items-center">
        <input name="email" id="email" type="text" class="min-w-[20vw] border-none focus:outline-none" placeholder="Email">
        <i class="fas fa-envelope text-[#333]"></i>
    </div>
    <div class="w-full py-2 px-3 shadow-xl bg-white mb-4 mt-2 rounded-full flex items-center">
        <input name="password" id="password" type="password" class="min-w-[20vw] border-none focus:outline-none" placeholder="Password">
        <i class="fas fa-lock text-[#333]"></i>
    </div>
    <button 
        type="submit"
        class="w-full hover:cursor-pointer hover:opacity-80 py-3 bg-[#333] text-white font-bold rounded-full mt-4 shadow-xl"
    >
        Login
    </button>
</form>
@endsection