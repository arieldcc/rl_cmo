<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Data Sales</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Target Sebelumnya</th>
                <th>Capaian Sebelumnya</th>
                <th>Potongan DP</th>
                <th>Target Berikutnya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->bulan }}</td>
                <td>{{ number_format($row->target_sebelumnya, 0, ',', '.') }}</td>
                <td>{{ number_format($row->capaian_sebelumnya, 0, ',', '.') }}</td>
                <td>{{ number_format($row->potongan_dp, 0, ',', '.') }}</td>
                <td>{{ number_format($row->target_berikutnya, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
