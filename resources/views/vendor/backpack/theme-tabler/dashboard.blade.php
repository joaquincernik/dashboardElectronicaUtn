@extends(backpack_view('layouts.' . (backpack_theme_config('layout') ?? 'vertical')))
@php


  $componentes = \App\Models\Inventario::where('componente',1)->get();
  $articulos = \App\Models\Inventario::where('componente',0)->get();

  $cantidadPersonas = \App\Models\Persona::count();
  $cantidadCursos = \App\Models\Curso::count();

  $cursoConMasInscriptos = \App\Models\Curso::withCount('personas')
    ->orderBy('personas_count', 'desc')
    ->take(1)
    ->get()
  ;

  // Merge widgets that were fluently declared with widgets declared without the fluent syntax:
  // - $data['widgets']['before_content']
  // - $data['widgets']['after_content']
  if (isset($widgets)) {
    foreach ($widgets as $section => $widgetSection) {
    foreach ($widgetSection as $key => $widget) {
      \Backpack\CRUD\app\Library\Widget::add($widget)->section($section);
    }
    }
  }
@endphp

@php
use Backpack\CRUD\app\Library\Widget;
    // Agregar un widget de tipo 'box' en la sección 'before_content'
    Widget::add()
      ->section('before_content')
      ->type('div')
      ->class('row')
      ->content([
      Widget::make()
      ->type('progress')
      ->class('card mb-4 py-3')
      ->ribbon(['top','la-calculator'])
      ->value($cantidadCursos)
      ->description('Cursos registrados'),

      Widget::make()
      ->type('progress')
      ->class('card mb-4 py-3')
      ->ribbon(['top','la-user'])
      ->value($cantidadPersonas)
      ->description('Usuarios registrados')
      ->accentColor('danger'),

      Widget::make()
      ->type('progress')
      ->class('card py-2')
      ->ribbon(['top','la-calculator'])
      ->value($cursoConMasInscriptos[0]->titulo."<br><h1 class='mt-2 badge bg-primary'>".$cursoConMasInscriptos[0]->personas_count." inscriptos</h1>")
      ->description('Curso con mas usuarios inscriptos')
      ->accentColor('primary'),
      
      ]);

@endphp

@section('before_breadcrumbs_widgets')
@include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'before_breadcrumbs')->toArray()])
@endsection

@section('after_breadcrumbs_widgets')
@include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'after_breadcrumbs')->toArray()])
@endsection

@section('before_content_widgets')
@include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'before_content')->toArray()])
@endsection

@section('content')

<h1 class="title text-center p-3">Inventario de productos</h1>

<div class="card my-3">
  <div class="table-responsive py-4">
    <table class="table table-vcenter card-table table-striped">
      <thead>
        <tr>
          <th>Componente</th>
          <th>Modelo</th>
          <th>Cantidad</th>

          <th class="w-1"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          @foreach ($componentes as $item)
        <td>{{$item->nombre}}</td>

        <td>
        {{$item->modelo}}
        </td>
       
        <td>
        {{$item->cantidad}}
        </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="card my-3">
  <div class="table-responsive py-4">
    <table class="table table-vcenter card-table table-striped">
      <thead>
        <tr>
          <th>Articulo</th>
          <th>Cantidad</th>

          <th class="w-1"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          @foreach ($articulos as $item)
        <td>{{$item->nombre}}</td>

        <td>
        {{$item->cantidad}}
        </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>


@endsection

@section('after_content_widgets')
@include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'after_content')->toArray()])
@endsection