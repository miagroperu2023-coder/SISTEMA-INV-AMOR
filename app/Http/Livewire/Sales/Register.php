<?php

namespace App\Http\Livewire\Sales;

use App\Models\ProductVariant;
use App\Models\Sale;
use Livewire\Component;

class Register extends Component
{

    public $busqueda = '';
    public $resultados = [];

    // items apilados antes de guardar la venta
    public $items = []; // [ ['variant_id', 'nombre', 'talla', 'color', 'precio_compra', 'precio_venta', 'cantidad'] ]

    public function updatedBusqueda()
    {
        if (strlen($this->busqueda) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = ProductVariant::with('product', 'size')
            ->whereHas('product', fn($q) => $q->where('nombre', 'like', '%' . $this->busqueda . '%'))
            ->orWhere('color', 'like', '%' . $this->busqueda . '%')
            ->limit(8)
            ->get();
    }

    public function agregarItem($variantId)
    {
        $variant = ProductVariant::with('product', 'size')->find($variantId);

        if (!$variant) {
            return;
        }

        if ($variant->stock <= 0) {
            session()->flash('error', "No hay stock disponible de {$variant->product->nombre} ({$variant->color}, {$variant->size->valor}).");
            $this->busqueda = '';
            $this->resultados = [];
            return;
        }

        // si ya está agregado, solo suma cantidad si el stock lo permite
        foreach ($this->items as $index => $item) {
            if ($item['variant_id'] == $variantId) {
                if ($item['cantidad'] + 1 > $variant->stock) {
                    session()->flash('error', "Solo hay {$variant->stock} unidad(es) en stock de ese producto.");
                    $this->busqueda = '';
                    $this->resultados = [];
                    return;
                }
                $this->items[$index]['cantidad']++;
                $this->busqueda = '';
                $this->resultados = [];
                return;
            }
        }

        $this->items[] = [
            'variant_id' => $variant->id,
            'nombre' => $variant->product->nombre,
            'talla' => $variant->size->valor,
            'color' => $variant->color,
            'precio_compra' => $variant->precio_compra,
            'precio_venta' => $variant->precio_venta, // se trae por defecto, pero es editable
            'cantidad' => 1,
            'stock_disponible' => $variant->stock,
        ];

        $this->busqueda = '';
        $this->resultados = [];
    }

    public function quitarItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems($value, $key)
    {
        // detecta si lo que cambió fue una cantidad: formato "0.cantidad", "1.cantidad", etc.
        if (str_ends_with($key, '.cantidad')) {
            $index = explode('.', $key)[0];

            if (!isset($this->items[$index])) {
                return;
            }

            $item = $this->items[$index];
            $stockReal = ProductVariant::find($item['variant_id'])->stock;

            if ($item['cantidad'] > $stockReal) {
                $this->items[$index]['cantidad'] = $stockReal;
                session()->flash('error', "Solo hay {$stockReal} unidad(es) en stock, se ajustó la cantidad.");
            }

            if ($item['cantidad'] < 1) {
                $this->items[$index]['cantidad'] = 1;
            }
        }
    }

    public function getTotalProperty()
    {
        return collect($this->items)->sum(fn($item) => $item['precio_venta'] * $item['cantidad']);
    }

    public function guardarVenta()
    {
        if (empty($this->items)) {
            session()->flash('error', 'Agrega al menos un producto antes de guardar.');
            return;
        }

        // revalida stock real antes de confirmar
        foreach ($this->items as $item) {
            $variant = ProductVariant::find($item['variant_id']);

            if (!$variant || $variant->stock < $item['cantidad']) {
                session()->flash('error', "Ya no hay stock suficiente de {$item['nombre']} ({$item['color']}, {$item['talla']}). Revisa el carrito.");
                return;
            }
        }

        $sale = Sale::create([
            'fecha' => now()->toDateString(),
            'total' => $this->total,
            'user_id' => auth()->user()->id
        ]);

        foreach ($this->items as $item) {
            $sale->items()->create([
                'product_variant_id' => $item['variant_id'],
                'cantidad' => $item['cantidad'],
                'precio_venta' => $item['precio_venta'],
                'subtotal' => $item['precio_venta'] * $item['cantidad'],
            ]);

            ProductVariant::where('id', $item['variant_id'])
                ->decrement('stock', $item['cantidad']);
        }

        $this->items = [];
        session()->flash('ok', 'Venta registrada correctamente.');
    }

    public function render()
    {
        return view('livewire.sales.register');
    }
}
