<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CategoryPropertyRequest as StoreRequest;
use App\Http\Requests\CategoryPropertyRequest as UpdateRequest;

class CategoryPropertyCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CategoryProperty');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/category_property');
        $this->crud->setEntityNameStrings('category property', 'categories properties');

        $this->crud->enableExportButtons();

        $this->crud->setFromDb();

        $this->crud->removeColumn('required');

        // ------ CRUD FIELDS
        $categoryDisabledFieldOptions = [  // Select2
            'label' => "Category",
            'type' => 'select2_with_options',
            'name' => 'category_id', // the db column for the foreign key
            'entity' => 'category', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Category', // foreign key model
            'element_options' => [
                'disabled' => true
            ],
            'select2_options' => [] // list of options that will be based to select2 init function
        ];
        $propertyDisableFieldOptions = [  // Select2
            'label' => "Property",
            'type' => 'select2_with_options',
            'name' => 'property_id', // the db column for the foreign key
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Property', // foreign key model
            'element_options' => [
                'disabled' => true
            ],
        ];

        $categoryFieldOptions = [  // Select2
            'label' => "Category",
            'type' => 'select2_with_options',
            'name' => 'category_id', // the db column for the foreign key
            'entity' => 'category', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Category', // foreign key model
        ];
        $propertyFieldOptions = [  // Select2
            'label' => "Property",
            'type' => 'select2_with_options',
            'name' => 'property_id', // the db column for the foreign key
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Property', // foreign key model
        ];

        $requiredFieldOptions = [ // Boolean
            'Label' => 'Required',
            'name' => 'required',
            'type' => 'checkbox'
        ];
        $this->crud->addField($requiredFieldOptions, 'update/create/both');

        $this->crud->addField($categoryDisabledFieldOptions, 'update')->beforeField('required');
        $this->crud->addField($propertyDisableFieldOptions, 'update')->afterField('category_id');

        $this->crud->addField($categoryFieldOptions, 'create');
        $this->crud->addField($propertyFieldOptions, 'create');

        // ------ CRUD COLUMNS
        $idColumnOptions = [
            'label' => 'ID',
            'type' => 'text',
            'name' => 'id'
        ];
        $categoryColumnOptions = [
            // 1-n relationship
            'label' => "Category Name", // Table column heading
            'type' => "select",
            'name' => 'category_id', // the column that contains the ID of that connected entity;
            'entity' => 'category', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => 'App\Category' // foreign key model
        ];

        $propertyColumnOptions = [
            // 1-n relationship
            'label' => "Property Title", // Table column heading
            'type' => "select",
            'name' => 'property_id', // the column that contains the ID of that connected entity;
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => 'App\Property' // foreign key model
        ];

        $requiredColumnOptions = [
            'label' => 'Required',
            'type' => 'boolean',
            'name' => 'required',
            'options' => [0 => 'No', 1 => 'Yes'],
            'after' => 'property_id'
        ];

        $this->crud->addColumn($idColumnOptions);
        $this->crud->addColumn($categoryColumnOptions);
        $this->crud->addColumn($propertyColumnOptions);
        $this->crud->addColumn($requiredColumnOptions);

        $this->crud->with(["category", "property"]); // eager load relationships
        $this->crud->orderBy("id");
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
