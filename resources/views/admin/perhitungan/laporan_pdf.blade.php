<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Perankingan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">LAPORAN HASIL PERANKINGAN DSS HILIRISASI</h2>

    <table>
        <thead>
            
            <tr>
                <th>No</th>
                <th>Alternatif</th>
                <th>Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($ranking as $nama => $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $nama }}</td>
                    <td>{{ number_format($data['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
