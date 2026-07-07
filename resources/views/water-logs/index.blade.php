@extends('layouts.app')

@section('title', 'Log Kualitas Air')
@section('header_title', 'Catatan Parameter Kualitas Air')

@section('content')
<div class="space-y-6">

    <!-- Add Water Log Form (sekarang full-width, 14 parameter) -->
    <div class="bg-slate-card border border-gold/10 p-6 rounded-2xl shadow-lg">
        <h3 class="text-base font-serif-heading text-white mb-5 flex items-center gap-2">
            <span class="w-1 h-5 bg-gold rounded-full inline-block"></span>
            Catat Parameter Air Baru
            <span class="text-xs text-gray-500 font-sans normal-case font-normal ml-2">(14 parameter lengkap sesuai model prediksi)</span>
        </h3>
        <form id="waterLogForm" action="{{ route('water-logs.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div id="errorContainer" class="hidden text-rose-400 text-xs bg-rose-500/10 border border-rose-500/20 p-3 rounded-lg mb-4">
            </div>

            <div>
                <label for="pond_id" class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Pilih Kolam</label>
                <select name="pond_id" id="pond_id" required
                    class="w-full md:w-1/3 bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-4 py-3 text-white focus:outline-none transition-colors text-sm">
                    <option value="">— Pilih Kolam —</option>
                    @foreach($ponds as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            @if(request()->has('sim_ph'))
                <div class="bg-gold/10 border border-gold/20 p-3 rounded-lg text-xs text-gold flex items-center justify-between">
                    <span>⚡ Data dari Simulator Aktif</span>
                    <a href="{{ route('water-logs.index') }}" class="underline hover:text-white">Reset</a>
                </div>
            @endif

            <!-- 14 Parameter Air, dikelompokkan agar lebih mudah dibaca -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">pH Air</label>
                    <input type="number" step="0.01" name="ph" required placeholder="7.0"
                        value="{{ request('sim_ph') ?? old('ph') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Suhu °C</label>
                    <input type="number" step="0.1" name="temperature" required placeholder="28.0"
                        value="{{ request('sim_temp') ?? old('temperature') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">DO mg/L</label>
                    <input type="number" step="0.01" name="dissolved_oxygen" required placeholder="5.0"
                        value="{{ request('sim_do') ?? old('dissolved_oxygen') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Turbidity cm</label>
                    <input type="number" step="0.01" name="turbidity" required placeholder="15.0"
                        value="{{ old('turbidity') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">BOD mg/L</label>
                    <input type="number" step="0.01" name="bod" required placeholder="3.0"
                        value="{{ old('bod') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">CO2 mg/L</label>
                    <input type="number" step="0.01" name="co2" required placeholder="9.0"
                        value="{{ old('co2') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Alkalinity mg/L</label>
                    <input type="number" step="0.01" name="alkalinity" required placeholder="120"
                        value="{{ old('alkalinity') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Hardness mg/L</label>
                    <input type="number" step="0.01" name="hardness" required placeholder="150"
                        value="{{ old('hardness') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Calcium mg/L</label>
                    <input type="number" step="0.01" name="calcium" required placeholder="80"
                        value="{{ old('calcium') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Ammonia mg/L</label>
                    <input type="number" step="0.001" name="ammonia" required placeholder="0.05"
                        value="{{ old('ammonia') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Nitrite mg/L</label>
                    <input type="number" step="0.001" name="nitrite" required placeholder="0.02"
                        value="{{ old('nitrite') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Phosphorus mg/L</label>
                    <input type="number" step="0.001" name="phosphorus" required placeholder="0.01"
                        value="{{ old('phosphorus') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">H2S mg/L</label>
                    <input type="number" step="0.001" name="h2s" required placeholder="0.01"
                        value="{{ old('h2s') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Plankton No./L</label>
                    <input type="number" step="0.01" name="plankton" required placeholder="500"
                        value="{{ old('plankton') }}"
                        class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data text-center">
                </div>
            </div>

            <button type="submit" id="btnSubmit" class="bg-gold hover:bg-gold-hover text-obsidian font-bold py-3 px-8 rounded-lg transition-all text-sm flex items-center justify-center gap-2 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Kirim & Analisis
            </button>
        </form>

<script>
    document.getElementById('waterLogForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnSubmit');
        const errorContainer = document.getElementById('errorContainer');
        
        btn.disabled = true;
        errorContainer.classList.add('hidden');

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (res.ok) {
                window.location.reload(); // Reload on success
                return;
            }
            if (res.status === 422) {
                return res.json().then(data => { throw data; });
            }
            throw new Error('Server error');
        })
        .catch(err => {
            btn.disabled = false;
            
            if (err.errors) {
                errorContainer.classList.remove('hidden');
                let errorHtml = '<strong>Periksa kembali input anda:</strong><ul class="list-disc list-inside mt-2">';
                Object.values(err.errors).forEach(messages => {
                    messages.forEach(msg => {
                        errorHtml += `<li>${msg}</li>`;
                    });
                });
                errorHtml += '</ul>';
                errorContainer.innerHTML = errorHtml;
            } else {
                alert("Terjadi kesalahan: " + (err.message || "Gagal memproses analisis."));
            }
        });
    });
