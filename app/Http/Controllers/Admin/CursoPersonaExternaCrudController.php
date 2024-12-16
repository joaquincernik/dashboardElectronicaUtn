<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CursoPersonaExternaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CursoPersonaExternaCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CursoPersonaExterna::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/curso-persona-externa');
        CRUD::setEntityNameStrings('inscripcion de personas externas', 'inscripcion de personas externas');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setTitle('Ficha de inscripcion de personas externas a cursos', 'list'); // set the Title for the create action
        $this->crud->setHeading('Ficha de inscripcion de personas externas', 'list'); // set the Heading for the create action

        $this->crud->addColumns([
      
            [
                'name' => 'persona_externa.nombre',
                'label' => 'Nombre y apellido',
                'type' => 'text',
                'linkTo' => 'persona-externa.show',
                'wrapper' => [
                    'class' => 'text-decoration-none',
                ],
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
                        if ( ($entry->montoAbonado) == ($entry->curso->precionosocio)) {
                            return 'text-dark badge bg-success';
                        } else {

                            return 'text-dark badge bg-danger';
                        }
                    },
                ]
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
            [
                'name' => 'comentarios',
                'label' => 'Comentarios de la persona',
                'type' => 'text'
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
                'name' => 'persona_externa_id', // the db column for the foreign key

                // optional
                // 'entity' should point to the method that defines the relationship in your Model
                // defining entity will make Backpack guess 'model' and 'attribute'
                'entity' => 'persona_externa',

                // optional - manually specify the related model and attribute
                'model' => "App\Models\PersonaExterna", // related model
                'attribute' => 'nombre', // foreign key attribute that is shown to user
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
            [
                'name' => 'comentarios',
                'label' => 'Comentarios acerca de donde se entero sobre el curso esta persona',
                'type' => 'text',
                
            ],

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
