<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LockerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LockerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Locker::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/locker');
        CRUD::setEntityNameStrings('locker', 'lockers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('fotoDNI')->label("DNI del responsable")->type('image')->prefix('storage/');
        CRUD::column('persona.nombre_completo')
            ->label('Alumno responsable')
            ->linkTo('persona.show');
        
        CRUD::column('numeroLocker')->label('Numero de locker');
        CRUD::column('estadoDevolucion')
            ->label('Estado de devolucion')
            ->type('radio')
            ->options(
                [
                    3 => 'No devuelto',
                    1 => 'Devuelto',
                    2 => 'Reservado',
                ]
                     )
            ->wrapper([  //estilos
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    if (($entry->estadoDevolucion) == 1) {
                        return 'badge bg-success';
                    }

                    if(($entry->estadoDevolucion) == 2) {
                        return 'badge bg-info';
                    }


                    return 'badge bg-danger';
                },
            ]);
        CRUD::column('created_at')->label('Fecha de registro');
        CRUD::column('nombreAlumnos')->label('Alumnos en el grupo');
        CRUD::column('listaTelefonos')->label('Lista de telefonos');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        
    }




    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::field('fotoDNI')->type('upload')->label('Imagen')->withFiles([
            'disk' => 'public', // the disk where file will be stored
            'path' => 'uploads',
        ]);
        CRUD::field('alumnoResponsable_id')
            ->type('select')
            ->label('Alumno responsable de la llave')
            ->entity('persona')
            ->attribute('nombre_completo_legajo');
        
        CRUD::field('numeroLocker')->type('number')->label('Numero de locker');
        CRUD::field('nombreAlumnos')->type('textarea')->label('Nombre de alumnos del grupo');
        CRUD::field('listaTelefono')->type('number')->label('Lista de telefonos');
        CRUD::field("estadoDevolucion")
            ->label("Estado de devolucion")
            ->type("radio")
            ->options(
                [
                    3  => "No devuelto",
                    1  => "Devuelto",
                    2  => "Reservado"
                ]
                );

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
