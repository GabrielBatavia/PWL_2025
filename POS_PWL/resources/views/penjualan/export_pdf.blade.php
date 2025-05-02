<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body{font-family:"Times New Roman",Times,serif;margin:0 20px;}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid;padding:4px 3px;font-size:11pt}
    .no-border{border:0}
    .text-center{text-align:center}
</style>
</head>
<body>
    <h3 class="text-center">Daftar Penjualan</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Kasir</th>
                <th>Pembeli</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $i=>$p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($p->penjualan_tanggal)->format('d/m/Y H:i') }}</td>
                <td>{{ $p->penjualan_kode }}</td>
                <td>{{ $p->user->nama ?? '-' }}</td>
                <td>{{ $p->pembeli }}</td>
                <td>
                    {{
                        number_format(
                            $p->penjualanDetail->sum(function($d){return $d->jumlah*$d->harga;}),0,',','.'
                        )
                    }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
