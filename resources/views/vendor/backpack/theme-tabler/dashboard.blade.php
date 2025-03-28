@extends(backpack_view('layouts.' . (backpack_theme_config('layout') ?? 'vertical')))
@php


  $componentes = \App\Models\Inventario::where('componente', 1)->orderBy('cantidad', 'asc')->take(5)->get();
  $articulos = \App\Models\Inventario::where('componente', 0)->orderBy('cantidad', 'asc')->take(5)->get();

  $cantidadPersonas = \App\Models\Persona::count();
  $cantidadPersonasExternas = \App\Models\PersonaExterna::count();
  $cantidadCursos = \App\Models\Curso::count();
  $cantidadInventario = \App\Models\Inventario::count();
  $totalVentas = \App\Models\Venta::sum('precioVenta');
  $totalCompras = \App\Models\Compra::sum('monto');


  $cursoConMasInscriptos = \App\Models\Curso::withCount('personas')
    ->orderBy('personas_count', 'desc')
    ->take(1)
    ->get()
  ;

  $movimientos = DB::table('compras')
    ->select('created_at', DB::raw('monto as debe'), DB::raw('NULL as haber'))
    ->unionAll(
    DB::table('ventas')
      ->select('created_at', DB::raw('NULL as debe'), DB::raw('precioVenta as haber'))
    )
    ->orderBy('created_at', 'asc')
    ->get();

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
      ->ribbon(['top', 'la-calculator'])
      ->value($cantidadCursos)
      ->description('Cursos registrados'),

    Widget::make()
      ->type('progress')
      ->class('card mb-4 py-3')
      ->ribbon(['top', 'la-user'])
      ->value($cantidadPersonas)
      ->description('Usuarios registrados')
      ->accentColor('danger'),


    Widget::make()
      ->type('progress')
      ->class('card mb-4 py-3')
      ->ribbon(['top', 'la-user'])
      ->value($cantidadPersonasExternas)
      ->description('Personas externas registradas')
      ->accentColor('warning'),

    Widget::make()
      ->type('progress')
      ->class('card mb-4 py-3')
      ->ribbon(['top', 'la-book'])
      ->value($cantidadInventario)
      ->description('Tipos de productos registrados')
      ->accentColor('secondary'),

    /*Widget::make()
      ->type('progress')
      ->class('card py-2')
      ->ribbon(['top', 'la-calculator'])
      ->value($cursoConMasInscriptos[0]->titulo . "<br><h1 class='mt-2 badge bg-primary'>" . $cursoConMasInscriptos[0]->personas_count . " inscriptos</h1>")
      ->description('Curso con mas usuarios inscriptos')
      ->accentColor('primary'),
    */
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
<div class="hr-text">Inscripcion de personas al sistema</div>
<div class="container my-5">
    <div class="d-flex row">
    <div class="col-6">
      <a class="card" href="/admin/persona">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

        <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-danger" width="24" height="24" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
        </svg>

        <h1 class="text-center p-3">Ver tabla de usuarios</h1>
      </div>
      </a>
    </div>

    <div class="col-6">
      <a class="card" href="/admin/persona-externa">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

        <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-primary" width="24" height="24" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
        <path d="M12 19l0 .01" />
        </svg>

        <h1 class="text-center p-3">Ver tabla de personas externas</h1>
      </div>
      </a>
    </div>
    </div>
  </div>

  
  <div class="hr-text">Inscripcion a cursos</div>

  <div class="container my-5">
    <div class="d-flex row">
    <div class="col-6">
      <a class="card" href="/admin/curso-persona">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

        <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-danger" width="24" height="24" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
        </svg>

        <h1 class="text-center p-3">Inscribir a un usuario a un curso</h1>
      </div>
      </a>
    </div>

    <div class="col-6">
      <a class="card" href="/admin/curso-persona-externa">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

        <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-primary" width="24" height="24" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
        <path d="M12 19l0 .01" />
        </svg>

        <h1 class="text-center p-3">Inscribir a una persona externa a un curso</h1>
      </div>
      </a>
    </div>
    </div>
  </div>

  


  <div class="hr-text">Inventario</div>

  <h1 class="title text-center p-3">Productos con menor stock disponible</h1>

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
  <div class="d-flex justify-content-center mb-5">
    <a href="/admin/inventario" class="btn btn-danger mx-1">Ver inventario</a>
  </div>
<!-- 
  <div class="d-flex row">
    <div class="col-6">
    <a class="card" href="/admin/compra">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

      <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-danger" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path
        d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" />
      </svg>
      <h1 class="text-center p-3">Registrar compra de productos</h1>
      </div>
    </a>
    </div>

    <div class="col-6">
    <a class="card" href="/admin/venta">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">

      <svg xmlns="http://www.w3.org/2000/svg" class="m-2 text-success" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path
        d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" />
      </svg>

      <h1 class="text-center p-3">Registrar venta de productos</h1>
      </div>
    </a>
    </div>
  </div> -->
  <!-- 
  <div class="hr-text">Tabla del debe/haber</div>

  <div class="card my-3">
    <div class="table-responsive py-4">

    <table class="table table-vcenter card-table table-striped">
      <thead>
      <tr>
        <th>Fecha</th>
        <th>Debe (Compras)</th>
        <th>Haber (Ventas)</th>
      </tr>
      </thead>
      <tbody>
      @foreach($movimientos as $movimiento)
      <tr>
      <td class="bg-dark">{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m/Y H:i')}}</td>
      <td>{{ $movimiento->debe ? '$'.number_format($movimiento->debe, '2') : '' }}</td>
      <td>{{ $movimiento->haber ? '$'.number_format($movimiento->haber, '2') : '' }}</td>
      </tr>
    @endforeach
      <td class="bg-dark"></td>
      <td class="bg-dark"><b>${{number_format($totalCompras,'2')}}</b></td>
      <td class="bg-dark"><b>${{number_format($totalVentas,'2')}}</b></td>

      </tbody>
    </table>
    </div>
  </div>
   -->

@endsection

@section('after_content_widgets')
  @include(backpack_view('inc.widgets'), ['widgets' => app('widgets')->where('section', 'after_content')->toArray()])
@endsection