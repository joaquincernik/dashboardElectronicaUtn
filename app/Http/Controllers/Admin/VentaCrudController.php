<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Venta as Venta;
use Illuminate\Database\Eloquent\Model;
/**
 * Class VentaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VentaCrudController extends CrudController
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
        CRUD::setModel(Venta::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/venta');
        CRUD::setEntityNameStrings('venta', 'ventas');
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

        CRUD::column('precioVenta')->label('Precio total de venta')->prefix('$');
        CRUD::column('cantidad')->label('Cantidad vendida');
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
        CRUD::field('inventario_id')
            ->type('select')
            ->attribute('nombre_cantidad')
            ->events([
            'saving' => function ($entry) {
                $inventario = \App\Models\Inventario::find($entry->inventario_id);
                if($entry->socio == 1){
                    $entry->precioVenta = $inventario->precio * $entry->cantidad;
                }
                else{
                    $entry->precioVenta = $inventario->precionosocios * $entry->cantidad;
                }
            },
            'saved' => function($entry){
                \App\Models\Inventario::find($entry->inventario_id)->decrement('cantidad',$entry->cantidad);
            }
            ]);
        CRUD::addFields([
            [
               'label' => 'Cantidad vendida',
                'type' => 'number',
                'name' =>  'cantidad',
            ],
            [
                'label' => 'Producto vendido a un socio',
                 'type' => 'switch',
                 'name' =>'socio'
             ]
            
        ]);

        $this->crud->setValidation([
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $inventario = \App\Models\Inventario::find(request()->inventario_id); // Obtiene el inventario
                    if ($inventario && $value > $inventario->cantidad) {
                        $fail("La cantidad ingresada ($value) supera el stock disponible ({$inventario->cantidad}).");
                    }
                }
            ],
            'inventario_id' => 'required|exists:inventarios,id'
        ]);

        /*CRUD::field('precioVenta')->on('saving', function ($entry) {
            if($entry->socio){
                $entry->precio = \App\Models\Inventario::findOrFail($entry->inventario_id)->precio;
            }
            else{
                $entry->precio = \App\Models\Inventario::findOrFail($entry->inventario_id)->precionosocios;
            }
            
        });*/
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
