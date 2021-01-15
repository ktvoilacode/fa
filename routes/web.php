<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Middleware\Subdomain;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::group(['middleware' => Subdomain::class], function () {

Route::get('/layout2', 'HomeController@index')->name('root');
Route::get('/', 'HomeController@welcome');

Route::get('/home', 'HomeController@dashboard')->name('home')->middleware('auth');
Route::get('/gre', 'HomeController@gre')->name('home.gre');

// login routes
Auth::routes();

//under construction page
Route::get('/construction', function () {
        return view('appl.pages.construction');
})->name('construction');

Route::get('/onlinetraining', function () {
        return view('appl.pages.onlinetraining');
})->name('onlinetraining');
Route::get('/toefl', function () {
        return view('appl.pages.toefl');
})->name('toefl');

Route::get('/help/mcq', function () {
        return view('appl.pages.mcq');
})->name('mcq');
Route::get('/help/fillup', function () {
        return view('appl.pages.fillup');
})->name('fillup');

Route::post('audioblob','Test\AttemptController@saveAudio')->name('audio.blob');


/* Admin Routes */
Route::get('/admin', 'Admin\AdminController@index')->name('admin')->middleware('auth');
Route::get('/admin/analytics', 'Admin\AdminController@analytics')->name('admin.analytics')->middleware('auth');
Route::post('/admin/contact', 'Admin\AdminController@contact')->name('admin.contact');
Route::post('/admin/notify', 'Admin\AdminController@notify')->name('admin.notify');
Route::post('/ajax/form','Admin\FormController@ajaxx')->name('form.a');

/* Admin Application Routes */
Route::get('/admin/test/createlist', function(){
    return view('appl.test.test.createlist');
})->middleware('auth')->name('test.createlist');
Route::resource('/admin/test', 'Test\TestController')->middleware('auth');
Route::get('/admin/test/{test}/view', 'Test\AttemptController@view')->middleware('auth')->name('test.view');
Route::get('/admin/test/{test}/cache', 'Test\TestController@cache')->middleware('auth')->name('test.cache');

Route::get('/admin/test/{test}/cache_delete', 'Test\TestController@cache_delete')->middleware('auth')->name('test.cache.delete');
Route::get('/admin/test/{test}/fillup_layout', 'Test\FillupController@layout')->name('fillup.layout')->middleware('auth');
Route::get('/admin/test/{test}/mcq_layout', 'Test\McqController@layout')->name('mcq.layout')->middleware('auth');
Route::get('/admin/test/{test}/fillup/{fillup}/d', 'Test\FillupController@d')->name('fillup.d')->middleware('auth');
Route::get('/admin/test/{test}/mcq/{mcq}/d', 'Test\McqController@d')->name('mcq.d')->middleware('auth');
Route::get('/admin/test/{test}/section/{id}/ajax', 'Test\SectionController@ajaxupdate')->name('section.ajaxupdate')->middleware('auth');
Route::get('/admin/test/{test}/fillup/{id}/ajax', 'Test\FillupController@ajaxupdate')->name('fillup.ajaxupdate')->middleware('auth');

Route::resource('/admin/category', 'Test\CategoryController')->middleware('auth');
Route::resource('/admin/tag', 'Test\TagController')->middleware('auth');
Route::resource('/admin/test/{test}/section', 'Test\SectionController')->middleware('auth');
Route::resource('/admin/test/{test}/extract', 'Test\ExtractController')->middleware('auth');
Route::resource('/admin/test/{test}/mcq', 'Test\McqController')->middleware('auth');
Route::resource('/admin/test/{test}/fillup', 'Test\FillupController')->middleware('auth');
Route::get('/admin/test/{test}/questions', 'Test\TestController@questions')->name('test.questions')->middleware('auth');
Route::get('/admin/test/{test}/analytics', 'Test\TestController@analytics')->name('test.analytics')->middleware('auth');
Route::get('/admin/test/{test}/qanalytics', 'Test\TestController@qanalytics')->name('test.qanalytics')->middleware('auth');
Route::get('/admin/test/{test}/duplicate', 'Test\TestController@duplicate')->name('test.duplicate')->middleware('auth');

Route::resource('/admin/file', 'Test\FileController')->middleware('auth');
Route::get('/admin/{file}/download','Test\FileController@download')->name('file.download');
Route::get('/admin/{file}/notify','Test\FileController@notify')->name('review.notify');
Route::get('/admin/{file}/assign','Test\FileController@assign')->name('file.assign');
Route::post('/admin/{file}/assign','Test\FileController@assignupdate')->name('file.assign');

Route::get('/admin/prospect/dashboard', 'Admin\ProspectController@dashboard')->middleware('auth')->name('prospect.dashboard');
Route::resource('/admin/prospect', 'Admin\ProspectController')->middleware('auth');
Route::resource('/admin/followup', 'Admin\FollowupController')->middleware('auth');
Route::resource('/admin/group', 'Test\GroupController')->middleware('auth');
Route::resource('/admin/type', 'Test\TypeController')->middleware('auth');

Route::resource('/admin/form', 'Admin\FormController')->middleware('auth');

Route::get('/request-form','Admin\FormController@request')->name('form.request');
Route::post('/request-form','Admin\FormController@save')->name('form.save');


/* User Routes */
Route::resource('/admin/user', 'User\UserController')->middleware('auth');
Route::get('/admin/user/{user}/{test}','User\UserController@test')->middleware('auth')->name('user.test');
Route::post('/admin/user/{user}/{test}','User\UserController@test')->middleware('auth')->name('user.test');

/* Editor routes */
Route::get('/admin/editor','Admin\EditorController@index')->middleware('auth')->name('editor.index');
Route::get('/admin/editor/page','Admin\EditorController@page')->middleware('auth')->name('editor.page');
Route::post('/admin/editor/page','Admin\EditorController@page')->middleware('auth')->name('editor.post');



/* Test Attempt Routes */
Route::get('/test/','Test\TestController@public')->name('tests');
Route::get('/test/{test}','Test\TestController@details')->name('test');
Route::get('/test/{test}/instructions','Test\AttemptController@instructions')->name('test.instructions');
Route::get('/test/{test}/try','Test\AttemptController@try')->name('test.try');
Route::post('/test/{test}/try','Test\AttemptController@store')->name('attempt.store');
Route::post('/test/{test}/upload','Test\AttemptController@upload')->name('attempt.upload');
Route::get('/test/{test}/delete','Test\AttemptController@file_delete')->name('attempt.delete');
Route::get('/test/{test}/review','Test\AttemptController@review')->name('test.review');

Route::get('/test/{test}/evaluation','Test\AttemptController@evaluation')->name('attempt.evaluation');
Route::get('/test/{test}/analysis','Test\AttemptController@analysis')->name('test.analysis');
Route::post('/test/{test}/analysis','Test\AttemptController@analysis')->name('test.analysis');
Route::get('/test/{test}/analytics','Test\TestController@analytics')->name('test.oanalytics');
Route::get('/test/{test}/solutions','Test\AttemptController@solutions')->middleware('auth')->name('test.solutions');
Route::get('/test/{test}/answers','Test\AttemptController@view')->middleware('auth')->name('test.answers');


/* Product Routes */
Route::resource('/admin/product', 'Product\ProductController')->middleware('auth');
Route::resource('/admin/coupon', 'Product\CouponController')->middleware('auth');
Route::resource('/admin/order', 'Product\OrderController')->middleware('auth');
Route::resource('/admin/client', 'Product\ClientController')->middleware('auth');
Route::get('/orders', 'Product\OrderController@myorders')->middleware('auth')->name('myorders');
Route::get('/orders/{order}', 'Product\OrderController@myordersview')->middleware('auth')->name('myorder.view');

Route::resource('/admin/track', 'Course\TrackController')->middleware('auth');
Route::resource('/admin/track/{track}/session', 'Course\SessionController')->middleware('auth');
Route::get('/session/{session}','Course\SessionController@url')->name('session.url')->middleware('auth');
Route::get('/session/{session}/join','Course\SessionController@join')->name('session.join')->middleware('auth');
Route::get('/mytracks','Course\TrackController@mytracks')->name('tracks.url')->middleware('auth');

/* product redirect */
Route::get('products/ielts-short-test', function () {
    return redirect('products/ielts-mini-test');
});
/* Product/Orders Public Routes */
Route::get('/products','Product\ProductController@public')->name('product.public');
Route::get('/products/{product}','Product\ProductController@view')->name('product.view');
Route::get('/det', function(){
    return view('appl.pages.det');
})->name('det.page');
Route::get('/checkout/{product}','Product\OrderController@checkout')->name('product.checkout')->middleware('auth');
Route::get('/checkout-access/{product}','Product\OrderController@checkout_access')->name('product.checkout-access')->middleware('auth');
Route::post('/order','Product\OrderController@order')->name('product.order');
Route::get('/order_payment', 'Product\OrderController@instamojo_return');
Route::post('/order_payment', 'Product\OrderController@instamojo_return');
Route::get('/couponcode','Product\CouponController@try')->name('coupon.try')->middleware('auth');
Route::get('/couponcode/code','Product\CouponController@use')->name('coupon.use')->middleware('auth');


/* Pages */

Route::get('/contact', function(){ return view('appl.pages.contact');})->name('contact');
Route::get('/frame', function(){ return view('appl.pages.terms');})->name('terms');
Route::get('/downloads', function(){ return view('appl.pages.downloads');})->name('downloads');


Route::post('/api/register', 'User\UserController@register')->name('apiregister');
Route::post('/api/login', 'User\UserController@login')->name('apilogin');
Route::get('/api/login', 'User\UserController@login')->name('apilogin');
Route::get('/api/phone', 'User\UserController@phone')->name('apiphone');
Route::get('/user/edit', 'User\UserController@useredit')->name('useredit');
Route::post('/user/edit', 'User\UserController@userstore')->name('userstore');

/* user verify routes */
Route::get('/activation', 'User\VerifyController@activation')->name('activation')->middleware('auth');
Route::post('/activation', 'User\VerifyController@activation')->name('activation');
Route::get('/activation/mail/{token}', 'User\VerifyController@email')->name('email.verify');

Route::post('/activation/phone', 'User\VerifyController@sms')->name('sms.verify');

/* Blog Routes */
Route::post('/blog/tooltip', 'Blog\BlogController@tooltip')->name('tooltip');
Route::get('/blog/tooltip', 'Blog\BlogController@tooltip')->name('tooltip');
Route::resource('/blog', 'Blog\BlogController');

Route::resource('/admin/label', 'Blog\LabelController')->middleware('auth');
Route::resource('/admin/collection', 'Blog\CollectionController')->middleware('auth');

/* Page Routes */
Route::resource('/admin/page', 'Admin\PageController')->middleware('auth');


Route::get('/category/{category}', 'Blog\CollectionController@list')->name('category.list');
Route::get('/tag/{tag}', 'Blog\LabelController@list')->name('tag.list');
Route::get('/{year}/{month}', 'Blog\CollectionController@yearmonth')->name('year.list');

Route::get('/ieltspage', function(){
    return view('appl.pages.ielts');
})->name('ielts.page');


Route::get('/enroll', function(){
    return view('appl.pages.enroll');
})->name('enroll.page');
Route::get('/enroll2', function(){
    return view('appl.pages.enroll2');
})->name('enroll2.page');
Route::get('/reviews', function(){
    return view('appl.pages.testimonials');
})->name('reviews.page');

Route::get('/first', function(){
    return view('first')->with('front',1);
})->name('first');

Route::get('/team', function(){
    return view('appl.pages.team');
})->name('team.page');
Route::get('/scores', function(){
    return view('appl.pages.scores');
})->name('scores.page');
Route::get('/courses', function(){
    return view('appl.pages.courses');
})->name('courses.page');

Route::get('/studyabroad', function(){
    return view('appl.pages.studyabroad');
})->name('studyabroad.page');

Route::get('/{page}','Admin\PageController@show')->name('page.view');
Route::get('/{page}/{s1}','Admin\PageController@show')->name('page.s1');
Route::get('/{page}/{s1}/{s2}','Admin\PageController@show')->name('page.s2');
/* learners club */
Route::get('/learnersclub', function(){
    return view('appl.pages.lclub');
})->name('listening');

/* Sample Routes */


Route::get('/listening', function(){
    return view('appl.pages.listening');
})->name('listening');

Route::get('/reading', function(){
    return view('appl.pages.reading')->with('reading',1);
})->name('reading');

Route::get('/pricing', function(){
    return view('appl.pages.pricing');
})->name('pricing');

});