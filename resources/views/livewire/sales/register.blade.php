<div class="container py-4">

    @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="h4 fw-bold mb-4">Registrar venta</h2>

    {{-- BUSCADOR --}}
    <div class="position-relative mb-4">
        <input type="text" wire:model.live.debounce.300ms="busqueda" class="form-control"
            placeholder="Busca por nombre de producto o color...">

        @if (count($resultados) > 0)
            <div class="list-group position-absolute w-100 shadow" style="z-index: 10;">
                @foreach ($resultados as $variant)
                    <button type="button" wire:click="agregarItem({{ $variant->id }})"
                        class="list-group-item list-group-item-action d-flex justify-content-between">
                        <span>
                            {{ $variant->product->nombre }} — {{ $variant->color }} — {{ $variant->size->valor }}
                        </span>
                        <span class="text-muted small">
                            Stock: {{ $variant->stock }} · S/ {{ number_format($variant->precio_venta, 2) }}
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>
    {{-- BUSCADOR --}}

    {{-- ITEMS APILADOS --}}
    @if (count($items) > 0)
        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th style="width:90px">Cantidad</th>
                    <th style="width:110px">Precio venta</th>
                    <th style="width:100px">Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr wire:key="sale-item-{{ $item['variant_id'] }}">
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['talla'] }}</td>
                        <td>{{ $item['color'] }}</td>
                        <td>
                            <input type="number" min="1" wire:model.live="items.{{ $index }}.cantidad"
                                class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="number" step="0.01"
                                wire:model.live="items.{{ $index }}.precio_venta"
                                class="form-control form-control-sm">
                        </td>
                        <td>
                            S/ {{ number_format($item['precio_venta'] * $item['cantidad'], 2) }}
                        </td>
                        <td>
                            <button wire:click="quitarItem({{ $index }})"
                                class="btn btn-sm btn-outline-danger">×</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Busca un producto arriba para empezar a registrar la venta.</p>
    @endif
    {{-- ITEMS APILADOS --}}

    {{-- METODO DE PAGO --}}
    <div class="border rounded p-2 mb-2">
        <div class="d-flex gap-2 align-items-end mb-2">
            <div>
                <label class="form-label small mb-1">Método</label>
                <select wire:model="nuevoTipoPago" class="form-select form-select-sm">
                    <option value="EFECTIVO">Efectivo</option>
                    <option value="YAPE">Yape</option>
                    <option value="PLIN">Plin</option>
                    <option value="TARJETA">Tarjeta</option>
                </select>
            </div>
            <div>
                <label class="form-label small mb-1">Monto</label>
                <input type="number" step="0.01" wire:model="nuevoMontoPago" class="form-control form-control-sm"
                    style="width:100px">
            </div>
            <button wire:click="agregarPago" class="btn btn-sm btn-outline-primary">+ Agregar pago</button>
        </div>

        @foreach ($pagos as $index => $pago)
            <div class="d-flex justify-content-between small border-top pt-1">
                <span>{{ $pago['tipo_pago'] }}: S/ {{ number_format($pago['monto'], 2) }}</span>
                <button wire:click="quitarPago({{ $index }})"
                    class="btn btn-sm btn-link text-danger p-0">×</button>
            </div>
        @endforeach

        <div class="d-flex justify-content-between mt-2 small">
            <strong>Pagado: S/ {{ number_format($this->totalPagado, 2) }}</strong>
            @if ($this->vuelto > 0)
                <span class="text-muted">Vuelto: S/ {{ number_format($this->vuelto, 2) }}</span>
            @endif
        </div>
    </div>
    {{-- METODO DE PAGO --}}

    {{-- GUARDAR VENTA --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h5 class="mb-0">Total: S/ {{ number_format($this->total, 2) }}</h5>
        <button wire:click="guardarVenta" class="btn btn-primary">Guardar venta</button>
    </div>
    {{-- GUARDAR VENTA --}}

</div>
