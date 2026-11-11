"""
Becks Apparel - Local NLP Chatbot Server
Server ini menangani request chatbot dari Laravel secara lokal tanpa menggunakan API cloud (seperti Gemini).

Dapat dijalankan secara otomatis lewat:
1. Laragon (Start All via Procfile)
2. Command terminal: python server_nlp.py
3. Script one-click: start-local.bat
"""

import sys
import json
import re

PORT = 8000
HOST = "127.0.0.1"

# ==============================================================================
# LOGIKA MODEL NLP LOKAL ANDA
# Anda dapat mengimpor library Anda di sini (misal: joblib, pickle, sklearn, torch)
# ==============================================================================

def process_nlp_message(user_message: str, user_id: str = "guest", history: list = None) -> dict:
    """
    Fungsi ini memproses input teks dari pengguna dan menghasilkan respons bot.
    Gantikan / hubungkan dengan model NLP buatan Anda sendiri di sini.
    """
    msg = user_message.lower().strip()
    
    # 1. Deteksi Handover ke CS Admin Manusia
    handover_keywords = ["admin", "cs", "manusia", "orang", "komplain", "marah", "batal", "retur", "bicara dengan admin"]
    if any(k in msg for k in handover_keywords):
        return {
            "status": "handover",
            "message": "[HANDOVER]"
        }
        
    # 2. Respon Berdasarkan Intent / Kata Kunci Produk Becks Apparel
    if any(k in msg for k in ["harga", "paket", "berapa", "biaya", "pricelist", "jersey"]):
        return {
            "status": "success",
            "message": "Halo Kak! Berikut daftar paket jersey Becks Apparel:\n• Paket A (Rp 90.000): Non-printing + DTF\n• Paket B (Rp 110.000): Badan polos + Lengan printing\n• Paket C (Rp 130.000): Jersey Full Printing\n• Paket D (Rp 160.000): Setelan Full Printing (Jersey + Celana)\nSemua paket gratis pilih bahan (Milano, Benzema, Wafel, dll)."
        }
        
    if any(k in msg for k in ["custom", "desain", "kustom", "3d", "buat sendiri", "bikin"]):
        return {
            "status": "success",
            "message": "Bisa banget Kak! Kami punya 'Fitur Kustomisasi' desain 3D interaktif. Kakak bisa klik menu 'Kustomisasi' di navigasi atas untuk mendesain jersey sendiri secara langsung dari HP/Laptop."
        }
        
    if any(k in msg for k in ["bahan", "kain", "milano", "dryfit", "wafel"]):
        return {
            "status": "success",
            "message": "Kami menyediakan berbagai bahan premium gratis: Dryfit Milano, Benzema, N-Tech, Wafel, Smash, dan masih banyak lagi. Semuanya adem, menyerap keringat, dan cocok untuk olahraga."
        }

    if any(k in msg for k in ["alamat", "lokasi", "toko", "offline", "dimana"]):
        return {
            "status": "success",
            "message": "Workshop Becks Apparel melayani pemesanan online ke seluruh Indonesia dengan pengiriman JNE, J&T, SiCepat, dll. Untuk info alamat fisik bisa chat langsung dengan admin kami ya Kak."
        }

    if any(k in msg for k in ["halo", "hai", "p", "siang", "pagi", "sore", "malam", "assalamualaikum"]):
        return {
            "status": "success",
            "message": "Halo Kak! Selamat datang di Becks Apparel. Ada yang bisa kami bantu seputar pembuatan jersey custom atau cek harga paket?"
        }

    # Default / Fallback Response
    return {
        "status": "success",
        "message": f"Halo Kak! Terima kasih sudah menghubungi Becks Apparel. Terkait '{user_message}', Kakak bisa tanyakan seputar harga paket jersey, fitur desain 3D, pilihan bahan, atau ketik 'admin' jika ingin bicara langsung dengan CS kami ya!"
    }


# ==============================================================================
# SERVER HTTP (Otomatis memilih FastAPI jika ada, atau built-in http.server)
# ==============================================================================

def run_fastapi():
    from fastapi import FastAPI
    from pydantic import BaseModel
    from typing import Optional, List, Dict, Any
    import uvicorn

    app = FastAPI(title="Becks Apparel Local NLP")

    class ChatPayload(BaseModel):
        message: str
        user_id: Optional[str] = "guest"
        history: Optional[List[Dict[str, Any]]] = []

    @app.post("/chatbot")
    def chatbot_route(payload: ChatPayload):
        return process_nlp_message(payload.message, payload.user_id, payload.history)

    @app.get("/chatbot")
    @app.get("/")
    def health_check():
        return {"status": "ok", "service": "Becks Local NLP Chatbot (FastAPI)"}

    print(f"[*] Menjalankan NLP Server (FastAPI) di http://{HOST}:{PORT}/chatbot")
    uvicorn.run(app, host=HOST, port=PORT, log_level="info")


def run_builtin_server():
    from http.server import HTTPServer, BaseHTTPRequestHandler

    class NLPHandler(BaseHTTPRequestHandler):
        def do_GET(self):
            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            response = {"status": "ok", "service": "Becks Local NLP Chatbot (Standard HTTP)"}
            self.wfile.write(json.dumps(response).encode("utf-8"))

        def do_POST(self):
            content_length = int(self.headers.get("Content-Length", 0))
            post_data = self.rfile.read(content_length)

            try:
                payload = json.loads(post_data.decode("utf-8")) if post_data else {}
            except Exception:
                payload = {}

            message = payload.get("message", "")
            user_id = payload.get("user_id", "guest")
            history = payload.get("history", [])

            result = process_nlp_message(message, user_id, history)

            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.send_header("Access-Control-Allow-Origin", "*")
            self.end_headers()
            self.wfile.write(json.dumps(result, ensure_ascii=False).encode("utf-8"))

        def log_message(self, format, *args):
            print(f"[NLP-Server] {self.address_string()} - {args[0]}")

    server = HTTPServer((HOST, PORT), NLPHandler)
    print(f"[*] Menjalankan NLP Server (Built-in Python) di http://{HOST}:{PORT}/chatbot")
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        print("\n[*] Server NLP dihentikan.")
        server.server_close()


if __name__ == "__main__":
    try:
        # Coba jalankan dengan FastAPI jika uvicorn tersedia
        import fastapi
        import uvicorn
        run_fastapi()
    except ImportError:
        # Fallback ke built-in server standar tanpa perlu install dependensi tambahan
        run_builtin_server()

