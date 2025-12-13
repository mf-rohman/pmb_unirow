<!DOCTYPE html>
<html>
<head>
    <title>Kartu Peserta PMB</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
        .title { font-size: 20px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 12px; margin: 5px 0; }
        
        .card-box { border: 1px solid #000; padding: 20px; position: relative; }
        .photo-box { 
            position: absolute; right: 20px; top: 20px; 
            width: 113px; height: 151px;
            border: 1px solid #ccc; background: #f0f0f0; text-align: center; line-height: 150px; color: #aaa;
        }
        .photo-img { width: 100%; height: 100%; object-fit: cover; }

        table { width: 100%; margin-top: 10px; }
        td { padding: 5px; vertical-align: top; }
        .label { width: 140px; font-weight: bold; }
        .colon { width: 10px; }
        
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
        .signature { margin-top: 50px; text-align: right; margin-right: 50px; }
        .no-reg { 
            font-size: 24px; font-weight: bold; letter-spacing: 2px; 
            border: 2px solid #333; display: inline-block; padding: 5px 15px; margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">Universitas PGRI Ronggolawe</h1>
        <p class="subtitle">Jl. Manunggal No.61, Tuban, Jawa Timur | Telp: (0356) 322233</p>
        <h2 style="margin-top:10px;">KARTU TANDA PESERTA PMB 2026</h2>
    </div>

    <div style="text-align: center;">
        <div class="no-reg">{{ $pendaftar->no_pendaftaran }}</div>
    </div>

    <div class="card-box">
        <div class="photo-box">
            @php
                // Cari foto terbaru
                $foto = $pendaftar->dokumenPendaftars->firstWhere('jenis_dokumen', 'Foto Terbaru');
            @endphp
            
            @if($foto)
                <img src="{{ public_path('storage/' . $foto->path_file) }}" class="photo-img">
            @else
                FOTO 3x4
            @endif
        </div>

        <table style="width: 70%;">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td>{{ strtoupper($pendaftar->nama_lengkap) }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->nik }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->asal_sekolah }}</td>
            </tr>
            <tr>
                <td class="label">Jalur Pendaftaran</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->jalurPendaftaran->nama_jalur }}</td>
            </tr>
            <tr>
                <td class="label">Pilihan Prodi 1</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->programStudi1->nama_prodi }}</td>
            </tr>
            <tr>
                <td class="label">Pilihan Prodi 2</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->programStudi2->nama_prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Gelombang</td>
                <td class="colon">:</td>
                <td>{{ $pendaftar->gelombang->nama_gelombang }}</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <p>Tuban, {{ $tanggal_cetak }}</p>
        <p>Ketua Panitia PMB,</p>
        <br><br><br>
        <p><strong>( _______________________ )</strong></p>
    </div>

    <div class="footer">
        <p><i>*Kartu ini wajib dibawa saat mengikuti ujian atau daftar ulang.</i></p>
        <p><i>Dicetak secara otomatis oleh sistem pada {{ now() }}</i></p>
    </div>

</body>
</html>