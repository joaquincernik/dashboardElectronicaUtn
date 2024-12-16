@extends(backpack_view('layouts.' . (backpack_theme_config('layout') ?? 'vertical')))
@php


  $inventario = \App\Models\Inventario::all();
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

<h1 class="title text-center p-3">Inventario de articulos</h1>

<div class="card">

  <div class="table-responsive">
    <table class="table table-vcenter card-table table-striped">
      <thead>
        <tr>
          <th>Articulo</th>
          <th>Precio para socios</th>
          <th>Precio para no socios</th>
          <th>Cantidad</th>

          <th class="w-1"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          @foreach ($inventario as $item)
        @foreach ($item->articulos as $articulo)
        <td>{{$articulo->descripcion}}</td>

        <td class="text-secondary">
        ${{$articulo->preciosocio}}
        </td>
        <td class="text-secondary">
        ${{$articulo->precionosocio}}
        </td>
        <td>
        {{$item->cantidad}}
        </td>
        </tr>
      @endforeach
    @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('after_content_widgets')
@include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'after_content')->toArray()])
@endsection