<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Penilaian</title>
    <style>
        @page {
            size: landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .header img {
            width: 80px;
            height: 80px;
        }
        .header-text {
            flex: 1;
            text-align: center;
        }
        .header-text h2, .header-text h4 {
            margin: 0;
            padding: 0;
        }
        hr {
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo"> <!-- 🔥 Ganti 'logo.png' dengan path logo kamu -->
        <div class="header-text">
            <h2>LAPORAN DATA PENILAIAN</h2>
            <h4>{{ date('d-m-Y') }}</h4>
        </div>
        <div style="width: 80px;">&nbsp;</div> <!-- Biar kanan dan kiri seimbang -->
    </div>
    <hr>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Penerima</th>
                <th>Nama Penerima</th>
                <th>Skor Rumah</th>
                <th>Skor Kendaraan</th>
                <th>Skor Pendapatan</th>
                <th>Skor Tanggungan</th>
                <th>Total Skor</th>
                <th>Status Kelayakan</th>
                <th>Tanggal Penilaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_penerima }}</td>
                <td>{{ $item->penerima->nama ?? '-' }}</td>
                <td>{{ $item->skor_rumah }}</td>
                <td>{{ $item->skor_kendaraan }}</td>
                <td>{{ $item->skor_pendapatan }}</td>
                <td>{{ $item->skor_tanggungan }}</td>
                <td>{{ $item->skor_total }}</td>
                <td>{{ $item->status->nama_status ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_penilaian)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
