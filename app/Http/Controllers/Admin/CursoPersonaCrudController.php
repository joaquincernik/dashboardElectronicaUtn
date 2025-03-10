<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ArticuloCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CursoPersonaCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CursoPersona::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/curso-persona');
        CRUD::setEntityNameStrings('inscripcion de usuario', 'inscripcion de usuarios a cursos');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setTitle('Ficha de inscripcion de usuarios a cursos', 'list'); // set the Title for the create action
        $this->crud->setHeading('Ficha de inscripcion', 'list'); // set the Heading for the create action

        // CRUD::setFromDb(); // set fields from db columns.
        $this->crud->addColumns([
            [
                'name' => 'persona.nombre_completo',
                'label' => 'Usuario',
                'type' => 'text',
                'linkTo' => 'persona.show',
                'wrapper' => [
                    'element' => 'a',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if (($entry->persona->socio) == 1) {
                            return 'text-decoration-none text-success';
                        } else {

                            return 'text-decoration-none text-warning';
                        }
                    },
                ],
                'searchLogic' => function ($query, $column, $searchTerm) {
                    // Hacemos join con la tabla personas para buscar por apellido
                    $query->orWhereHas('persona', function ($q) use ($searchTerm) {
                        $q->where('nombre', 'like', '%' . $searchTerm . '%')
                           ->orWhere('apellido', 'like', '%' . $searchTerm . '%');
                    });
                }

            ],
            [
                'name' => 'persona.legajo',
                'label' => 'Legajo',
                'type' => 'text'
            ],
            [
                'name' => 'curso.titulo',
                'label' => 'Curso',
                'type' => 'text',
                'linkTo' => 'curso.show',
                'wrapper' => [
                    'class' => 'text-decoration-none',
                ],
            ],
            [
                'name' => 'montoAbonado',
                'label' => 'Monto Abonado',
                'type' => 'number',
                'prefix' => '$',
                'wrapper' => [
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if (($entry->persona->socio && (($entry->montoAbonado) == ($entry->curso->precio))) || (!($entry->persona->socio) && (($entry->montoAbonado) == ($entry->curso->precionosocio)))) {
                            return 'text-dark badge bg-success';
                        } else {

                            return 'text-dark badge bg-danger';
                        }
                    },
                ]
            ],
            [
                'name' => 'curso.precio',
                'label' => 'Precio socios',
                'type' => 'text',
                'wrapper' => [
                    'element' => 'span',
                    'class' => 'text-success'
                ],
                'prefix' => '$'


            ],

            [
                'name' => 'curso.precionosocio',
                'label' => 'Precio no socios',
                'type' => 'text',
                'wrapper' => [
                    'element' => 'span',
                    'class' => 'text-warning'
                ],
                'prefix' => '$'


            ],


            [
                'name' => 'created_at',
                'label' => 'Fecha de pago',
                'type' => 'datetime'
            ],
            [
                'name' => 'updated_at',
                'label' => 'Fecha de modificacion de pago',
                'type' => 'datetime'
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
        CRUD::addFields([
            [  // Select
                'label' => "Nombre completo de la persona",
                'type' => 'select',
                'name' => 'persona_id', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'persona',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\Persona", // related model
                'attribute' => 'nombre_completo_legajo', // foreign key attribute that is shown to user

                'options' => (function ($query) {
                    return $query->orderBy('apellido', 'ASC')->get();
                }), //  you can use this to filter the results show in the select
            ],
            [
                'label' => "Nombre del curso",
                'type' => 'select',
                'name' => 'curso_id', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'curso',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\Curso", // related model
                'attribute' => 'curso_completo', // foreign key attribute that is shown to user

                'options' => (function ($query) {
                    return $query->orderBy('titulo', 'ASC')->get();
                }), //  you can use this to filter the results show in the select
            ],
            [
                'name' => 'montoAbonado',
                'label' => 'Monto abonado',
                'type' => 'number',
                'prefix' => '$',
            ],

        ]);

        // Setters
        $this->crud->setTitle('Ficha de inscripcion de usuarios a cursos', 'create'); // set the Title for the create action
        $this->crud->setHeading('Ficha de inscripcion', 'create'); // set the Heading for the create action
        $this->crud->setSubheading('de usuarios a cursos', 'create'); // set the Subheading for the create action
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
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
