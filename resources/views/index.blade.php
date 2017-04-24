@extends('layouts.app')

@section('stylesheets')
  @parent
  <link rel="stylesheet" href="{{ mix('css/index.css')  }}">
@endsection

@section('content')
<div id="posts-container" class="init">
  <div class="row"></div>
</div>
@endsection

@section('scripts')
  @parent
  <script src=" {{ asset('js/home.js') }} "></script>
@endsection

@section('lodash-templates')
  @parent
@endsection
