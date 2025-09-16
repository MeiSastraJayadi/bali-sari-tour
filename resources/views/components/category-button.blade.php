<button 
    x-data="{ currentMobil: new URLSearchParams(window.location.search).get('mobil') }"
    @click="
        const url = new URL(window.location.href);
        if (currentMobil === '{{ $label }}') {
            url.searchParams.delete('mobil');
        } else {
            url.searchParams.set('mobil', '{{ $label }}');
        }
        window.location.href = url.toString();
    "
    class="text-sm px-5 hover:cursor-pointer py-2 rounded mx-1 rounded-full border border-[#333] shadow shadow-lg"
    :class="currentMobil === '{{ $label }}' ? 'bg-[#333] text-white' : 'text-[#333]'"
>
    {{ $label }}
</button>
