<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Livewire\Component;

class Report extends Component
{
    public $fechaInicio;
    public $fechaFin;

    public function mount()
    {
        // por defecto, el día de hoy
        $this->fechaInicio = now()->toDateString();
        $this->fechaFin = now()->toDateString();
    }

    public function getProductosVendidosProperty()
    {
        return SaleItem::query()
            ->whereHas('sale', function ($q) {
                $q->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
            })
            ->with('variant.product', 'variant.size')
            ->get()
            ->groupBy('product_variant_id')
            ->map(function ($items) {
                $primero = $items->first();
                return [
                    'nombre' => $primero->variant->product->nombre,
                    'talla' => $primero->variant->size->valor,
                    'color' => $primero->variant->color,
                    'cantidad' => $items->sum('cantidad'),
                    'subtotal' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('cantidad')
            ->values();
    }

    public function getTotalesPorMetodoProperty()
    {
        $metodos = ['YAPE' => 0, 'PLIN' => 0, 'EFECTIVO' => 0, 'TARJETA' => 0];

        $sumas = SalePayment::query()
            ->whereHas('sale', function ($q) {
                $q->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
            })
            ->selectRaw('tipo_pago, SUM(monto) as total')
            ->groupBy('tipo_pago')
            ->pluck('total', 'tipo_pago');

        foreach ($sumas as $tipo => $monto) {
            $metodos[$tipo] = $monto;
        }

        return $metodos;
    }

    public function getTotalGeneralProperty()
    {
        return Sale::whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])->sum('total');
    }

    public function getCantidadVentasProperty()
    {
        return Sale::whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])->count();
    }

    public function render()
    {
        return view('livewire.sales.report');
    }
}
