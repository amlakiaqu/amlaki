<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RequestModelRequest as StoreRequest;
use App\Http\Requests\RequestModelRequest as UpdateRequest;

class RequestModelCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Request');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/requests');
        $this->crud->setEntityNameStrings('request', 'requests');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD COLUMNS
        $this->crud->removeColumns(['user_id', 'category_id']);

        $idColumnOptions = [
            'label' => 'ID',
            'type' => 'text',
            'name' => 'id'
        ];
        $userColumnOptions = [
            // 1-n relationship
            'label' => "User", // Table column heading
            'type' => "select",
            'name' => 'user_id', // the column that contains the ID of that connected entity;
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => 'App\User' // foreign key model
        ];

        $categoryColumnOptions = [
            // 1-n relationship
            'label' => "Category", // Table column heading
            'type' => "select",
            'name' => 'category_id', // the column that contains the ID of that connected entity;
            'entity' => 'category', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => 'App\Category' // foreign key model
        ];

        $this->crud->addColumn($idColumnOptions);
        $this->crud->addColumn($userColumnOptions);
        $this->crud->addColumn($categoryColumnOptions);

        // ------ CRUD FIELDS
         $userFieldOptions = [  // Select2
             'label' => "User",
             'type' => 'select2_with_options',
             'name' => 'user_id', // the db column for the foreign key
             'entity' => 'user', // the method that defines the relationship in your Model
             'attribute' => 'name', // foreign key attribute that is shown to user
             'model' => 'App\User', // foreign key model
             'element_options' => [],
         ];
         $categoryFieldOptions = [  // Select2
             'label' => "Category",
             'type' => 'select2_with_options',
             'name' => 'category_id', // the db column for the foreign key
             'entity' => 'category', // the method that defines the relationship in your Model
             'attribute' => 'name', // foreign key attribute that is shown to user
             'model' => 'App\Category', // foreign key model
             'element_options' => [],
         ];
         $this->crud->addField($userFieldOptions, 'update/create/both');
         $this->crud->addField($categoryFieldOptions, 'update/create/both');


        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
         $this->crud->enableExportButtons();


         $this->crud->with(["user", "category"]); // eager load relationships

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
