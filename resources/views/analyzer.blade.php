@extends('layouts.app')

@section('title', 'Analisis Kelayakan Air')
@section('header_title', 'Sandbox Analisis Kelayakan Air')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Card Description -->
    <div class="bg-slate-card border border-gold/10 p-6 rounded-xl">
        <h3 class="text-xl font-serif-heading text-white mb-2">Evaluasi Parameter Kualitas Air</h3>
        <p class="text-sm text-gray-400 leading-relaxed">
            Gunakan sandbox ini untuk menguji kelayakan parameter air kolam secara instan. Hasil analisis biologis dan saran mitigasi tindakan akan diproses langsung oleh service backend.
        </p>
    </div>

    <!-- Form & Output Container -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Input Form -->
        <div class="bg-slate-card border border-gold/5 p-6 rounded-xl">
            <h4 class="text-white font-medium mb-4">Input Parameter Uji</h4>
            
            <form id="sandboxForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">pH Air</label>
                        <input type="number" step="0.01" name="ph" required placeholder="7.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Suhu (°C)</label>
                        <input type="number" step="0.1" name="temp" required placeholder="28.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">DO (mg/L)</label>
                        <input type="number" step="0.01" name="do" required placeholder="5.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Turbidity (cm)</label>
                        <input type="number" step="0.01" name="turbidity" required placeholder="15.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">BOD (mg/L)</label>
                        <input type="number" step="0.01" name="bod" required placeholder="3.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">CO2 (mg/L)</label>
                        <input type="number" step="0.01" name="co2" required placeholder="9.0"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Alkalinity (mg/L)</label>
                        <input type="number" step="0.01" name="alkalinity" required placeholder="120"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Hardness (mg/L)</label>
                        <input type="number" step="0.01" name="hardness" required placeholder="150"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Calcium (mg/L)</label>
                        <input type="number" step="0.01" name="calcium" required placeholder="80"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Ammonia (mg/L)</label>
                        <input type="number" step="0.001" name="ammonia" required placeholder="0.05"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Nitrite (mg/L)</label>
                        <input type="number" step="0.001" name="nitrite" required placeholder="0.02"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Phosphorus (mg/L)</label>
                        <input type="number" step="0.001" name="phosphorus" required placeholder="0.01"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">H2S (mg/L)</label>
                        <input type="number" step="0.001" name="h2s" required placeholder="0.01"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-1.5">Plankton (No./L)</label>
                        <input type="number" step="0.01" name="plankton" required placeholder="500"
                            class="w-full bg-obsidian border border-gold/25 focus:border-gold rounded-lg px-4 py-2 text-white placeholder-gray-700 focus:outline-none transition-colors text-sm font-mono-data">
                    </div>
                </div>

                <div id="errorContainer" class="hidden text-rose-400 text-xs bg-rose-500/10 border border-rose-500/20 p-3 rounded-lg mb-4">
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-gold hover:bg-gold-hover text-obsidian font-bold py-3 px-4 rounded-lg transition-all gold-glow text-sm">
                    Mulai Analisis
                </button>
            </form>
        </div>

        <!-- Output Results Card -->
        <div class="bg-slate-card border border-gold/5 p-6 rounded-xl flex flex-col justify-between">
            <div>
                <h4 class="text-white font-medium mb-4">Hasil Analisis</h4>
                
                <div id="outputLoading" class="hidden py-12 text-center text-gray-500 text-sm">
                    Menganalisis parameter air...
                </div>

                <div id="outputPlaceholder" class="py-12 text-center text-gray-500 text-sm">
                    Silakan masukkan parameter dan klik "Mulai Analisis" untuk melihat hasil.
                </div>

                <div id="outputContent" class="hidden space-y-6">
                    <!-- Status Indicator -->
                    <div class="flex justify-between items-center border-b border-gold/10 pb-4">
                        <span class="text-gray-400 text-sm">Status Kelayakan</span>
                        <span id="outputStatus" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                    </div>
                    
                    <!-- Recommendations -->
                    <div>
                        <span class="text-gray-500 text-xs uppercase tracking-wider block mb-2">Panduan Lapangan / Mitigasi</span>
                        <div id="outputRecs" class="text-sm text-gray-300 leading-relaxed bg-obsidian/40 border border-gold/5 p-4 rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gold/5 mt-6 pt-4 text-xs text-gray-500">
                Penyusunan mitigasi diselaraskan dengan parameter baku mutu biota air nasional.
            </div>
        </div>

    </div>

</div>

<script>
    document.getElementById('sandboxForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnSubmit');
        const loading = document.getElementById('outputLoading');
        const placeholder = document.getElementById('outputPlaceholder');
        const content = document.getElementById('outputContent');
        const errorContainer = document.getElementById('errorContainer');
        const outStatus = document.getElementById('outputStatus');
        const outRecs = document.getElementById('outputRecs');

        btn.disabled = true;
        placeholder.classList.add('hidden');
        content.classList.add('hidden');
        errorContainer.classList.add('hidden');
        loading.classList.remove('hidden');

        const formData = new FormData(this);

        fetch("{{ route('analyzer.process') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) {
                if (res.status === 422) {
                    return res.json().then(data => { throw data; });
                }
                throw new Error('Server error');
            }
            return res.json();
        })
        .then(data => {
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            
            // Set status text and style
            outStatus.textContent = data.status;
            outStatus.className = "px-3 py-1 rounded-full text-xs font-bold ";
            
            if (data.status === 'Optimal') {
                outStatus.classList.add('bg-emerald-500/10', 'text-emerald-400', 'border', 'border-emerald-500/20');
            } else if (data.status === 'Atensi') {
                outStatus.classList.add('bg-amber-500/10', 'text-amber-400', 'border', 'border-amber-500/20');
            } else {
                outStatus.classList.add('bg-rose-500/10', 'text-rose-400', 'border', 'border-rose-500/20');
            }

            // Set recommendations
            outRecs.textContent = data.recommendation;
            btn.disabled = false;
        })
        .catch(err => {
            console.error('Fetch error:', err);
            loading.classList.add('hidden');
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
                placeholder.classList.remove('hidden');
                placeholder.textContent = "Terjadi kesalahan: " + (err.message || "Gagal memproses analisis.");
            }
        });
    });
</script>
@endsection
