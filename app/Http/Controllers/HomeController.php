<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    public function getFile(Request $request, $path){
        $fileName = \Crypt::decrypt($path); // TODO undo this
        if($fileName != null && \Storage::exists($fileName)) {
            $file = \Storage::get($fileName);
        }else{
            $file = \Storage::disk('public')->get('images/default.png');
        }
        $image = Image::make($file);
        return response($file, 200)->header('Content-Type', $image->mime());
    }
}
