import os
import uvicorn
import joblib
import pandas as pd
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

app = FastAPI(title="AquaPredict Analysis Service", version="1.1.0")

# Enable CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], #Menentukan siapa saja yang diizinkan mengakses API
    allow_credentials=True,
    allow_methods=["*"], #Mengizinkan semua metode HTTP (GET, POST, PUT, DELETE)
    allow_headers=["*"], #Mengizinkan semua header HTTP
)

class AnalysisRequest(BaseModel):
    # 14 parameter asli, sesuai fitur yang dipakai saat training model
    temp: float
    turbidity: float
    do: float           # Dissolved Oxygen
    bod: float
    co2: float
    ph: float
    alkalinity: float
    hardness: float
    calcium: float
    ammonia: float
    nitrite: float
    phosphorus: float
    h2s: float
    plankton: float

class AnalysisResponse(BaseModel):
    status: str  # Optimal, Atensi, Kritis
    recommendation: str

class ConsultRequest(BaseModel):
    message: str

class ConsultResponse(BaseModel):
    response: str

# Load the Scikit-learn Model
MODEL_PATH = os.path.join(os.path.dirname(__file__), "model_kualitas_air.joblib")
if os.path.exists(MODEL_PATH):
    model = joblib.load(MODEL_PATH)
    print(f"Model loaded successfully from {MODEL_PATH}")
else:
    model = None
    print(f"WARNING: Model file not found at {MODEL_PATH}")

# Generic threshold configurations for rule-based recommendation generation
GENERIC_THRESHOLDS = {"ph": (6.5, 8.5), "temp": (25.0, 32.0), "do": 3.0}


@app.post("/analyze", response_model=AnalysisResponse)
def analyze_water(request: AnalysisRequest):
    ph = request.ph
    temp = request.temp
    do = request.do

    # 1. Prediction using Scikit-Learn Model
    if model is not None:
        try:
            input_df = pd.DataFrame([{
                'Temp': request.temp,
                'Turbidity (cm)': request.turbidity,
                'DO(mg/L)': request.do,
                'BOD (mg/L)': request.bod,
                'CO2': request.co2,
                'pH': request.ph,
                'Alkalinity (mg L-1 )': request.alkalinity,
                'Hardness (mg L-1 )': request.hardness,
                'Calcium (mg L-1 )': request.calcium,
                'Ammonia (mg L-1 )': request.ammonia,
                'Nitrite (mg L-1 )': request.nitrite,
                'Phosphorus (mg L-1 )': request.phosphorus,
                'H2S (mg L-1 )': request.h2s,
                'Plankton (No. L-1)': request.plankton,
            }])

            pred = int(model.predict(input_df)[0])
            status_map = {0: "Optimal", 1: "Atensi", 2: "Kritis"}
            status = status_map.get(pred, "Atensi")
        except Exception as e:
            print(f"Error during model prediction: {e}")
            status = "Atensi"
    else:
        status = "Optimal"
        ph_min, ph_max = GENERIC_THRESHOLDS["ph"]
        t_min, t_max = GENERIC_THRESHOLDS["temp"]
        do_min = GENERIC_THRESHOLDS["do"]

        if ph < ph_min or ph > ph_max or temp < t_min or temp > t_max or do < do_min:
            status = "Atensi"
            if ph < ph_min - 0.5 or ph > ph_max + 0.5 or temp > t_max + 2.0 or do < do_min - 1.0:
                status = "Kritis"

    # 2. Rule-based checks for generic recommendations
    ph_min, ph_max = GENERIC_THRESHOLDS["ph"]
    t_min, t_max = GENERIC_THRESHOLDS["temp"]
    do_min = GENERIC_THRESHOLDS["do"]

    issues = []
    if ph < ph_min:
        issues.append(f"pH terlalu asam ({ph:.2f} < {ph_min})")
    elif ph > ph_max:
        issues.append(f"pH terlalu basa ({ph:.2f} > {ph_max})")

    if temp < t_min:
        issues.append(f"Suhu terlalu rendah ({temp:.1f}°C < {t_min}°C)")
    elif temp > t_max:
        issues.append(f"Suhu terlalu tinggi ({temp:.1f}°C > {t_max}°C)")

    if do < do_min:
        issues.append(f"Kadar Oksigen Terlarut (DO) rendah ({do:.2f} mg/L < {do_min} mg/L)")

    if request.ammonia > 0.5:
        issues.append(f"Ammonia tinggi ({request.ammonia:.3f} mg/L)")
    if request.h2s > 0.05:
        issues.append(f"H2S terdeteksi tinggi ({request.h2s:.3f} mg/L)")

    # 3. Formulate response
    if status == "Optimal" and not issues:
        recommendation = "Kondisi air kolam terpantau sangat baik dan optimal. Pertahankan pengelolaan pakan serta sirkulasi air rutin."
    else:
        rec_list = []
        for issue in issues:
            if "pH terlalu asam" in issue:
                rec_list.append("Taburkan kapur pertanian (dolomit) dosis 10-20 gram per meter kubik untuk menaikkan pH secara perlahan.")
            elif "pH terlalu basa" in issue:
                rec_list.append("Lakukan pergantian air bersih sebanyak 10-20% atau tambahkan molase/probiotik untuk menstabilkan alkalinitas air.")
            elif "Suhu terlalu rendah" in issue:
                rec_list.append("Kurangi pemberian pakan pelet untuk mencegah pengendapan pakan tidak termakan, atau tambahkan penutup kolam/terpal.")
            elif "Suhu terlalu tinggi" in issue:
                rec_list.append("Lakukan penambahan air bersih dingin secara perlahan dan tingkatkan sirkulasi air untuk menurunkan suhu kolam.")
            elif "Oksigen Terlarut (DO) rendah" in issue:
                rec_list.append("Nyalakan aerator tambahan / kincir air segera dan kurangi frekuensi pemberian pakan pelet sementara waktu.")
            elif "Ammonia tinggi" in issue:
                rec_list.append("Lakukan penyifonan dasar kolam dan kurangi sisa pakan; pertimbangkan penambahan probiotik pengurai amonia.")
            elif "H2S" in issue:
                rec_list.append("Tingkatkan aerasi dan lakukan pembersihan lumpur dasar kolam yang dapat memicu gas H2S beracun.")

        if not rec_list:
            rec_list.append("Periksa sirkulasi air dan tingkatkan aerasi kolam untuk menjaga kualitas air tetap stabil.")

        recommendation = f"Status Air: {status}. Ditemukan kendala: {', '.join(issues) if issues else 'deviasi ringan'}. Rekomendasi Mitigasi: " + " ".join(rec_list)

    return AnalysisResponse(status=status, recommendation=recommendation)


