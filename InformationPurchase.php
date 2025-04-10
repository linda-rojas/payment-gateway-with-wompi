<?php

namespace InformationCompra;

require_once 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto = isset($_POST['producto']) ? sanitizeInput($_POST['producto']) : '';
    $cantidad = isset($_POST['cantidad']) ? sanitizeInput($_POST['cantidad']) : '';
    $precio = isset($_POST['precio']) ? sanitizeInput($_POST['precio']) : '';
}

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

class InformationCompra
{
    private $producto;
    private $cantidad;
    private $precio;

    public function __construct($producto, $cantidad, $precio)
    {
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }

    public function getTotal()
    {
        return $this->cantidad * $this->precio;
    }
}
