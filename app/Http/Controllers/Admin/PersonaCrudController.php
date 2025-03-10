<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonaCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Persona::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/persona');
        CRUD::setEntityNameStrings('persona', 'personas');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // set columns from db columns.
        $this->crud->addColumns([

            [
                'name' => 'nombre', //  db column name (attribute name)
                'label' => "Nombre", // the human-readable label for it
                'type' => 'text', // the kind of column to show    
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('nombre', 'like', '%' . $searchTerm . '%');
                }
            ],
            [
                'name' => 'apellido',
                'label' => "Apellido",
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('apellido', 'like', '%' . $searchTerm . '%');
                }
            ],
            [
                'name' => 'socio',
                'label' => 'Estado de socio',
                'type' => 'boolean',
                'options' => [0 => 'No socio', 1 => 'Socio'],
                'wrapper' => [  //estilos
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if (($entry->socio) == 1) {
                            return 'badge bg-success';
                        }

                        return 'badge bg-warning';
                    },
                ],
            ],
            [
                'name' => 'mail',
                'label' => "Mail",
                'type' => 'text',
            ],
            [
                'name' => 'telefono',
                'label' => "Telefono",
                'type' => 'text',
            ],
            [
                'name' => 'legajo',
                'label' => "Legajo",
                'type' => 'number',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('legajo', 'like', '%' . $searchTerm . '%');
                }
            ],
            [
                'name' => 'dni',
                'label' => "DNI",
                'type' => 'text',
            ],
            [
                'name' => 'direccion',
                'label' => "Direccion",
                'type' => 'text',
            ],

            [
                'name' => 'mesesAbonados',
                'label' => "Meses abonados",
                'type' => 'select_from_array',
            ],
            [
                'name' => 'anio',
                'label' => "Año que cursa",
                'type' => 'text',
            ],
        ]);


        CRUD::setOperationSetting('lineButtonsAsDropdown', true);


    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        $this->crud->column([
            // Select

            'name' => 'foto',
            'label' => "Foto",
            'type' => 'image',
            'prefix' => 'storage/',
            'height' => '50px',
            'width' => '50px',

        ])->makeFirst();
        $this->crud->column([
            // Select
            'label' => 'Cursos a los que esta isncripto',
            'type' => 'select',
            'name' => 'curso_id', // the db column for the foreign key
            'entity' => 'cursos', // the method that defines the relationship in your Model
            'attribute' => 'titulo', // foreign key attribute that is shown to user
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('curso/' . $related_key . '/show');
                },
                'class' => 'badge text-decoration-none my-2',
            ],
        ]);
    }



    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PersonaRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('anio')
            ->label("Año en el que cursa");
        CRUD::field('foto')->remove();
        CRUD::field('mesesAbonados')->remove();
        CRUD::addField([   // Upload
            'name' => 'foto',
            'label' => 'Foto',
            'type' => 'upload',
            'withFiles' => true
        ]);



        $rules = [
            'nombre' => 'required',
            'apellido' => 'required',
            'legajo' => 'required',
        ];
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
