<div class="container py-4">

    <h2 class="h4 fw-bold mb-4">Reporte de ventas</h2>

    {{-- FILTRO DE FECHAS --}}
    <div class="row g-2 align-items-end mb-4">
        <div class="col-auto">
            <label class="form-label small mb-1">Fecha inicial</label>
            <input type="date" wire:model.live="fechaInicio" class="form-control form-control-sm">
        </div>
        <div class="col-auto">
            <label class="form-label small mb-1">Fecha final</label>
            <input type="date" wire:model.live="fechaFin" class="form-control form-control-sm">
        </div>
    </div>

    {{-- RESUMEN GENERAL --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">N° de ventas</div>
                <div class="h5 mb-0">{{ $this->cantidadVentas }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Total vendido</div>
                <div class="h5 mb-0">S/ {{ number_format($this->totalGeneral, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- LISTA DE PRODUCTOS VENDIDOS --}}
    <h3 class="h6 fw-bold mb-2">Productos vendidos</h3>
    @if ($this->productosVendidos->isEmpty())
        <p class="text-muted small">No hay ventas registradas en este rango de fechas.</p>
    @else
        <table class="table table-sm align-middle mb-4">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th class="text-end">Cantidad</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->productosVendidos as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['talla'] }}</td>
                        <td>{{ $item['color'] }}</td>
                        <td class="text-end">{{ $item['cantidad'] }}</td>
                        <td class="text-end">S/ {{ number_format($item['subtotal'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- TOTALES POR MÉTODO DE PAGO --}}
    <h3 class="h6 fw-bold mb-2">Totales por método de pago</h3>
    <div class="row g-2">
        @foreach ($this->totalesPorMetodo as $metodo => $monto)
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted small">{{ ucfirst(strtolower($metodo)) }}</div>
                    <div class="h6 mb-0">S/ {{ number_format($monto, 2) }}</div>
                </div>
            </div>
        @endforeach
    </div>

</div>
