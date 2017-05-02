<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if (config('app.locale') == "ar") dir="rtl" @else dir="ltr" @endif >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/smoke.min.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

    <!-- App Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @if ( config('app.locale') == "ar")
    <!-- Bootstrap RTL custom css library  -->
    <!-- Source https://github.com/morteza/bootstrap-rtl -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-rtl.min.css') }}">
    @endif

    @yield('stylesheets')

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'userId' => Auth::user() ? Auth::user()->id : null,
            'apiToken' => Auth::user() ? Auth::user()->api_token : null,
            'dir' => config('app.locale') == "ar" ? "rtl" : "ltr",
            'locale' => config('app.locale', 'en'),
            'config' => [
                'postMediaImageCount' => 3,
                'postMediaImageRequiredCount' => 1,
                'postMediaImageNamingTemplate' => 'MEDIA_IMAGE_{0}'
            ],
            "pages" => [
                "home" => route('home')
            ],
            'apis' => [
              "posts" => [
                "list" => route('posts.index'),
                "get" => urldecode(route('posts.show', ["post" => "<% print(postId) %>"])),
                "store" => route('posts.store'),
                'update' => urldecode(route('posts.update', ["post" => "<% print(id) %>"]))
              ],
              "categories" => [
                "list" => route('categories.index')
              ],
              "users" => [
                "get" => urldecode(route('users.show', ["user" => "<% print(userId) %>"])),
                "getPosts" => urldecode(route('users.posts', ["user" => "<% print(userId) %>"])),
                "getRequests" => urldecode(route('users.requests', ["user" => "<% print(id) %>"])),
              ],
              "requests" => [
                "store" => route('requests.store'),
                "update" => str_replace('ID','{0}', route('requests.update', ["request" => "ID"])),
                "get" => str_replace('ID','{0}', route('requests.show', ["request" => "ID"])),
                "delete" => urldecode(route('requests.destroy', ["request" => "<% print(id) %>"])),
              ]
            ],
            "strings" => [
                "general" => [
                    "loading_message" => __("Loading").' ...',
                    "description" => __("Description"),
                    "property" => __("Property"),
                    "no_posts" => __("No Posts"),
                    "copy_to_clipboard_success" => __("Text copied to clipboard successfully"),
                    "copy_to_clipboard_fail" => __("Failed To Copy text to clipboard, please try again later")
                ],
                "validation" => [
                    "required" => __("This field is required"),
                    "min" => __("This field length must be at least {0} character(s)"),
                    "max" => __("The max length is {0}")
                ],
                "post_category_type_modal" => [
                    "modal_title" => __('Select Post Category'),
                    "modal_confirm_button_text" => __('Select'),
                    "default_item_text" => __('Select Post Category')
                ],
                "create_post_modal" => [
                    "submit_form_button_text" => __("Add Post"),
                    "form" => [
                        "title_field_title" => __("Post Title"),
                        "title_field_hint" => __("Test Post"),
                        "image_group_label" => __("Select Post Images"),
                        "file_image_input_button_text" => __("Select Image"),
                        "image_required_help_text" => __("You must select at least one image")
                    ]
                ],
                "edit_post_modal" => [
                    "modal_title" => __("Edit Post"),
                    "submit_form_button_text" => __("Edit Post")
                ],
                "post_info_modal" => [
                    "advertiser_name_title" => __('Advertiser'),
                    "category_name_title" => __('Category'),
                    "btn_edit_post" => '<i class="fa fa-pencil" aria-hidden="true"></i> '.__("Edit Post"),
                    "btn_delete_post" => '<i class="fa fa-trash" aria-hidden="true"></i> '.__("Delete Post"),
                    "delete_post_confirm_message" => __("Are you sure, you want to delete the post ?"),
                    "btn_delete_post_confirm_text" => '<i class="fa fa-trash" aria-hidden="true"></i> '. __("Delete"),
                    "btn_delete_post_cancel_text" => __("Cancel")
                ],
                "user_info_modal" => [
                    "modal_title" => __("Advertiser Info"),
                    "user_name_title" => __("User Name"),
                    "mobile_number_title" => __("Mobile Number"),
                    "address_title" => __("Address")
                ],
                "user_posts_modal" => [
                    "modal" => [
                        "title" => __("Posts")
                    ],
                    "table_columns_titles" => [
                        "title" => __('Post Title'),
                        "category" => __('Category'),
                        "created_at" => __('Created At'),
                        "last_update_date" => __('Last Update')
                    ]
                ],
                "user_requests" => [
                    "types" => [
                        "ALL" => __("Any Post Type")
                    ]
                ],
                "user_requests_modal" => [
                    "modal" => [
                        "title" => __("Requests of")
                    ],
                    "table_columns_titles" => [
                        "category" => __('Category'),
                        "properties" => __('Request Properties')
                    ]
                ],
                "delete_request_confirm_modal" => [
                    "title" => __('Delete Request'),
                    "message" => __("Are you sure, you want to delete the request ?"),
                    "btn" => [
                        "confirm" => __("Delete"),
                        "cancel" => __("Cancel")
                    ]
                ],
                "create_request_modal" => [
                    "modal_title" => __("Create Request"),
                    "help_message" => __("Leave the field empty if you need all values"),
                    "skip_field_value_checkbox_text" => __("Check the box if you need to ignore the {0} value"),
                    "btn" => [
                        "submit" => __("Create Request"),
                        "edit" => __("Edit Request")
                    ]
                ]
            ],
            "assets" => [
                "dataTables" => [
                    "lang" => [
                        "default" => config('app.locale') == "ar" ? asset('js/lang/dataTables/ar.json'): null,
                        "ar"=> asset('js/lang/dataTables/ar.json')
                    ]
                ]
            ]
        ]) !!};
    </script>
