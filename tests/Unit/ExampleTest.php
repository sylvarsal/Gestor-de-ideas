<?php

test('comprobar una suma', function () { //definir nombre del test
   $result = 1 + 2; //ejecutar (lo que quieres probar)
 
   expect($result)->toBe(3); //todo lo que va después de expect: comprobación (lo que esperas que pase)
});


//toBe() -> mismo tipo y valor
//toEqual() -> mismo valor, pero no mismo tipo (MISMA CANTIDAD)
//toBeTrue() -> valor booleano true
//toBeFalse() -> valor booleano false
//toHaveCount() -> cantidad de elementos en un array
//with() 

test('comprobar si una persona es mayor de edad', function () {
    $edad = 20;
 
    $esMayor = $edad >= 18;
    expect($esMayor)->toBeTrue();
});

test('comprobar si una persona es menor de edad', function () {
    $edad = 16;
 
    $esMayor = $edad >= 18;
    expect($esMayor)->toBeFalse();
});

test('comprobar la cantidad de productos', function () {
    $productos = ['pan', 'leche', 'huevos'];
 
    expect($productos)->toHaveCount(3);
});

function calcularDescuento($precio, $descuento) {
    return $precio - ($precio * ($descuento / 100));
}

test('calcular correctamente un descuento', function () {
    $resultado = calcularDescuento(200, 10);
 
    expect($resultado)->toEqual(180);
});

test('calcular descuento de estudiante', function () {
    $resultado = calcularDescuento(200, 50);
 
    expect($resultado)->toEqual(100);
});

test('calcular descuento de profesor', function () {
    $resultado = calcularDescuento(200, 0);
 
    expect($resultado)->toEqual(200);
});


test('calcular diferentes descuentos', function ($precio, $descuento, $resultadoEsperado) {
    $resultado = calcularDescuento($precio, $descuento);
 
    expect($resultado)->toEqual($resultadoEsperado);
}) ->with(['descuento de 10%' => [200, 10, 180],
          'descuento de 50%' => [200, 50, 100],
          'descuento de 0%' => [200, 0, 200]]);