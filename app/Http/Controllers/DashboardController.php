<?php

namespace App\Http\Controllers;

use App\Models\Pond;
use App\Models\WaterLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPonds = Pond::count();
        $totalLogs = WaterLog::count();
        
        // Calculate averages
        $avgPh = WaterLog::avg('ph') ?? 7.0;
        $avgTemp = WaterLog::avg('temperature') ?? 28.0;
        $avgDo = WaterLog::avg('dissolved_oxygen') ?? 5.0;

        // Status distributions
        $optimalCount = WaterLog::where('status', 'Optimal')->count();
        $atensiCount = WaterLog::where('status', 'Atensi')->count();
        $kritisCount = WaterLog::where('status', 'Kritis')->count();

        // Recent logs
        $recentLogs = WaterLog::with('pond')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalPonds', 'totalLogs', 'avgPh', 'avgTemp', 'avgDo',
            'optimalCount', 'atensiCount', 'kritisCount', 'recentLogs'
        ));
    }

    public function showAnalyzer()
    {
        $ponds = Pond::all();
        return view('analyzer', compact('ponds'));
    }

    public function processAnalyzer(Request $request)
    {
        $request->validate([
            'ph' => 'required|numeric|between:0,14',
            'temp' => 'required|numeric|between:0,50',
            'do' => 'required|numeric|between:0,20',
            'turbidity' => 'required|numeric|between:0,200',
            'bod' => 'required|numeric|between:0,20',
            'co2' => 'required|numeric|between:0,50',
            'alkalinity' => 'required|numeric|between:0,300',
            'hardness' => 'required|numeric|between:0,300',
            'calcium' => 'required|numeric|between:0,300',
            'ammonia' => 'required|numeric|between:0,5',
            'nitrite' => 'required|numeric|between:0,10',
            'phosphorus' => 'required|numeric|between:0,5',
            'h2s' => 'required|numeric|between:0,2',
            'plankton' => 'required|numeric|between:0,10000',
        ], [
            'ph.between' => 'pH harus antara 0 - 14.',
            'temp.between' => 'Suhu harus antara 0 - 50.',
            'do.between' => 'DO harus antara 0 - 20.',
            'turbidity.between' => 'Turbidity harus antara 0 - 200.',
            'bod.between' => 'BOD harus antara 0 - 20.',
            'co2.between' => 'CO2 harus antara 0 - 50.',
            'alkalinity.between' => 'Alkalinity harus antara 0 - 300.',
            'hardness.between' => 'Hardness harus antara 0 - 300.',
            'calcium.between' => 'Calcium harus antara 0 - 300.',
            'ammonia.between' => 'Ammonia harus antara 0 - 5.',
            'nitrite.between' => 'Nitrite harus antara 0 - 10.',
            'phosphorus.between' => 'Phosphorus harus antara 0 - 5.',
            'h2s.between' => 'H2S harus antara 0 - 2.',
            'plankton.between' => 'Plankton harus antara 0 - 10000.',
        ]);

        // Request analysis to FastAPI
        $fastapiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001');
        try {
            $response = Http::timeout(5)->post($fastapiUrl . '/analyze', [
                'ph' => floatval($request->ph),
                'temp' => floatval($request->temp),
                'do' => floatval($request->do),
                'turbidity' => floatval($request->turbidity),
                'bod' => floatval($request->bod),
                'co2' => floatval($request->co2),
                'alkalinity' => floatval($request->alkalinity),
                'hardness' => floatval($request->hardness),
                'calcium' => floatval($request->calcium),
                'ammonia' => floatval($request->ammonia),
                'nitrite' => floatval($request->nitrite),
                'phosphorus' => floatval($request->phosphorus),
                'h2s' => floatval($request->h2s),
                'plankton' => floatval($request->plankton),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return response()->json($result);
            }
        } catch (\Exception $e) {
            Log::warning('FastAPI offline in analysis sandbox: ' . $e->getMessage());
        }

        // Local fallback
        return response()->json([
            'status' => 'Atensi',
            'recommendation' => 'Peringatan: Layanan backend FastAPI sedang offline atau gagal memproses data (Error: ' . ($e->getMessage() ?? 'Unknown') . '). Menggunakan analisis cadangan lokal.'
        ]);
    }

    public function showConsultation()
    {
        return view('consultation');
    }

    public function processConsultation(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        try {
            $fastapiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001');
            $response = Http::timeout(3)->post($fastapiUrl . '/consult', [
                'message' => $request->message,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            Log::warning('FastAPI offline in consultation: ' . $e->getMessage());
        }

        return response()->json([
            'response' => 'Maaf, layanan konsultasi budidaya (FastAPI) sedang offline. Silakan coba sesaat lagi.'
        ]);
    }
}
