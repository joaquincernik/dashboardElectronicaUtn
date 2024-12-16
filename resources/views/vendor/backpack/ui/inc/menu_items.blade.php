{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Articulos" class="text-primary" icon="la la-question" :link="backpack_url('articulo')" />
<x-backpack::menu-item title="Componentes" class="text-primary" icon="la la-question" :link="backpack_url('componente')" />
<x-backpack::menu-item title="Inventario" class="text-primary" icon="la la-question" :link="backpack_url('inventario')" />
<x-backpack::menu-item title="Usuarios" class="text-warning" icon="la la-question" :link="backpack_url('persona')" />
<x-backpack::menu-item title="Persona externas" class="text-warning" icon="la la-question" :link="backpack_url('persona-externa')" />
<x-backpack::menu-item title="Cursos" class="text-warning" icon="la la-question" :link="backpack_url('curso')" />
<!-- <x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" /> -->
<!-- <x-backpack::menu-item title="Curso pagos" icon="la la-question" :link="backpack_url('curso-pagos')" />
<x-backpack::menu-item title="Persona cuotas" icon="la la-question" :link="backpack_url('persona-cuota')" />
<x-backpack::menu-item title="Articulo pagos" icon="la la-question" :link="backpack_url('articulo-pagos')" />  -->
<x-backpack::menu-item title="Inscripcion a cursos de usuarios" icon="la la-question" :link="backpack_url('curso-persona')" />
<x-backpack::menu-item title="Inscripcion a cursos de personas externas" icon="la la-question" :link="backpack_url('curso-persona-externa')" />