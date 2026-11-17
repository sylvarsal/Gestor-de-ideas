<?php

use App\Models\Categoria;
use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
uses(RefreshDatabase::class);

test('Utilizar base de datos de prueba', function () {
    expect(config('database.default'))->toBe('sqlite');
    expect(config('database.connections.sqlite.database'))->toBe(':memory:');

});

test('registrar una categoría', function () {
    Categoria::create([
        'nombre' => 'Ciencia',
        'descripcion' => 'Ideas de ciencias'
    ]);

    $this->assertDatabaseHas('categorias', [
        'nombre' => 'Ciencia',
        'descripcion' => 'Ideas de ciencias'
    ]);
});

test('consultar una categoría', function () {
    $categoria = Categoria::create([
        'nombre' => 'Ciencia',
        'descripcion' => 'Ideas de ciencias'
    ]);

    $encontrada = Categoria::find($categoria->id);

    expect($encontrada->nombre)->toBe('Ciencia');
});

test('modificar una categoría', function () {
    $categoria = Categoria::create([
        'nombre' => 'Ciencia',
        'descripcion' => 'Ideas de ciencias'
    ]);

    $categoria->update([
        'nombre' => 'Innovación',
    ]);

    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'nombre' => 'Innovación',
    ]);

    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'nombre' => 'Innovación',
    ]);
});

test('eliminar una categoría', function () {
    $categoria = Categoria::create([
        'nombre' => 'Ciencia',
        'descripcion' => 'Ideas de ciencias'
    ]);

    $categoria->delete();

    $this->assertDatabaseMissing('categorias', [
        'id' => $categoria->id,
    ]);
});

test('una categoría puede tener varias ideas', function () {
  $categoria = Categoria::create([
    'nombre' => 'Tecnología',
    'descripcion' => 'Ideas tecnológicas'
  ]);

  Idea::create([
    'titulo' => 'Aplicación de transporte',
    'descripcion' => 'Aplicación para compartir viajes',
    'autor' => 'Ana',
    'estado' => 'registrada',
    'categoria_id' => $categoria->id
  ]);

  Idea::create([
    'titulo' => 'Sistema de inventario',
    'descripcion' => 'Control de productos',
    'autor' => 'Luis',
    'estado' => 'registrada',
    'categoria_id' => $categoria->id
  ]);

 	expect($categoria->ideas)->toHaveCount(2);
});

test('no se puede borrar una categoria con ideas asociadas', function () {
  $categoria = Categoria::create([
    'nombre' => 'Tecnología',
    'descripcion' => 'Ideas tecnológicas'
  ]);

  Idea::create([
    'titulo' => 'Aplicación de transporte',
    'descripcion' => 'Aplicación para compartir viajes',
    'autor' => 'Ana',
    'estado' => 'registrada',
    'categoria_id' => $categoria->id
  ]);

  Idea::create([
    'titulo' => 'Sistema de inventario',
    'descripcion' => 'Control de productos',
    'autor' => 'Luis',
    'estado' => 'registrada',
    'categoria_id' => $categoria->id
  ]);
    
  $seRechazo = false;
    try{
        $categoria->delete();
    }catch(QueryException $e){
        $seRechazo = true; // la base de datos impidio la eliminacion
    }

    expect($seRechazo)->toBeTrue();
    
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
        ]);
});