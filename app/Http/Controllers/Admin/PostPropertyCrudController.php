<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PostPropertyRequest as StoreRequest;
use App\Http\Requests\PostPropertyRequest as UpdateRequest;

class PostPropertyCrudController extends CrudController
{

    public function setUp()
    {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel('App\Models\PostProperty');
        $this->crud->setRoute("admin/post_property");
        $this->crud->setEntityNameStrings('post property', 'posts properties');

        $this->crud->enableExportButtons();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

        // ------ CRUD FIELDS
        $postFieldOptions = [  // Select2
            'label' => "Post",
            'type' => 'select2',
            'name' => 'post_id', // the db column for the foreign key
            'entity' => 'post', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Post' // foreign key model
        ];
        $propertyFieldOptions = [  // Select2
            'label' => "Property",
            'type' => 'select2',
            'name' => 'property_id', // the db column for the foreign key
            'entity' => 'property', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => 'App\Property' // foreign key model
        ];
        $this->crud->addField($postFieldOptions, 'update/create/both')->beforeField('value');
        $this->crud->addField($propertyFieldOptions, 'update/create/both')->afterField('property_id');

        // ------ CRUD COLUMNS
        $idColumnOptions = [
            'label' => 'ID',
            'type' => 'text',
            'name' => 'id'
        ];
        $postColumnOptions = [
            // 1-n relationship
            'label' => "Post", // Table column heading
            'type' => "select",
            'name' => 'post_id', // the column that contains the ID of that connected entity;
            'entity' => 'post', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => 'App\Post' // foreign key model
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

        $this->crud->addColumn($idColumnOptions);
        $this->crud->addColumn($postColumnOptions);
        $this->crud->addColumn($propertyColumnOptions);

        $this->crud->setFromDb();

        $this->crud->with(["post", "property"]); // eager load relationships
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
