<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class InventarioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InventarioCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Inventario::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inventario');
        CRUD::setEntityNameStrings('inventario', 'inventarios');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'nombre',
                'type' => 'text',
                'wrapper' => [  //estilos
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if (($entry->componente) == 1) {
                            return 'badge bg-secondary';
                        }
    
                        return 'badge bg-warning';
                    },
                ],
            ],
            [
                'name'        => 'cantidad',
                'type'        => 'number',
            ],
            [
                'name'        => 'dato_adicional',
                'type'        => 'text',
            ],
            [
                'name'        => 'precio',
                'type'        => 'number',
                'label'       => 'Precio para socios',
                'prefix'      => '$'
            ],
            [
                'name'        => 'precionosocios',
                'type'        => 'number',
                'label'       => 'Precio para no socios',
                'prefix'      => '$',
            ],
            [
                'name'        => 'modelo',
                'label'       => 'Modelo comercial',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('modelo', 'like', '%'.$searchTerm.'%');
                }
                ],
      ]);

       
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::field('componente')->type('switch')->hint('Ejemplo, un <i>regulador LM317</i> es un <b>componente</b>, y un libro de fisica es un <b>articulo</b>');
        CRUD::field('nombre')->type('text')->label('Nombre');
        CRUD::field('modelo')->type('text')->label('Modelo comercial')->hint('ejemplo: 4491-LM317-ND ');
        CRUD::field('cantidad')->type('number');
        CRUD::field('dato_adicional')->type('text');
        CRUD::field('precio')->label('Precio para socios')->type('number')->prefix('$');
        CRUD::field('precionosocios')->label('Precio para no socios')->type('number')->prefix('$');



        Widget::add()->type('script')
        ->content(('assets/js/admin/forms/inventarioScript.js'));
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
