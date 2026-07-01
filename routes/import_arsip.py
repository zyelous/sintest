# routes/import_arsip.py
from fastapi import APIRouter, UploadFile, File
import pandas as pd

router = APIRouter()

@router.post("/api/import-arsip")
async def import_arsip(file: UploadFile = File(...)):
    df = pd.read_excel(file.file, sheet_name="Daftar Arsip aktif", header=7)
    
    # Bersihkan data
    df = df.dropna(how='all').fillna('')
    
    # Insert ke database (contoh SQLAlchemy / raw SQL)
    for _, row in df.iterrows():
        # Sesuaikan dengan model tabel Anda
        db.execute("""
            INSERT INTO arsip_aktif 
            (kode_klasifikasi, no_berkas, uraian_berkas, tanggal_diarsipkan, ...)
            VALUES (?, ?, ?, ?, ...)
        """, (row['Kode Klasifikasi '], row['No. Berkas '], ...))
    
    return {"status": "success", "imported": len(df)}