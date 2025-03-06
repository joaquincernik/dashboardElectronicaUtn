<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class PersonaExternaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonaExternaCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PersonaExterna::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/persona-externa');
        CRUD::setEntityNameStrings('persona externa', 'persona externas');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('nombre')
            ->type('text')
            ->label("Nombre y Apellido");
        CRUD::setFromDb();
        CRUD::column('mesesAbonados')->type('select_from_array');
       
        /**
         * Columns can be defined using the fluent syntax:
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
       
        CRUD::addField([
            'name'      => 'nombre',
            'label'     => 'Nombre y Apellido',
            'type'      => 'text',
           
        ]);

    
        CRUD::addField([
            'name'      => 'estudiante',
            'label'     => 'Estudia en alguna facultad?',
            'type'      => 'switch',
            'hint'      =>  'Si es estudiante se desplegara el campo facultad',
            'default' => 1
            
        ]);
        CRUD::addField([
            'name'  => 'facultad',
            'type'  => 'text',
            'label' => 'Facultad',
        ]);

        Widget::add()->type('script')
        ->content(('assets/js/admin/forms/personaExternaScript.js'));
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
