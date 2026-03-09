<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->trx_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            margin-bottom: 15px;
        }

        table td {
            padding: 3px 0;
        }

        .item-name {
            width: 60%;
        }

        .item-qty {
            width: 15%;
            text-align: center;
        }

        .item-price {
            width: 25%;
            text-align: right;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .total-section {
            margin-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total-row.grand {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    @for ($i = 1; $i <= 2; $i++)
            <div class="receipt-content" style="{{ $i == 2 ? 'margin-top: 30px; border-top: 2px dashed #000; padding-top: 30px;' : '' }}">
                <div class="header">
            <h2>Cerita Coffee</h2>
            <p>Jl. Contoh No. 123</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="info">
            <p>No. Transaksi: {{ $transaction->trx_no }}</p>
            <p>Tanggal: {{ $transaction->trx_date->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ $transaction->user->name }}</p>
                    <p><strong>Cetak: {{ $i == 1 ? 'Pelanggan' : 'Toko' }}</strong></p>
        </div>

        <div class="separator"></div>

        <table>
            <tbody>
                @foreach($transaction->items as $item)
                    <tr>
                        <td class="item-name">{{ $item->product->name }}</td>
                        <td class="item-qty">{{ $item->qty }}x</td>
                        <td class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row grand">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Metode</span>
                <span>{{ $transaction->payment_method }}</span>
            </div>
            <div class="total-row">
                <span>Bayar</span>
                <span>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima Kasih</p>
            <p>Atas Kunjungan Anda</p>
        </div>
            </div>
    @endfor

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Struk</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">Tutup</button>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>