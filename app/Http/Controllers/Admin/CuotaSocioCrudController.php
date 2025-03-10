<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class CuotaSocioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CuotaSocioCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CuotaSocio::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cuota-socio');
        CRUD::setEntityNameStrings('cuota socio', 'cuotas socios');
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
                'name' => 'persona.nombre_completo',
                'label' => 'Persona',
                'type' => 'text',
                'linkTo' => 'persona.show',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('persona', function ($q) use ($column, $searchTerm) {
                        $q->where('nombre', 'like', '%' . $searchTerm . '%');
                    });
                }
            ]
        );

        $this->crud->addColumn(
            [
                'name' => 'persona_externa.nombre',
                'label' => 'Persona externa',
                'type' => 'text',
                'linkTo' => 'persona-externa.show',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('persona_externa', function ($q) use ($column, $searchTerm) {
                        $q->where('nombre', 'like', '%' . $searchTerm . '%');
                    });
                }
            ]
        );

        CRUD::column('monto')
            ->prefix('$');
        CRUD::column('meses')
            ->type('select_from_array');


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
        CRUD::addFields([
            [
                'label' => "Socio externo",
                'type' => 'checkbox',
                'name' => 'persona_ext'
            ],
            [  // Select
                'label' => "Nombre completo de la persona",
                'type' => 'select',
                'name' => 'persona_socio_id', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'persona',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\Persona", // related model
                'attribute' => 'nombre_completo_legajo', // foreign key attribute that is shown to user

                'events' => [
                    'saved' => function ($entry) {
                        $persona = \App\Models\Persona::find($entry->persona_socio_id);
                        if ($persona) {
                            $persona->socio = 1; // Cambia el estado
                            $persona->mesesAbonados = $entry->meses;
                            $persona->save(); // Guarda los cambios en la BD
                        }


                    }
                ],
                'options' => (function ($query) {
                    return $query->orderBy('apellido', 'ASC')->get();
                }), //  you can use this to filter the results show in the select
            ],
            [  // Select
                'label' => "Nombre completo de la persona externa",
                'type' => 'select',
                'name' => 'persona_ext_socio_id', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'persona_externa',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\PersonaExterna", // related model
                'attribute' => 'nombre', // foreign key attribute that is shown to user
                'events' => [
                    'saved' => function ($entry) {
                        $personaExt = \App\Models\PersonaExterna::find($entry->persona_ext_socio_id);
                        if ($personaExt) {
                            $personaExt->socio = 1; // Cambia el estado
                            $personaExt->mesesAbonados = $entry->meses;
                            $personaExt->save(); // Guarda los cambios en la BD
                        }


                    }
                ],
            ],
            [
                'name' => 'monto',
                'label' => 'Monto abonado',
                'type' => 'number',
                'prefix' => '$',
            ],
            [   // select_from_array
                'name' => 'meses',
                'label' => "Meses abonados",
                'hint' => "Para seleccionar mas de un mes, oprima CTRL y selecciona los demas meses",
                'type' => 'select_from_array',
                'options' =>
                    [
                        'enero' => 'Enero',
                        'febrero' => 'Febrero',
                        'marzo' => 'Marzo',
                        'abril' => 'Abril',
                        'mayo' => 'Mayo',
                        'junio' => 'Junio',
                        'julio' => 'Julio',
                        'agosto' => 'Agosto',
                        'septiembre' => 'Septiembre',
                        'octubre' => 'Octubre',
                        'noviembre' => 'Noviembre',
                        'diciembre' => 'Diciembre'


                    ],
                'allows_null' => false,
                'default' => 'enero',
                'allows_multiple' => true,
                /*'events' =>[
                'saved' => function($entry){
                    $persona = \App\Models\Persona::find($entry->persona_socio_id);
                    if ($persona) { 
                        $persona->mesesAbonados = $entry->meses; // Cambia el estado
                        $persona->save(); // Guarda los cambios en la BD
                    }
                }
            ],*/
            ]
        ]);
        CRUD::setFromDb(); // set fields from db columns.
        Widget::add()->type('script')
            ->content(('assets/js/admin/forms/cuotaScript.js'));


        $rules = ['monto' => 'required|numeric'];
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
