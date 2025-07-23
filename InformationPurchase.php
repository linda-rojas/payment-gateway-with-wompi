<?php

namespace InformationCompra;

class InformationCompra
{
    private $producto;
    private $cantidad;
    private $precio;

    public function __construct()
    {
        $this->producto = isset($_POST['producto']) ? $this->sanitizeInput($_POST['producto']) : '';
        $this->cantidad = isset($_POST['cantidad']) ? (int)$this->sanitizeInput($_POST['cantidad']) : 0;
        $this->precio = isset($_POST['precio']) ? (float)$this->sanitizeInput($_POST['precio']) : 0;
    }

    private function sanitizeInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function getTotal()
    {
        return $this->cantidad * $this->precio;
    }


    public function getProducto()
    {
        return $this->producto;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
}
