<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UserRequest as UpdateRequest;
use App\Http\Requests\UserRequest as RestoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserCrudController extends CrudController
{

    public function setUp()
    {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel('App\User');
        $this->crud->setRoute('admin/users');
        $this->crud->setEntityNameStrings('user', 'users');

        $this->crud->enableExportButtons();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

        $this->crud->setFromDb();

        $this->crud->removeField('password', 'update');
        $this->crud->removeField('api_token', 'update/create/both');

        $this->crud->setColumns(['id', 'name', 'username', 'email', 'phone', 'address']);
        $this->crud->addColumn([
            'type' => 'boolean',
            'name' => 'is_admin'
        ])->afterColumn('email');

        $this->crud->addField([
            'label' => "Full Name",
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'placeholder' => 'Full Name',
                'required' => 'required',
                'minlength' => '3',
                'maxlength' => '64'
            ]
        ], 'update/create/both');

        $this->crud->addField([
            'label' => "Username",
            'type' => 'text',
            'name' => 'username',
            'attributes' => [
                'placeholder' => 'username',
                'required' => 'required',
                'minlength' => '4',
                'maxlength' => '64'
            ]
        ], 'update/create/both');

        $this->crud->addField([
            'label' => "Email",
            'type' => 'email',
            'name' => 'email',
            'attributes' => [
                'placeholder' => 'email@example.com',
                'required' => 'required',
                'maxlength' => '191'
            ]
        ], 'update/create/both');

        $this->crud->addField([
            'label' => "Password",
            'type' => 'password',
            'name' => 'password',
            'attributes' => [
                'placeholder' => 'Password',
                'required' => 'required',
                'minlength' => 6
            ]
        ], 'create');

        $this->crud->addField([
            'label' => "is admin ?",
            'type' => 'checkbox',
            'name' => 'is_admin'
        ], 'update/create/both');

        $phoneRegexPattern = config('constants.PHONE_VALIDATION_REGX');
        $phoneRegexPattern = str_replace(['/', '^', '$'], '', $phoneRegexPattern);
        $this->crud->addField([
            'label' => "Phone",
            'type' => 'text',
            'name' => 'phone',
            'attributes' => [
                'placeholder' => '0590000000 or 0569000000',
                'required' => 'required',
                'minlength' => 10,
                'maxlength' => 10,
                'pattern' => $phoneRegexPattern
            ]
        ], 'update/create/both');

        $this->crud->addField([
            'label' => "Address",
            'type' => 'text',
            'name' => 'address',
            'attributes' => [
                'placeholder' => 'Address'
            ]
        ], 'update/create/both');

        $stack = 'line';
        $name = 'restore';
        $view = 'restore';
        $position = 'end';

        // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        $this->crud->addButtonFromView($stack, $name, $view, $position);
        $this->crud->allowAccess('restore');

        $this->crud->addFilter([ // simple filter
            'type' => 'simple',
            'name' => 'trashed',
            'label' => 'Show Deleted Users'
        ], false, function () { // if the filter is active
            $this->crud->addClause('onlyTrashed');
            $this->crud->removeButton('delete');
            $this->crud->removeButton('update');
        });

        $this->crud->with('posts'); // eager load relationships
        $this->crud->orderBy('id');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $currentUserId = $this->request->user()->id;

        if($currentUserId == $id){
            throw new BadRequestHttpException("Can not delete your self");
        }

        return $this->crud->delete($id);
    }

    public function restore(RestoreRequest $request){
        $userId = (int) $request->id;
        $restored = User::onlyTrashed()->where('id', '=', $userId)->restore();
        if($restored){
            return response()->json(["message" => 'User restored successfully']);
        }else{
            $exception = new ModelNotFoundException();
            $exception->setModel(User::class, [$userId]);
            throw $exception;
        }
    }
}
