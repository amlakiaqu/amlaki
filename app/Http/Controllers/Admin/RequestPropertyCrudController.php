<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RequestPropertyRequest as StoreRequest;
use App\Http\Requests\RequestPropertyRequest as UpdateRequest;

class RequestPropertyCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\RequestProperty');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/request_property');
        $this->crud->setEntityNameStrings('Request Property', 'Requests Properties');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->removeField('value');

        // ------ CRUD FIELDS
        $requestFieldOptions = [  // Select2
            'label' => "Request",
            'type' => 'select2_with_options',
            'name' => 'request_id', // the db column for the foreign key
            'entity' => 'request', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Request', // foreign key model
        ];

        $propertyFieldOptions = [  // Select2
            'label' => "Property",
            'type' => 'select2_with_options',
            'name' => 'property_id', // the db column for the foreign key
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Property', // foreign key model
        ];

        $valueFieldOptions = [ // Select2
            'label' => 'Value',
            "name" => 'value',
            'type' => 'textarea'
        ];

         $this->crud->addFields([$requestFieldOptions, $propertyFieldOptions, $valueFieldOptions], 'update/create/both');

        // ------ CRUD COLUMNS
        $this->crud->removeColumn('value');

        $requestColumnOptions = [
            // 1-n relationship
            'label' => "Request", // Table column heading
            'type' => "select",
            'name' => 'request_id', // the column that contains the ID of that connected entity;
            'entity' => 'request', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => 'App\Request' // foreign key model
        ];
        $propertyColumnOptions = [
            // 1-n relationship
            'label' => "Property", // Table column heading
            'type' => "select",
            'name' => 'property_id', // the column that contains the ID of that connected entity;
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => 'App\Property' // foreign key model
        ];
        $valueColumnOptions = [
            "label" => "Value",
            "name" => 'value',
            "type" => 'textarea'
        ];

        $this->crud->addColumns([$requestColumnOptions, $propertyColumnOptions, $valueColumnOptions]);

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
         $this->crud->enableExportButtons();

         $this->crud->with(["request" => function($query){$query->with(["category", "user"]);}, "property"]); // eager load relationships
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
