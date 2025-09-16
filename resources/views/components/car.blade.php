<a href="{{ route('booking-view', ['car' => $id, 'waktu' => request()->query('waktu')]) }}"
    class="w-[23.5%] max-sm:w-[95%] max-sm:ml-0 max-sm:mr-0 ml-[1%] mr-[.5%] mb-10 shadow-xl hover:cursor-pointer rounded rounded-xl relative border border-[#CCC]">
    <img 
        src="{{ $photo }}" 
        alt="" 
        class="rounded rounded-full border border-[#DDD] absolute top-[-5%] right-[-5%] w-[80px] 
            max-sm:h-[50px] max-sm:w-[50px] h-[80px] max-sm:top-[-3%] max-sm:right-[-3%] 
            object-cover object-center"
    >
    <img 
        src="{{ $image }}" 
        alt="" 
        class="w-full rounded-t-xl h-[250px] object-cover object-center"
    >
    <div class="px-3 py-2 bg-[#333] rounded-b-xl py-3 px-1">
        <h1 class="text-white text-lg font-bold">
            {{ $car }}
        </h1>
        <h2 class="text-white text-md">
            {{ $name }}
        </h2>
    </div>
</a>