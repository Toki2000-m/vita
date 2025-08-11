<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerfumeRegistro;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;

class PerfumesController extends Controller
{
    public function index()
    {
        $perfumes = PerfumeRegistro::paginate(10);
        return view('config.perfumes.index', compact('perfumes'));
    }

    public function create()
    {
        return view('config.perfumes.create');
    }

    public function store(Request $request)
{
    $rules = [
        'name_per' => 'required|string|max:255',
        'descripcion_per' => 'nullable|string',
        'categoria_per' => 'nullable|string|max:255',
        'precio_venta_per' => 'required|numeric|min:0',
        'stock_per' => 'nullable|integer|min:0',
        'stock_minimo_per' => 'nullable|integer|min:0',
        'ubicacion_per' => 'nullable|string|max:100',
        'fecha_expiracion' => 'nullable|date',
        'estado' => 'required|string|in:Activo,Inactivo',
        'imagen_url' => 'nullable|string|max:1000',
        'marca' => 'nullable|string|max:255',
    ];

    $data = $request->validate($rules);

    // Validación adicional
    if (!empty($data['ubicacion_per']) && (!isset($data['stock_per']) || $data['stock_per'] === null)) {
        return back()
            ->withErrors(['stock_per' => 'El stock inicial es obligatorio si defines una ubicación.'])
            ->withInput();
    }

    // Tipado explícito
    $data['precio_venta_per'] = (float) ($data['precio_venta_per'] ?? 0);
    $data['stock_per'] = (int) ($data['stock_per'] ?? 0);
    $data['stock_minimo_per'] = $data['stock_minimo_per'] ?? 0;

    // Construcción segura de stock_por_almacen
    if (!empty($data['ubicacion_per']) && $data['stock_per'] > 0) {
        $stockObject = new BSONDocument([
            $data['ubicacion_per'] => $data['stock_per']
        ]);

        // Validación de tipo
        if (!($stockObject instanceof BSONDocument)) {
            throw new \Exception('❌ stock_por_almacen debe ser BSONDocument. Se recibió: ' . gettype($stockObject));
        }

        $data['stock_por_almacen'] = $stockObject;
        $data['stock_actual'] = $data['stock_per'];
    } else {
        $data['stock_por_almacen'] = new BSONDocument([]);
        $data['stock_actual'] = 0;
    }

    // Fechas
    $data['fecha_expiracion'] = !empty($data['fecha_expiracion'])
        ? Carbon::parse($data['fecha_expiracion'])
        : null;

    $data['createdAt'] = Carbon::now();
    $data['updatedAt'] = Carbon::now();
    $data['_id'] = new ObjectId();

    // Guardar
    $perfume = PerfumeRegistro::create($data)->fresh();

    if (!$perfume || !$perfume->_id) {
        abort(500, '❌ Error al crear el perfume');
    }

    return redirect()->route('config.perfumes.index')
        ->with('success', 'Perfume creado correctamente.');
}

    public function edit($id)
    {
        $perfume = PerfumeRegistro::find($id);

        if (!$perfume) {
            abort(404, '❌ Perfume no encontrado');
        }

        return view('config.perfumes.edit', compact('perfume'));
    }

    public function update(Request $request, $id)
    {
        $perfume = PerfumeRegistro::find($id);

        if (!$perfume) {
            abort(404, '❌ Perfume no encontrado');
        }

        $data = $request->validate([
            'name_per' => 'required|string|max:255',
            'descripcion_per' => 'nullable|string',
            'categoria_per' => 'nullable|string|max:255',
            'precio_venta_per' => 'required|numeric|min:0',
            'stock_per' => 'required|integer|min:0',
            'stock_minimo_per' => 'nullable|integer|min:0',
            'ubicacion_per' => 'nullable|string|max:100',
            'fecha_expiracion' => 'nullable|date',
            'estado' => 'required|string|in:Activo,Inactivo',
            'imagen_url' => 'nullable|string|max:1000',
            'marca' => 'nullable|string|max:255',
        ]);

        $data['precio_venta_per'] = (float) $data['precio_venta_per'];
        $data['stock_per'] = (int) $data['stock_per'];
        $data['stock_minimo_per'] = $data['stock_minimo_per'] ?? 0;
        $data['fecha_expiracion'] = !empty($data['fecha_expiracion']) ? Carbon::parse($data['fecha_expiracion']) : null;

        // Actualizar stock_por_almacen y stock_actual en update también:
        if (!empty($data['ubicacion_per']) && $data['stock_per'] > 0) {
            $data['stock_por_almacen'] = new BSONDocument([
                $data['ubicacion_per'] => $data['stock_per']
            ]);
            $data['stock_actual'] = $data['stock_per'];
        } else {
            $data['stock_por_almacen'] = new BSONDocument([]);
            $data['stock_actual'] = 0;
        }

        $data['updatedAt'] = Carbon::now();

        $perfume->update($data);

        return redirect()->route('config.perfumes.index')
            ->with('success', 'Perfume actualizado.');
    }

    public function destroy($id)
    {
        $perfume = PerfumeRegistro::find($id);

        if (!$perfume) {
            abort(404, '❌ Perfume no encontrado');
        }

        $perfume->delete();

        return redirect()->route('config.perfumes.index')
            ->with('success', 'Perfume eliminado.');
    }
}
