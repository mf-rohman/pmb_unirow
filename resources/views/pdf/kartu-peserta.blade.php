<!DOCTYPE html>
<html>
<head>
    <title>Kartu Peserta PMB</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; position: relative; }
        .logo { width: 70px; height: auto; position: absolute; left: 10px; top: 0; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 11px; margin: 3px 0; }
        .card-title { font-size: 14px; font-weight: bold; margin-top: 10px; text-decoration: underline; }
        
        .main-content { border: 1px solid #333; padding: 15px; position: relative; }
        
        .no-reg-box { 
            text-align: center; margin-bottom: 15px;
            background-color: #f0f0f0; border: 1px dashed #333; padding: 5px;
        }
        .no-reg { font-size: 18px; font-weight: bold; letter-spacing: 1px; }

        .photo-box { 
            position: absolute; right: 15px; top: 100px; 
            width: 113px; height: 151px; /* 3x4 cm */
            border: 1px solid #999; background: #fff; text-align: center; line-height: 150px; color: #ccc; font-size: 10px;
        }
        .photo-img { width: 100%; height: 100%; object-fit: cover; }

        table { width: 100%; margin-top: 5px; border-collapse: collapse; }
        td { padding: 3px 0; vertical-align: top; }
        .label { width: 130px; font-weight: bold; font-size: 12px; }
        .colon { width: 10px; text-align: center; }
        .value { font-weight: normal; }

        .section-title {
            font-size: 12px; font-weight: bold; text-transform: uppercase; 
            background-color: #eee; padding: 3px 5px; margin-top: 10px; margin-bottom: 5px;
            border-left: 3px solid #333;
        }

        .footer { margin-top: 20px; font-size: 10px; font-style: italic; border-top: 1px solid #ccc; padding-top: 5px; }
        
        .signature-area { margin-top: 30px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-date { margin-bottom: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }
        
        /* Clearfix */
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="header">
        <!-- Jika ada logo, uncomment baris ini -->
        <img src="{{ public_path('images/logo-unirow.png') }}" class="logo">
        <h1 class="title">Universitas PGRI Ronggolawe</h1>
        <p class="subtitle">Jl. Manunggal No.61, Tuban, Jawa Timur | Telp: (0356) 322233 | Website: pmb.unirow.ac.id</p>
        <div class="card-title">KARTU TANDA PESERTA PMB 2026</div>
    </div>

    <div class="main-content clearfix">
        
        <!-- FOTO -->
        <div class="photo-box">
            @php
                $foto = $pendaftar->dokumenPendaftars->firstWhere('jenis_dokumen', 'Foto Terbaru');
                $fotoPath = $foto ? public_path('storage/' . $foto->path_file) : null;
            @endphp
            
            @if($foto && file_exists($fotoPath))
                <img src="{{ $fotoPath }}" class="photo-img">
            @else
                FOTO 3x4
            @endif
        </div>

        <!-- NOMOR PENDAFTARAN -->
        <div class="no-reg-box">
            NO. PESERTA: <span class="no-reg">{{ $pendaftar->no_pendaftaran }}</span>
        </div>

        <!-- SECTION 1: DATA DIRI -->
        <div class="section-title">A. Data Diri</div>
        <table style="width: 70%;"> <!-- Lebar 70% agar tidak menabrak foto -->
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="value"><strong>{{ strtoupper($pendaftar->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->nik }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tgl Lahir</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->agama }}</td>
            </tr>
            <tr>
                <td class="label">No. HP / WA</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->no_hp }}</td>
            </tr>
             <tr>
                <td class="label">Email</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->email }}</td>
            </tr>
        </table>

        <!-- SECTION 2: PILIHAN PRODI & JALUR -->
        <div class="section-title" style="margin-top: 15px;">B. Data Pendaftaran</div>
        <table style="width: 100%;">
            <tr>
                <td class="label">Jalur Pendaftaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->jalurPendaftaran->nama_jalur }}</td>
            </tr>
            <tr>
                <td class="label">Gelombang</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->gelombang->nama_gelombang }}</td>
            </tr>
            <tr>
                <td class="label">Pilihan Prodi 1</td>
                <td class="colon">:</td>
                <td class="value"><strong>{{ $pendaftar->programStudi1->jenjang }} {{ $pendaftar->programStudi1->nama_prodi }}</strong></td>
            </tr>
            <tr>
                <td class="label">Pilihan Prodi 2</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->programStudi2 ? ($pendaftar->programStudi2->jenjang . ' ' . $pendaftar->programStudi2->nama_prodi) : '-' }}</td>
            </tr>
        </table>

        <!-- SECTION 3: SEKOLAH & ALAMAT -->
        <div class="section-title">C. Data Sekolah & Alamat</div>
        <table style="width: 100%;">
            <tr>
                <td class="label">Asal Sekolah</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->asal_sekolah }} ({{ $pendaftar->jurusan_asal_sekolah ?? '-' }})</td>
            </tr>
            <tr>
                <td class="label">Nama Ibu Kandung</td>
                <td class="colon">:</td>
                <td class="value">{{ $pendaftar->nama_ibu_kandung }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Lengkap</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ $pendaftar->alamat_lengkap }}, RT {{ $pendaftar->rt }}/RW {{ $pendaftar->rw }}<br>
                    {{ $pendaftar->village->name ?? '' }}, {{ $pendaftar->district->name ?? '' }}<br>
                    {{ $pendaftar->regency->name ?? '' }} - {{ $pendaftar->province->name ?? '' }}
                </td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
        <div class="signature-area clearfix">
            <div class="ttd-box">
                <div class="ttd-date">Tuban, {{ \Carbon\Carbon::parse($tanggal_cetak)->locale('id')->isoFormat('D MMMM Y') }}</div>
                <div>Ketua Panitia PMB,</div>
                <br><br><br>
                <div class="ttd-name">( ............................................ )</div>
                <div>NIP. ............................</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <strong>Catatan Penting:</strong>
        <ol style="margin-top: 5px; padding-left: 15px;">
            <li>Kartu ini adalah bukti sah pendaftaran mahasiswa baru Universitas PGRI Ronggolawe.</li>
            <li>Wajib dibawa saat mengikuti ujian seleksi (jika ada) dan saat melakukan daftar ulang.</li>
            <li>Informasi lebih lanjut kunjungi <u>pmb.unirow.ac.id</u></li>
        </ol>
    </div>

</body>
</html>