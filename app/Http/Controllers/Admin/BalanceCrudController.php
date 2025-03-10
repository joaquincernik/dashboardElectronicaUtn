<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BalanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BalanceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Balance::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/balance');
        CRUD::setEntityNameStrings('balance', 'balances');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('created_at')->label('Fecha');
        CRUD::column('cuenta');
        CRUD::column('detalle');
        CRUD::column('deber')->label('Debe')->type('number')->prefix(' $');
        CRUD::column('haber')->label('Haber')->type('number')->prefix(' $');
        CRUD::setOperationSetting('lineButtonsAsDropdown', true);
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
        CRUD::field('cuenta')->hint('Ejemplo: Caja');
        CRUD::field('detalle')->hint('Ingreso por venta de diodos');
        CRUD::field('debe')
            ->label('')
            ->type('radio')
            ->options([0 => 'Haber', 1 => 'Debe'])
            ->hint('Elije si esta cuenta impacta en el debe o en el haber');
        CRUD::field('monto')->label('Monto gastado')->prefix('$');

        $rules = [ 'cuenta' => 'required',
        'debe' => 'required',
        'monto' => 'numeric',];
        $messages = [
            'required' => 'Campo requerido',
            'numeric' => 'Ingresa un numero',
        ];
        $this->crud->setValidation($rules, $messages);
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
