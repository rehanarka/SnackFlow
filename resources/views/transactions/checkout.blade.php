@extends('layouts.normal')
@section('content')
<div class="bg-slate-150">
    <div class="bg-white max-w-2xl h-screen flex mx-auto">
        <div class="flex flex-col justify-start w-full">
            <h1 class="text-3xl font-bold">Insert Your Address</h1>

            <p>Province</p>

            <input type="text" id="search" placeholder="Ketik daerah..." class="border p-2 mb-2">

            <select id="areaSelect" size="5" class="border w-full">
            </select>
        </div>
    </div>
</div>

<script>
const input = document.getElementById('search');
const select = document.getElementById('areaSelect');

let timeout = null;

input.addEventListener('keyup', function () {
    clearTimeout(timeout);

    timeout = setTimeout(async () => {
        const query = input.value;

        if (query.length < 3) return;

        const res = await fetch(`/api/areas?q=${encodeURIComponent(query)}`);
        const data = await res.json();

        select.innerHTML = '';

        if (data.success && data.areas) {
            data.areas.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;       // ID untuk backend
                option.textContent = item.name; // Nama daerah untuk ditampilkan
                select.appendChild(option);
            });
        }
    }, 300); // delay 300ms
});
</script>
@endsection