@app.post("/consult", response_model=ConsultResponse)
def consult(request: ConsultRequest):
    msg = request.message.lower()

    if "ph" in msg or "asam" in msg or "basa" in msg:
        res = "pH air yang ideal umumnya berada di kisaran 6.5 - 8.5. pH yang terlalu rendah (asam) dapat dinaikkan dengan kapur dolomit, sedangkan pH yang terlalu tinggi (basa) dapat diturunkan dengan pergantian air atau penambahan bahan organik seperti molase."
    elif "oksigen" in msg or "do" in msg or "aerasi" in msg or "aerator" in msg:
        res = "Kadar Oksigen Terlarut (DO) sangat krusial untuk respirasi biota air. Minimal DO sebaiknya di atas 3-4 mg/L. Jika DO rendah, segera tingkatkan aerasi menggunakan kincir atau aerator tambahan, kurangi pemberian pakan, dan lakukan sirkulasi air."
    elif "ammonia" in msg or "bau" in msg or "lumpur" in msg:
        res = "Ammonia tinggi biasanya berasal dari sisa pakan yang membusuk. Lakukan penyifonan dasar kolam untuk membuang lumpur, kurangi jumlah pakan, dan gunakan probiotik untuk mempercepat penguraian bahan organik di dasar kolam."
    elif "suhu" in msg or "panas" in msg or "dingin" in msg:
        res = "Fluktuasi suhu ekstrem dapat membuat biota air stres. Jaga kestabilan suhu dengan mengatur sirkulasi air yang baik. Untuk suhu dingin, bisa menggunakan penutup kolam, dan untuk suhu panas, tingkatkan aerasi serta pergantian air."
    elif "bioflok" in msg or "flok" in msg:
        res = "Teknologi berbasis flok mengandalkan keseimbangan rasio Karbon (C) dan Nitrogen (N). Pastikan pH stabil di angka 7-8 dan aerasi selalu aktif 24 jam untuk menjaga flok tetap melayang dan tidak membusuk di dasar kolam."
    else:
        res = "Halo! Saya adalah Konsultan Budidaya Air Tawar Cerdas AquaPredict. Anda dapat berkonsultasi seputar pemeliharaan kualitas air seperti pengelolaan pH, kadar oksigen (DO), pengendalian ammonia, suhu, atau sistem budidaya intensif lainnya secara umum."

    return ConsultResponse(response=res)


if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=8001)