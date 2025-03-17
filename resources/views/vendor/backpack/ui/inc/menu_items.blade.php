{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Inventario" icon="la la-book text-danger" :link="backpack_url('inventario')" />
<!-- <x-backpack::menu-item title="Usuarios" icon="la la-user text-primary" :link="backpack_url('persona')" />
<x-backpack::menu-item title="Persona externas"  icon="la la-question text-primary" :link="backpack_url('persona-externa')" />
 --><x-backpack::menu-item title="Cuota de socios" icon="la la-user text-primary" :link="backpack_url('cuota-socio')" />
<x-backpack::menu-item title="Cursos" icon="la la-pen text-warning" :link="backpack_url('curso')" />
<!-- <x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" /> -->
<!-- <x-backpack::menu-item title="Curso pagos" icon="la la-question" :link="backpack_url('curso-pagos')" />
<x-backpack::menu-item title="Persona cuotas" icon="la la-question" :link="backpack_url('persona-cuota')" />
<x-backpack::menu-item title="Articulo pagos" icon="la la-question" :link="backpack_url('articulo-pagos')" />  
<x-backpack::menu-item title="Inscripcion a cursos de usuarios" icon="la la-question" :link="backpack_url('curso-persona')" />
<x-backpack::menu-item title="Inscripcion a cursos de personas externas" icon="la la-question" :link="backpack_url('curso-persona-externa')" />
-->
<x-backpack::menu-item title="Lockers" icon="la la-lock-open text-info" :link="backpack_url('locker')" />
<x-backpack::menu-item title="Ventas" icon="la la-money text-success" :link="backpack_url('venta')" />
<x-backpack::menu-item title="Compras" icon="la la-money text-danger" :link="backpack_url('compra')" /> 
<x-backpack::menu-item title="Ingresos" icon="la la-money text-success" :link="backpack_url('ingreso')" />