</head>
<body>
    <div id="modals-container"></div>
    <div id="app">
        <div id="social-icons-container">
            <a class="social-icon" href="javascript:void(0);" title="facebook" data-toggle="tooltip" data-placement="bottom"><i aria-hidden="true" class="fa fa-facebook-official fa-3x"></i></a>
            <a class="social-icon" href="javascript:void(0);" title="twitter" data-toggle="tooltip" data-placement="bottom"><i aria-hidden="true" class="fa fa-twitter fa-3x"></i></a>
            <a class="social-icon" href="javascript:void(0);" title="instagram" data-toggle="tooltip" data-placement="bottom"><i aria-hidden="true" class="fa fa-instagram fa-3x"></i></a>
            <a class="social-icon" href="javascript:void(0);" title="youtube" data-toggle="tooltip" data-placement="bottom"><i aria-hidden="true" class="fa fa-youtube fa-3x"></i></a>
            <a class="social-icon" href="javascript:void(0);" title="snapchat" data-toggle="tooltip" data-placement="bottom"><i aria-hidden="true" class="fa fa-snapchat-ghost fa-3x"></i></a>
        </div>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">&nbsp;</ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right flip">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a id="btn-show-login-modal" href="javascript:void(0);">{{ __('Login') }}</a></li>
                            <li><a id="btn-show-sign-up-modal" href="javascript:void(0);">{{ __('Register') }}</a></li>
                        @else
                            <!-- Requests Dropdown -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-eye fa-2x" aria-hidden="true"></i> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" id="btn-add-request"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New Request")  }} </a></li>
                                    <li><a href="javascript:void(0);" class="btn-show-user-requests" data-user-id="{{ Auth::user()->id }}"><i class="fa fa-list" aria-hidden="true"></i> {{ __("View My Requests")  }}</a></li>
                                </ul>
                            </li>

                            <!-- Posts Dropdown -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-pushpin bt-icon-lg"></span> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" id="btn-create-post"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New Post")  }} </a></li>
                                    <li><a href="javascript:void(0);" class="btn-show-user-posts" data-user-id="{{ Auth::user()->id }}"><i class="fa fa-list" aria-hidden="true"></i> {{ __("View My Posts")  }}</a></li>
                                </ul>
                            </li>

                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user fa-2x" aria-hidden="true"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{ __('Logout') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"></form>
                                    </li>
                                    @if(Auth::user()->is_admin == true)
                                    <li>
                                        <a href="{{ url('admin') }}"><i class="fa fa-tachometer" aria-hidden="true"></i> {{ __('Admin Dashboard') }}</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row-offcanvas row-offcanvas-left">
          <div id="sidebar" class="sidebar-offcanvas">
              <div class="col-md-12">
                <div class="posts-search-container">
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-search-posts" placeholder="{{ __("Search for") }} ...">
                    <span class="input-group-btn">
                      <button id="btn-search-posts" class="btn btn-info" type="button" title=" {{ __("Search for") }} "><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                  </div><!-- /input-group -->
                </div>
              </div>
              <div class="col-md-12">
                  <div id="category-filter-container">
                      <h5>{{ __("Category")  }}</h5>
                      <select id="category-filter" class="form-control">
                          <option value="">{{ __("Select the category")  }}</option>
                          <option value="CARS">{{ __('Cars') }}</option>
                          <option value="APARTMENTS">{{ __('Apartments') }}</option>
                          <option value="MOBILES">{{ __('Mobiles') }}</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                  <div id="filters-container"></div>
              </div>
          </div>

          <div id="main">
              <div class="col-md-12">
                  @yield('content')
              </div>
          </div>
        </div><!--/row-offcanvas -->
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/watch.min.js')  }}"></script>

    <script src="https://use.fontawesome.com/a8e79672ec.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.6.0/clipboard.min.js"></script>

    <script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/js.storage.min.js') }}"></script>
    <script src="{{ asset('js/smoke/smoke.min.js')  }}"></script>
    <script src="{{ asset('js/moment/moment.min.js')  }}"></script>
    <script src="{{ asset('js/bootstrap-filestyle.min.js')  }}"></script>

    <script src="{{ mix('js/login.js') }}"></script>

    <script src="{{ mix('js/resources/post.js')  }}"></script>
    <script src="{{ mix('js/resources/request.js')  }}"></script>

    <!-- Current Locale is {{ config('app.locale', 'en') }} -->
    <!-- Add Locale files  -->
    @if (config('app.locale', 'en') == "ar")
        <script src="{{ asset('js/smoke/lang/ar.min.js') }}"></script>
        <script src="{{ asset('js/moment/locale/ar.js')  }}"></script>
    @endif

    @yield('scripts')

    <!-- Default Templates -->
    @include('layouts.lodash_templates.bootstrap.modal')
    @include('layouts.lodash_templates.bootstrap.table')
    @include('layouts.lodash_templates.app.modal.login')
    @include('layouts.lodash_templates.app.modal.user_info_modal_body')
    @include('layouts.lodash_templates.app.resources.post')
    @include('layouts.lodash_templates.app.resources.request')
    @include('layouts.lodash_templates.app.form')
    @include('layouts.lodash_templates.app.button')
    @include('layouts.lodash_templates.app.inputs')

    @yield('lodash-templates')
</body>
</html>
