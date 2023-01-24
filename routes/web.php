<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('adminLogin'));
});

Route::namespace('App\\Http\\Controllers\\Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace

    Route::prefix('admin')->group(function () {

        Route::get('login', 'AuthAdminLoginController@getLogin')->name('adminLogin');

        Route::post('login', 'AuthAdminLoginController@postLogin');
        Route::get('resetPassword', 'AuthAdminLoginController@getResetPassword');


        //Protected Urls by Auth
        Route::middleware(['auth:admin'])->group(function () {

            Route::get('dash-board', 'AdminDashBoardController@index')->name('adminDashBoard');
            Route::post('dash-board/getCalendar', 'AdminDashBoardController@postCalendar')->name('DashBoardgetCalendar');
            Route::get('logout', 'AdminDashBoardController@logout')->name('adminLogout');

            Route::middleware('type')->group( function () {
                Route::middleware('user')->group( function () {
                    //Users Controller
                    Route::get('users', 'UserController@getIndex');
                    Route::get('users/index', 'UserController@getIndex')->name("usersIndex");
                    Route::post('users/rows', 'UserController@postRows')->name("usersRows");
                    //Add
                    Route::get('users/add', 'UserController@getAdd');
                    Route::post('users/add', 'UserController@postAdd');
                    //Edit
                    Route::get('users/edit/{id}', 'UserController@getEdit');
                    Route::post('users/edit/{id}', 'UserController@postEdit');
                    //Delete
                    Route::get('users/delete/{id}', 'UserController@getDelete');
                });
    
                //Service Controller
                Route::get('services', 'ServiceController@getIndex');
                Route::get('services/index', 'ServiceController@getIndex')->name('servicesIndex');
                Route::post('services/rows', 'ServiceController@postRows')->name('servicesRows');
                //Add
                Route::get('services/add', 'ServiceController@getAdd');
                Route::post('services/add', 'ServiceController@postAdd');
                //Edit
                Route::get('services/edit/{id}', 'ServiceController@getEdit');
                Route::post('services/edit/{id}', 'ServiceController@postEdit'); 
                //Delete
                Route::get('services/delete/{id}', 'ServiceController@getDelete');
    
                //Car Controller
                Route::get('cars', 'CarController@getIndex');
                Route::get('cars/index', 'CarController@getIndex')->name('carsIndex');
                Route::post('cars/rows', 'CarController@postRows')->name('carsRows');
                //Add
                Route::get('cars/add', 'CarController@getAdd');
                Route::post('cars/add', 'CarController@postAdd');
                //Edit
                Route::get('cars/edit/{id}', 'CarController@getEdit');
                Route::post('cars/edit/{id}', 'CarController@postEdit');
                //Delete
                Route::get('cars/delete/{id}', 'CarController@getDelete');

                //Job-Entry
                Route::get('job-entrys/edit/{id}', 'JobEntryController@getEdit');
                Route::post('job-entrys/edit/{id}', 'JobEntryController@postEdit');
                
                Route::get('job-entrys/delete/{id}', 'JobEntryController@getDelete');

                //Repairs
                Route::get('job-entrys/{id}/repairs/edit/{repairs_id}', 'RepairController@getEdit');
                Route::post('job-entrys/{id}/repairs/edit/{repairs_id}', 'RepairController@postEdit');
    
                Route::get('job-entrys/{id}/repairs/delete/{repairs_id}', 'RepairController@getDelete');
            });

            //Modal
            Route::get('get-image/{car_plate}/{nameImage}', 'ModalController@getImage');
            Route::get('get-parts/{car_plate}/{nameImage}', 'ModalController@getPart');

            //Job-Entry
            Route::get('job-entrys/', 'JobEntryController@getIndex');
            Route::get('job-entrys/index', 'JobEntryController@getIndex')->name('job-entrysIndex');
            Route::post('job-entrys/rows', 'JobEntryController@postRows')->name('job-entrysRows');

            //Add
            Route::get('job-entrys/add', 'JobEntryController@getAdd');
            Route::post('job-entrys/add', 'JobEntryController@postAdd');

            //Details
            Route::get('job-entrys/details/{id}', 'DetailsController@getDetails')->name('detailsIndex');
            Route::post('job-entrys/details/{id}', 'DetailsController@postDetails')->name('detailsRows');
          
            //Repairs
            // Route::get('job-entrys/details/', 'RepairController@getIndex')->name('repairsIndex');
            Route::get('job-entrys/workshop/{id}', 'RepairController@getIndex')->name('repairsIndex');
            Route::post('job-entrys/worskhop/{id}', 'RepairController@postRows')->name('repairsRows');

            Route::get('job-entrys/{id}/repairs/add', 'RepairController@getAdd');
            Route::post('job-entrys/{id}/repairs/add', 'RepairController@postAdd');

            Route::get('job-entrys/{id}/repairs/{repair_id}', 'RepairController@postEdit');
            Route::post('job-entrys/{id}/repairs/{repair_id}', 'RepairController@postEdit');

            Route::get('job-entrys/{id}/repairs/{repair_id}', 'RepairController@getDelete');
            
            //Notes
            Route::get('notes/', 'NoteController@getIndex');
            Route::get('notes/index', 'NoteController@getIndex')->name('notesIndex');
            Route::post('notes/rows', 'NoteController@postRows')->name('notesRows');

            Route::get('generate-note/{id}', 'NoteController@getGenerateNote');
            Route::post('generate-note/{id}', 'NoteController@createNote');

            Route::get('notes/{path}/consult/{id}', 'NoteController@consultPdf');
            Route::get('notes/{path}/download/{id}', 'NoteController@downloadPdf');
            Route::get('notes/{path}/send/{id}', 'NoteController@getSend');
            Route::post('notes/{path}/send/{id}', 'NoteController@postSend');

            //Test
            //Route::get('test-pdf/{id}', 'TestPdfController@createNote');

        });
    });
});