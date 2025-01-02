<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\Widget;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::setFromDb(); // set columns from db columns.

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
        CRUD::addFields([
            [
                'name'      => 'componente',
                'label'     => 'Active el boton si es un componente',
                'type'      => 'switch',
                'hint'      =>  'Ejemplo, los componentes tienen un identificador de modelo por ejemplo, como un regulador LM317, y un articulo seria algo comun como un llavero',
                'default' => 1
                
            ],
            [  // Select
                'label' => "Elija el articulo",
                'type' => 'select',
                'name' => 'idarticulo', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'articulos',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\Articulo", // related model
                'attribute' => 'nombre', // foreign key attribute that is shown to user

            ],
          
            [
                'label' => 'cantidad',
                'type' => 'number',
                'name' => 'cantidad'
            ]
      
        ]);

        Widget::add()->type('script')
        ->content('assets/js/admin/forms/inventarioScript.js');

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
