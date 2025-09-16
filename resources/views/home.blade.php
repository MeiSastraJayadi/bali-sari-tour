@extends('layout.client')


@section('style')
<style>
    .bg-slide {
        background-size: cover;
        background-position: center;
    }
</style>
@endsection


@section('content')
<div class="md:container max-sm:w-full max-sm:px-2 mt-24 min-h-[200vh] max-sm:min-h-[100vh]">
    <div class="w-full bg-cover bg-center relative h-[70vh] max-sm:h-[50vh] rounded rounded-xl">
        <div class="bg-cover bg-center absolute inset-0 rounded rounded-xl" id="slideshow"></div>
        <div class="absolute top-0 right-0 left-0 bottom-0 
            rounded rounded-xl bg-[rgba(0,0,0,.4)] flex-col px-12 py-4 flex items-start justify-center">
            <h1 class="text-white text-2xl font-bold">
                Selamat Datang di
            </h1>
            <h1 class="text-white text-4xl font-bold mt-2">
                BALI SARI TOUR
            </h1>
        </div>
        <div class="bottom-[-7%] max-sm:bottom-[-20%] w-full flex justify-center absolute">
            @include('components.search-bar', ["kategori" => $kategoriMobil])
        </div>
    </div>
    <div class="max-sm:mt-24 max-sm:pt-4 w-full flex flex-col justify-start items-start flex-wrap mt-12">
        <h1 class="text-lg text-[#333] mt-1 font-bold ml-2">Pilih Kategori</h1>
        <div class="flex items-center justify-start mt-2">
            @foreach ($kategoriMobil as $type)
                @include('components.category-button', ['label' => $type -> nama_kategori])
            @endforeach
        </div>
        <div class="flex items-start max-sm:items-center max-sm:justify-center max-sm:flex-col justify-start w-full flex-wrap mt-12">
            @foreach ($mobil as $item)
                @include('components.car', [
                    "car" => $item->nama_mobil, 
                    "name" => $item->owner->nama_lengkap, 
                    "photo" => $item->owner->user->profile_photo_url, 
                    "image" => $item->foto_mobil, 
                    "id" => $item -> id
                ])
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const slideshow = document.getElementById('slideshow');
    slideshow.style.backgroundImage = `url('/images/aron-visuals-1kdIG_258bU-unsplash.jpg')`
    
</script>
@endsection