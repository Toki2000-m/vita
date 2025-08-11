<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex - Vita-SmartFlow</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header small {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #2c3e50;
            color: #fff;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Vita-SmartFlow</h1>
    <small>Reporte de Movimientos de Kardex</small>
</div>

<table>
    <thead>
        <tr>
            <th style="width:10%;">Fecha</th>
            <th style="width:8%;">Tipo</th>
            <th style="width:20%;">Perfume</th>
            <th style="width:18%;">Almacén Origen</th>
            <th style="width:18%;">Almacén Destino</th>
            <th style="width:8%;">Cantidad</th>
            <th style="width:8%;">Motivo</th>
            <th style="width:10%;">Referencia</th>
        </tr>
    </thead>
    <tbody>
        @php use Carbon\Carbon; @endphp
       @foreach($movimientos as $mov)
    <tr>
        <td>
            {{ $mov->timestamp
                ? \Carbon\Carbon::parse($mov->timestamp->toDateTime())->format('d/m/Y H:i')
                : '-' }}
        </td>
        <td>{{ ucfirst($mov->tipo ?? '-') }}</td>
        <td>{{ $perfumes[(string)$mov->perfume_id] ?? '-' }}</td>
        <td>
            {{ isset($almacenes[(string)$mov->almacen_origen_id]) 
                ? $almacenes[(string)$mov->almacen_origen_id] 
                : '-' }}
        </td>
        <td>
            {{ isset($almacenes[(string)$mov->almacen_destino_id]) 
                ? $almacenes[(string)$mov->almacen_destino_id] 
                : '-' }}
        </td>
        <td>{{ $mov->cantidad ?? '-' }}</td>
        <td>{{ $mov->motivo ?? '-' }}</td>
        <td>{{ $mov->referencia ?? '-' }}</td>
    </tr>
@endforeach

    </tbody>
</table>

<div class="footer">
    Reporte generado el {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
