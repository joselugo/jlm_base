<?php

namespace App\Libraries;

#[\AllowDynamicProperties]
class Calcular
{
  function calculadora(String $impuesto, array $productos, array $otros_impuestos_array, float $descuento)
  {
    $total_items = 0;
    $total = 0;
    $subtotal = 0;
    $iva = 0;
    $total_otros_impuestos = 0;
    if ($impuesto == "NADA") {
      foreach ($productos as $producto) {
        $total_items += $producto->cantidad;
        $subtotal += $producto->cantidad;
      }
      foreach ($otros_impuestos_array as $val_impuesto) {
        $total_otros_impuestos += $val_impuesto;
      }
      $subtotal = $total_items;
    } else if ($impuesto == "SI") {
      foreach ($productos as $producto) {
        $total_items += $producto->cantidad;
        $subtotal += $producto->cantidad;
        $iva += ($producto->cantidad * $producto->iimpuesto) / 100;
      }
      foreach ($otros_impuestos_array as $val_impuesto) {
        $total_otros_impuestos += $val_impuesto;
      }
    } else if ($impuesto == "NO") {
      foreach ($productos as $producto) {
        $total_items += $producto->cantidad;
        $iva_producto = ($producto->cantidad / (1 + ($producto->iimpuesto / 100)));
        if ($producto->cantidad > 0) {
          $subtotal += $iva_producto;
        } else {
          $subtotal += $producto->cantidad;
        }
        if ($producto->cantidad > 0)  $iva += abs($iva_producto - $producto->cantidad);
      }
      foreach ($otros_impuestos_array as $val_impuesto) {
        $total_otros_impuestos += $val_impuesto;
      }
    }
    $total = $subtotal + $iva + $total_otros_impuestos;
    if ($total < $descuento) {
      $descuento = $descuento - $total;
      $nuevo_descuento = $total;
      $total = 0;
    } else {
      $total -= $descuento;
      $nuevo_descuento = $descuento;
      $descuento = 0;
    }
    $data = [
      "total" => $total,
      'subtotal' => $subtotal,
      'iva' => $iva,
      'otros_impuestos' => $total_otros_impuestos,
      'estado' => $total <= 0 ? 'pagado' : 'No pagado',
      'nuevo_saldo' => $descuento,
      'nuevo_descuento' => $nuevo_descuento
    ];
    return $data;
  }
}
