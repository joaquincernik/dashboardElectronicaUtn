<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CompraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompraCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Compra::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/compra');
        CRUD::setEntityNameStrings('compra', 'compras');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(
            [
                'name' => 'inventario.nombre',
                'label' => 'Producto',
                'type' => 'text',
                'linkTo' => 'inventario.show',
            ]);

        CRUD::column('monto')->label('Monto gastado')->prefix('$');
        CRUD::column('cantidad')->label('Cantidad comprada');
        CRUD::column('created_at')->label('Fecha de venta');


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
        CRUD::field('inventarioCompra_id')
            ->type('select')
            ->entity('inventario')
            ->label('Producto')
            ->attribute('nombre_cantidad')
            ->events([
                'saved' => function($entry){
                    \App\Models\Inventario::find($entry->inventarioCompra_id)->increment('cantidad',$entry->cantidad);
                }
                ]);

        CRUD::addFields([
            [
                       'label' => 'Cantidad vendida',
                        'type' => 'number',
                        'name' =>  'cantidad',
            ],
            [
                        'label' => 'Monto gastado',
                         'type' => 'number',
                         'name' =>'monto',
                         'prefix' => '$'
            ]
                    
                ]);
        

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
