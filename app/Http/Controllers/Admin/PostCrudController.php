<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PostRequest as StoreRequest;
use App\Http\Requests\PostRequest as UpdateRequest;

class PostCrudController extends CrudController
{

    public function setUp()
    {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel('App\Post');
        $this->crud->setRoute("admin/posts");
        $this->crud->setEntityNameStrings('post', 'posts');

        $this->crud->enableExportButtons();

        $this->crud->setFromDb();

        $this->crud->setColumns(["id", "title"]);
        $this->crud->addColumn([
            "label" => "User",
            "type" => "select",
            "name" => "user",
            "entity" => "user",
            "attribute" => "name",
            "model" => 'App\User'
        ]);

        $this->crud->addField(
            [  // Select
                'label' => "Category",
                'type' => 'select',
                'name' => 'category_id', // the db column for the foreign key
                'entity' => 'category', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => 'App\Category' // foreign key model
            ], 'update/create/both');

        $this->crud->with(["user"]); // eager load relationships
        $this->crud->orderBy("id");

        $this->crud->removeButton('create');
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
