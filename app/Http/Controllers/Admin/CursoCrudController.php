<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CursoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class CursoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CursoCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Curso::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/curso');
        CRUD::setEntityNameStrings('curso', 'cursos');
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

        CRUD::button('email')->stack('line')->view('crud::buttons.quick')->meta([
            'access' => true,
            'label' => 'Inscribir usuarios',
            'icon' => 'la la-external-link-alt',
            'wrapper' => [
                'element' => 'a',
                'href' => url('/admin/curso-persona/create'),
                'target' => '_blank',
                'title' => 'Inscribe un usuario a este curso',
            ]
        ]);
        $this->crud->addColumns([
            [
                'name' => 'abierto',
                'label' => 'Abierto',
                'type' => 'boolean',
                'options' => [0 => 'No', 1 => 'Si'],
                'wrapper' => [  //estilos
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if (($entry->abierto) == 1) {
                            return 'badge bg-success';
                        }

                        return 'badge bg-warning';
                    },
                ],
            ],

            [
                'name' => 'titulo',
                'label' => "Titulo",
                'type' => 'text',

            ],
            [
                'name' => 'duracion', //  db column name (attribute name)
                'label' => "Duracion", // the human-readable label for it
                'type' => 'number', // the kind of column to show   
                'suffix' => ' hs',

            ],
            [
                'name' => 'capacidad',
                'label' => "Capacidad",
                'type' => 'text',
                'suffix' => ' personas',

            ],
            [
                'name' => 'disertante',
                'label' => "Disertante",
                'type' => 'text',

            ],

            [
                'name' => 'precio',
                'label' => "Precio",
                'type' => 'text',
                'wrapper' => [  //estilos
                    'element' => 'span',
                    'class' => 'badge bg-success'
                ],
                'prefix' => '$',
            ],
            [
                'name' => 'precionosocio',
                'label' => "Precio no socios",
                'type' => 'text',
                'wrapper' => [  //estilos
                    'element' => 'span',
                    'class' => 'badge bg-warning'
                ],
                'prefix' => '$',
            ],
            [
                'name' => 'fechaalta',
                'label' => "Fecha de alta",
                'type' => 'date',
            ],
            
            [
                'name' => 'personas', // name of relationship method in the model
                'entity' => 'personas',
                'type' => 'relationship_count',
                'label' => 'Cantidad de usuarios de la facultad inscriptos',
                'suffix' => ' usuarios inscriptos',
            ],

            [
                'name' => 'personas_externas', // name of relationship method in the model
                'entity' => 'personas_externas',
                'type' => 'relationship_count',
                'label' => 'Cantidad de personas externas a la facultad inscriptos',
                'suffix' => ' personas inscriptas',
            ],
         
            [
                'name' => 'docente',
                'label' => "Docente",
                'type' => 'boolean',
                'options' => [0 => 'Alumno', 1 => 'Docente'],

            ],
        ]);

     
        CRUD::setOperationSetting('lineButtonsAsDropdown', true);

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */


    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->crud->column([
            // Select
            'label'     => 'Usuarios inscriptos',
            'type'      => 'select',
            'name'      => 'persona_id', // the db column for the foreign key
            'entity'    => 'personas', // the method that defines the relationship in your Model
            'attribute' => 'nombre_completo', // foreign key attribute that is shown to user
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('persona/'.$related_key.'/show');
                },
                 'class' => 'badge text-decoration-none my-2',
            ],
          ]);
          $this->crud->column([
            // Select
            'label'     => 'Personas externas inscriptas',
            'type'      => 'select',
            'name'      => 'personas_ext_id', // the db column for the foreign key
            'entity'    => 'personas_externas', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('persona-externa/'.$related_key.'/show');
                },
                 'class' => 'badge text-decoration-none my-2',
            ],
          ]);


    }
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('capacidad')->suffix('personas');
        CRUD::field('duracion')->suffix('horas');
        CRUD::field('precio')->prefix('$');
        CRUD::field('precionosocio')->prefix('$')->label('Precio para no socios');
        CRUD::field('docente')
            ->after('disertante')
            ->hint('Inserte si el disertante es un alumno o docente de la UTN')
            ->type('switch');

            $rules = [
                'titulo' => 'required',
               
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