</script>
    </div>

    <!-- Filter, Search & Sort -->
    <div class="bg-slate-card border border-gold/10 p-6 rounded-2xl shadow-lg">
        <h3 class="text-base font-serif-heading text-white mb-5 flex items-center gap-2">
            <span class="w-1 h-5 bg-gold rounded-full inline-block"></span>
            Filter & Pencarian Data
        </h3>
        <form action="{{ route('water-logs.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-gray-400 text-xs font-semibold mb-1.5 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full bg-obsidian border border-gold/20 focus:border-gold rounded-lg px-3 py-3 text-white focus:outline-none transition-colors text-sm">
                    <option value="">Semua</option>
                    <option value="Optimal" {{ request('status') === 'Optimal' ? 'selected' : '' }}>✅ Optimal</option>
                    <option value="Atensi" {{ request('status') === 'Atensi' ? 'selected' : '' }}>⚠️ Atensi</option>
                    <option value="Kritis" {{ request('status') === 'Kritis' ? 'selected' : '' }}>🔴 Kritis</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-3 justify-end mt-1">
                <a href="{{ route('water-logs.index') }}" class="border border-gold/20 text-gold hover:bg-gold/5 px-5 py-2.5 rounded-lg text-sm transition-all flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Reset
                </a>
                <button type="submit" class="bg-gold hover:bg-gold-hover text-obsidian font-bold px-6 py-2.5 rounded-lg text-sm transition-all flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-card border border-gold/10 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gold/10 flex items-center justify-between">
            <h3 class="text-base font-serif-heading text-white flex items-center gap-2">
                <span class="w-1 h-5 bg-gold rounded-full inline-block"></span>
                Riwayat Catatan Kualitas Air
            </h3>
            <span class="text-xs text-gray-500 bg-obsidian/50 px-3 py-1.5 rounded-full border border-gold/10">
                {{ $waterLogs->total() }} total catatan
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-obsidian/40 border-b border-gold/10">
                    <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400">Kolam</th>
                    <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400 text-center">pH</th>

                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400 text-center">Suhu °C</th>
                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400 text-center">DO mg/L</th>
                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400 text-center">Status</th>
                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400">Rekomendasi Tindakan</th>
                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400">Waktu Catat</th>
                        <th class="px-5 py-4 text-[11px] font-semibold uppercase tracking-widest text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($waterLogs as $log)
                        <tr class="border-b border-gold/5 hover:bg-obsidian/30 transition-colors">
                            <td class="px-5 py-4 text-sm text-white">
                                {{ $log->pond->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-300 text-center font-mono-data">{{ $log->ph }}</td>
                            <td class="px-5 py-4 text-sm text-gray-300 text-center font-mono-data">{{ $log->temperature }}</td>
                            <td class="px-5 py-4 text-sm text-gray-300 text-center font-mono-data">{{ $log->dissolved_oxygen }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($log->status === 'Optimal')
                                    <span class="text-xs bg-green-500/10 text-green-400 px-2.5 py-1 rounded-full border border-green-500/20">✅ Optimal</span>
                                @elseif($log->status === 'Atensi')
                                    <span class="text-xs bg-yellow-500/10 text-yellow-400 px-2.5 py-1 rounded-full border border-yellow-500/20">⚠️ Atensi</span>
                                @else
                                    <span class="text-xs bg-red-500/10 text-red-400 px-2.5 py-1 rounded-full border border-red-500/20">🔴 Kritis</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-400 max-w-xs">{{ $log->recommendation }}</td>
                            <td class="px-5 py-4 text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('water-logs.edit', $log->id) }}" class="text-gold hover:text-gold-hover text-xs font-medium mr-3">Edit</a>
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('water-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gold/10">
            {{ $waterLogs->links() }}
        </div>
    </div>

</div>
@endsection