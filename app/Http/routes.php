<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
######
######
######
######
######
######
######
######---------------------------------========= DEFAULT ROUTES START ==========-----------------------------------------// 

		Route::get('/', function () {
		    return view('welcome');
		});

		Route::get('/homeView', function () {
		    return view('welcome');
		});

		Route::auth();

		Route::get('/home', 'HomeController@index');

######---------------------------------========= DEFAULT ROUTES END =============-----------------------------------------//
######
######
######
######
######
######
######
######---------------------------------========= FRONTEND ROUTES START ==========-----------------------------------------//
		###
		###
		###
		###
		#### FRONTEND ROUTS WITHOUT LOGIN VALIDATION START
				Route::group(['middleware' => ['web']], function () {
						#
						#
						//-- tempController(We will change then during code implementation)

								//-- Contact Page View
								Route::get('/contact', 'tempController@contact');

								//-- Product Description Page View
								Route::get('/productDetail', 'tempController@productDetail');

								//-- Price And Covrage Page View
								Route::get('/priceCoverage', 'tempController@priceCoverage');

								//-- News Page View
								Route::get('/newsFront', 'tempController@newsFront');

								//-- Product Page View
								Route::get('/droneFront', 'tempController@droneFront');

								//-- Write Review
								Route::get('/wriiteRe', 'tempController@wriiteRe');

								//-- Single News
								Route::get('/snglNews', 'tempController@snglNews');

								//-- Single News
								Route::get('/video', 'tempController@video');

								//-- About Drone
								Route::get('/aboutDrone', 'tempController@aboutDrone');

								//-- Why Us
								Route::get('/whyUs', 'tempController@whyUs');

								//-- Our Team
								Route::get('/ourTeam', 'tempController@ourTeam');

								//-- About Us
								Route::get('/aboutUs', 'tempController@aboutUs');

								//-- Privacy Policy
								Route::get('/prPolicy', 'tempController@prPolicy');

						//-- tempController(We will change then during code implementation)
						#
						#
						//-- Feedback E-mail Form Submit
							Route::post('emailFback','emailController@emailFback');
						#
						#
						//--Cms Page Routes--(Start)

							//-- Cms Page Redirect
							Route::get('/frontCmsDefault/{id}', 'frontCmsController@frontCmsDefault');
						
							//-- Cms Page View
							Route::get('/frontCms', 'frontCmsController@frontCms');
						
							//-- Cms Inner Page Redirect
							Route::get('/frontCmsInnerDefault/{id}', 'frontCmsController@frontCmsInnerDefault');
						
							//-- Cms Inner Page View
							Route::get('/frontInnerCms', 'frontCmsController@frontInnerCms');

						//--Cms Page Routes--(End)
						#
						#
						//-- Latest News View
							Route::get('/viewNews/{id}', 'newsController@viewNews');
						#
						#
						//-- Check User Email Repeat--(User Registration Form)
							Route::post('/chkUserRegEmail', 'registerController@chkUserRegEmail');
						#
						#
						//-- Check Pilot Email Repeat--(Pilot Registration Form)
							Route::post('/chkPilRegEmail', 'registerController@chkPilRegEmail');
						#
						#
						//-- Check Company Email Repeat--(Company Registration Form)
							Route::post('/chkCompRegEmail', 'registerController@chkCompRegEmail');
						#
						#
						//-- Pilots View-(Start)--(Without Login)

							//-- View List Of Pilots
									Route::get('/pilotList', 'pcViewController@pilotList');

							//-- View Selected Pilots Profile
								Route::get('/pilotView/{idP}', 'pcViewController@pilotView');

						#-- Pilots View-(End)
						#
						#
						#-- Company View-(Start)--(Without Login)

							//-- View List Of Companies
								Route::get('/compList', 'pcViewController@compList');

							//-- View Selected Company Profile
								Route::get('/compView/{idC}', 'pcViewController@compView');

						#-- Company View-(End)
						#
						#
						//-- Search Page Routes-(Start)

							//-- View Search Page
								Route::get('/srchRslt/{idStatic}', 'searchController@srchRslt');

							//-- Search Area Coverage Filter--(View Page)
								Route::get('/aCovFilter1/{commaIdOff}/{mCat}', 'searchController@aCovFilter1');

							//-- Search Area Coverage Filter--(Get Offers In Selected Area)
								Route::get('/aCovFilter', 'searchController@aCovFilter');

							//-- Search Sub-Category Filter--(View Page)
								Route::get('/sFilter', 'searchController@sFilter');

							//-- Search Sub-Category Filter--(Get Offers In Selected SubCategories)
								Route::get('/sFilter1/{id}/{idMain}', 'searchController@sFilter1');

							//-- Price Filter--(Main Price Search)
								Route::post('/priceMainS1', 'searchController@priceMainS1');

							//-- Price Filter--(Main Search Price Redirect)
								Route::get('/priceMainS', 'searchController@priceMainS');

							//-- Price Filter--(Sub Category Price Search)
								Route::post('/priceSubS1', 'searchController@priceSubS1');

							//-- Price Filter--(Sub Category Price Search Redirect)
								Route::get('/priceSubS', 'searchController@priceSubS');

							//-- Price Filter--(A-Cover Price Search)
								Route::post('/priceAcovS1', 'searchController@priceAcovS1');

							//-- Price Filter--(A-Cover Price Search Redirect)
								Route::get('/priceAcovS', 'searchController@priceAcovS');

						//-- Search Page Routes-(End)
						#
						#
						//-- View Offer--(Without Login)--(Start)

							//-- View Added Offers List In A Category And Subcateory
								Route::get('/offerList/{idMCat}/{idSCat}', 'viewOfferController@offerList');

							//-- View Selected Offer In A Category And Subcateory
								Route::get('/viewOffer/{idOff}', 'viewOfferController@viewOffer');
								
						//-- View Offer--(End)
						#
						#
				});
		#### FRONTEND ROUTS WITHOUT LOGIN VALIDATION END
		###
		###
		###
		###
		#### FRONTEND ROUTS WITH LOGIN VALIDATION START
				Route::group(['middleware' => ['web','auth']], function () {
					#
					#
					//-- Check If User Is Enabled Or Disabled From Admin--(For Auto Logout)
						Route::get('/loggedUserTest', 'myAuthController@loggedUserTest');
					#
					#
					#
					//-- Profile Routes (User, Pilot and Company)--(Start)

						//-- View My Profile (Default)
							Route::get('/myProfile', 'loggedUserProfileController@myProfile');

						//-- View My Profile
							Route::get('/myProfileView/{id}/{role}', 'loggedUserProfileController@myProfileView');

						//-- Change My Password-(View Form)
							Route::get('/chngPassLogged/{id}/{role}', 'loggedUserProfileController@chngPassLogged');

						//-- Change My Password-(Submit Form)
							Route::post('/chngPassLoggedSub', 'loggedUserProfileController@chngPassLoggedSub');

						//-- Edit User Profile-(View Form)
							Route::get('/editUser/{id}/{role}', 'loggedUserProfileController@editUser');

						//-- Edit User Profile-(Submit Form)
							Route::post('/editUserS', 'loggedUserProfileController@editUserS');

						//-- Edit Pilot Profile-(View Form)
							Route::get('/editPilot/{id}/{role}', 'loggedUserProfileController@editPilot');

						//-- Edit Pilot Profile-(Submit Form)
							Route::post('/editPilotS', 'loggedUserProfileController@editPilotS');

						//-- Edit Company Profile-(View Form)
							Route::get('/editComp/{id}/{role}', 'loggedUserProfileController@editComp');

						//-- Edit Company Profile-(Submit Form)
							Route::post('/editCompS', 'loggedUserProfileController@editCompS');

					//-- Profile Routes (User, Pilot and Company)--(End)
					#
					#
					#
					//-- Post Offer Routes-(START)

						//-- Post a Offer Page View
							Route::get('/postOffer', 'postOfferController@postOffer');

						//-- Get Sub Categories
							Route::post('/getSubCate', 'postOfferController@getSubCate');

						//-- Get City List
							Route::post('/getCityList', 'postOfferController@getCityList');

						//-- Get Departments List
							Route::post('/getDptList', 'postOfferController@getDptList');

						//-- Use My Detail-(Checkbox)
							Route::post('/useMyDet', 'postOfferController@useMyDet');

						//-- Post Offer Last Step Submit-(Form Submit)
							Route::post('/postOfferSub', 'postOfferController@postOfferSub');

					//-- Post Offer Routes-(END)
					#
					#
					#
					//-- View Added Offer-(START)

						// //-- View Added Offers List In A Category And Subcateory
						// 	Route::get('/offerList/{idMCat}/{idSCat}', 'viewOfferController@offerList');

						// //-- View Selected Offer In A Category And Subcateory
						// 	Route::get('/viewOffer/{idOff}', 'viewOfferController@viewOffer');

						//-- Add Offer Rating--(Ajax)
							Route::post('/addOfferRating', 'viewOfferController@addOfferRating');

						//-- Post Review--(Ajax)
							Route::post('/postOffReview', 'viewOfferController@postOffReview');

						//-- Get Bar Rate %age--(Ajax)
							Route::post('/getBarVal', 'viewOfferController@getBarVal');

						//-- Submit Query Form
							Route::post('/subQueryForm', 'viewOfferController@subQueryForm');

						//-- Submit Edit Review
							Route::post('/editReSub', 'viewOfferController@editReSub');

						//-- Submit Delete Review 
							Route::post('/delReSub', 'viewOfferController@delReSub');

					//-- View Added Offer-(END)
					#
					#
					#
					//-- Edit Added Offer-(Start)

						//-- View Edit Offer Form
							Route::get('/editOffF/{id}', 'editOfferController@editOffF');

						//-- Get City List--(Edit Offer)
							Route::post('/cityListEditOff', 'editOfferController@cityListEditOff');

						//-- Get Departments List--(Edit Offer)
							Route::post('/dptListEditOff', 'editOfferController@dptListEditOff');

						//-- Submit Edit Offer Form
							Route::post('/editOfferSub', 'editOfferController@editOfferSub');

					//-- Edit Added Offer-(END)
					#
					#
					#
					//-- Added Companies And Pilots Module-(Start)
							#
							#
							#-- Pilots View-(Start) 

								// //-- View List Of Pilots
								// 	Route::get('/pilotList', 'pcViewController@pilotList');

								// //-- View Selected Pilots Profile
								// 	Route::get('/pilotView/{idP}', 'pcViewController@pilotView');

								//-- Add Rating Pilot
									Route::post('/addRatePil', 'pcViewController@addRatePil');

								//-- Add Review Pilot
									Route::post('/addReviewPil', 'pcViewController@addReviewPil');

								//-- Bar Rating Ajax--(Pilot)
									Route::post('/getBarpilV', 'pcViewController@getBarpilV');

								//-- Edit My Review--(On Pilot Profile)
									Route::post('/editRePil', 'pcViewController@editRePil');

								//-- Delete My Review--(On Pilot Profile)
									Route::post('/delRePil', 'pcViewController@delRePil');

								//-- Add Pilot To Favaurite--(On Pilot Profile)
									Route::post('/addPiltoFv', 'pcViewController@addPiltoFv');

								//-- Removr Pilot From Favaurite--(On Pilot Profile)
									Route::post('/rmvPilfrmFv', 'pcViewController@rmvPilfrmFv');

							#-- Pilots View-(End)
							#
							#
							#-- Companies View-(Start)
									
								// //-- View List Of Companies
								// 	Route::get('/compList', 'pcViewController@compList');

								// //-- View Selected Company Profile
								// 	Route::get('/compView/{idC}', 'pcViewController@compView');

								//-- Add/Update Rating--(On Company Profile)
									Route::post('/addRateCmp', 'pcViewController@addRateCmp');

								//-- Add Review Company
									Route::post('/addReviewCmp', 'pcViewController@addReviewCmp');

								//-- Bar Rating Ajax--(Company)
									Route::post('/getBarCmpV', 'pcViewController@getBarCmpV');

								//-- Edit Review Company
									Route::post('/editReCompy', 'pcViewController@editReCompy');

								//-- Delete Review Company
									Route::post('/delReCompy', 'pcViewController@delReCompy');

								//-- Add Company To Favaurite--(On Pilot Profile)
									Route::post('/addCmptoFv', 'pcViewController@addCmptoFv');

								//-- Remove Company From Favaurite--(On Pilot Profile)
									Route::post('/rmvCmpfrmFv', 'pcViewController@rmvCmpfrmFv');

							#-- Companies View-(End)
							#
							#
					//-- Added Companies And Pilots Module-(End)
					#
					#
					#
					//-- Login To Add Review

						//-- offer
							Route::get('/offReLogin/{id}', 'viewOfferController@offReLogin');

						//-- Pilot Profile
							Route::get('/viewPilLogin/{id}', 'pcViewController@viewPilLogin');

						//-- Company Profile
							Route::get('/viewCmpLogin/{id}', 'pcViewController@viewCmpLogin');
						
						//-- offer Interest
							Route::get('/offInterestLogin/{id}', 'viewOfferController@offInterestLogin');
							
					//-- Login To Add Review
					#
					#
					//-- View more before login (view offer)
							Route::get('/viewMoreOffer/{ofrId}', 'viewOfferController@viewMoreOffer');
					//-- View more before login (view offer)
					#
					#
				});
		#### FRONTEND ROUTS WITH LOGIN VALIDATION END
		###
		###
		###
		###
######---------------------------------========= FRONTEND ROUTES END ==========-----------------------------------------//
######
######
######
######
######
######
######
######---------------------------------========= ADMIN ROUTES START ==========-----------------------------------------//
		###
		###
		###
		###
		#### WITHOUT ADMIN LOGIN VALIDATION ROUTES (ADMIN PANEL)
				Route::group(['middleware' => ['web'],'prefix' => 'admin', 'namespace' => 'admin'], function () {

					if(!Auth::check()) {
					    Route::get('', 'adminController@login');
					    Route::post('', 'adminController@login_sub');
					}
					
					Route::get('login', 'adminController@login');
					Route::post('login_sub', 'adminController@login_sub');

				});
		#### WITHOUT ADMIN LOGIN VALIDATION ROUTES (ADMIN PANEL)
		###
		###
		###
		###
		#### WITH ADMIN LOGIN VALIDATION ROUTES START (ADMIN PANEL)
				
				Route::group(['middleware' => ['web','admin'],'prefix' => 'admin', 'namespace' => 'admin'], function () { 
					#
					#
					//-- Dashboard Panel View After Admin Login
						Route::get('index', 'adminController@index');
					#
					#
					//-- Dashboard Panel View After Admin Login
						Route::get('', 'adminController@index');
					#
					#
					//-- Admin Logout
						Route::get('adminLogout', 'adminController@adminLogout');
					#
					#
					//-- ROUTES FOR NEWS CONTROLLER (ADMIN PANEL --  newsController)

						//-- News Panel View
							Route::get('newsDefault', 'newsController@newsDefault'); 

						//-- News Panel Redirect
							Route::get('news', 'newsController@news'); 

						//-- Add News (View)
							Route::get('addNews', 'newsController@addNews'); 

						//-- Add News (Form Submit)
							Route::post('addNewsSub', 'newsController@addNewsSub'); 

						//-- Delete News (Submit)
							Route::get('delNewsSub/{id}/{img}', 'newsController@delNewsSub');

						//-- Update News (View)
							Route::get('upNews/{id}', 'newsController@upNews');

						//-- Update News (Form Submit)
							Route::post('upNewsSub', 'newsController@upNewsSub');

						//-- View Selected News (Form <pre> view </pre> button)
							Route::get('showNews/{id}', 'newsController@showNews');

					//-- ROUTES FOR NEWS CONTROLLER (ADMIN PANEL -- newsController)
					#
					#
					//-- ROUTES FOR CATEGORY CONTROLLER (ADMIN PANEL -- categoryController)
							#
							#
							//-- Main Categories Routes Start --//

									//-- Category Panel View
										Route::get('categoryDefault', 'categoryController@categoryDefault');

									//-- Category Panel Redirect
										Route::get('category', 'categoryController@category');

									//-- Add Category (View)
										Route::get('addCate', 'categoryController@addCate');

									//-- Add Category (Form Submit)
										Route::post('addCateSub', 'categoryController@addCateSub');

									//-- Update Category (View)
										Route::get('upCate/{id}', 'categoryController@upCate');

									//-- Delete Category (Submit)
										Route::get('delCate/{id}/{img}', 'categoryController@delCate');
										
									//-- Update Category (Form Submit)
										Route::post('upCateSub', 'categoryController@upCateSub');

							//-- Main Categories Routes End --//
							#
							#
							//-- Sub Categories Routes Start --//

									//-- Subcategories (View)
										Route::get('listSub/{id}', 'categoryController@listSub');

									//--Add Subcategories (View)
										Route::get('addSubC/{id}', 'categoryController@addSubC');

									//-- Add Sub Category (Form Submit)
										Route::post('addSubCateSub', 'categoryController@addSubCateSub');

									//-- Update Sub Category (View)
										Route::get('upSubC/{id}/{idMain}', 'categoryController@upSubC');

									//-- Update Sub Category (Form Submit)
										Route::post('upSubCsub', 'categoryController@upSubCsub');

									//-- Delete Sub Category (Submit)
										Route::get('delSubC/{id}/{idMain}', 'categoryController@delSubC');
									
							//-- Sub Categories Routes End --//
							#
							#
					//-- ROUTES FOR CATEGORY CONTROLLER (ADMIN PANEL -- categoryController)
					#
					#
					//-- ROUTES FOR REGISTERED USERS CONTROLLER (ADMIN PANEL -- usersController)

								//-- Redirect to Users Panel On Admin(Default) --//
									Route::get('usersDefault', 'usersController@usersDefault');
								
								//-- View Users Panel On Admin(Default Case) --//
									Route::get('user', 'usersController@users');

								//-- Disable Active User From Admin Panel --//
									Route::get('disableUser/{id}/{role}', 'usersController@disableUser');

								//-- Enable Inactive User From Admin Panel --//
									Route::get('enableUser/{id}/{role}', 'usersController@enableUser');

								//-- View Selected User In Admin Panel --//
									Route::get('viewUser/{id}/{role}', 'usersController@viewUser');

					//-- ROUTES FOR REGISTERED USERS CONTROLLER (ADMIN PANEL -- usersController)
					#
					#
					//-- ROUTES FOR CMS CONTROLLER START (ADMIN PANEL -- cmsController)
								#
								#
								#### ROUTES FOR MAIN CMS CONTROLLER START (ADMIN PANEL -- cmsController)	

											//-- Redirect to CMS Panel On Admin(Default) --//
												Route::get('cmsDefault', 'cmsController@cmsDefault');

											//-- View CMS Panel On Admin(Default Case) --//
												Route::get('cms', 'cmsController@cms');

											//-- Disable Active CMS Page From Admin Panel --//
												Route::get('cmsDis/{id}', 'cmsController@cmsDis');

											//-- Enable Active CMS Page From Admin Panel --//
												Route::get('cmsEna/{id}', 'cmsController@cmsEna');

											//-- Delete CMS Page From Admin Panel --//
												Route::get('cmsDel/{id}/{img}', 'cmsController@cmsDel');

											//-- Add CMS Page From Admin Panel --//
												Route::post('addCmsSub', 'cmsController@addCmsSub');

											//-- Edit CMS Page From Admin Panel --//
												Route::get('cmsEdit/{id}', 'cmsController@cmsEdit');

											//-- Update CMS Page From Admin Panel --//
												Route::post('upCmsSub', 'cmsController@upCmsSub');

								#### ROUTES FOR MAIN CMS CONTROLLER END (ADMIN PANEL -- cmsController)
								#
								#
								#### ROUTES FOR INNER CMS CONTROLLER START (ADMIN PANEL -- cmsController)

											//-- View Inner CMS Panel On Admin(Default Case) --//
												Route::get('cmsInnerList/{id}', 'cmsController@cmsInnerList');

											//-- Add Inner CMS Page On Admin(Show Form Case) --//
												Route::get('innerCmsAdd/{id}', 'cmsController@innerCmsAdd');

											//-- Add Inner CMS Page On Admin(Submit Form Case) --//
												Route::post('addInnerCmsSub', 'cmsController@addInnerCmsSub');

											//-- Enable Active Inner CMS Page From Admin Panel --//
												Route::get('innerCmsEna/{id}/{idMain}', 'cmsController@innerCmsEna');

											//-- Disable Active Inner CMS Page From Admin Panel --//
												Route::get('innerCmsDis/{id}/{idMain}', 'cmsController@innerCmsDis');

											//-- Delete Inner CMS Page From Admin Panel --//
												Route::get('innerCmsDel/{id}/{img}/{idMain}', 'cmsController@innerCmsDel');

											//-- Edit CMS Page From Admin Panel --//
												Route::get('innerCmsEdit/{id}/{idMain}', 'cmsController@innerCmsEdit');

											//-- Update Inner CMS Page From Admin Panel --//
												Route::post('InnerCmsEditSub', 'cmsController@InnerCmsEditSub');

								#### ROUTES FOR INNER CMS CONTROLLER END (ADMIN PANEL -- cmsController)
								#
								#
					//-- ROUTES FOR CMS CONTROLLER END (ADMIN PANEL -- cmsController)
					#
					#
					//-- ROUTES FOR OFFERS MANAGEMENT--(START)

						//-- View List Of Added Offers On Admin --//
							Route::get('offManageView', 'offManageController@offManageView');

						//-- Enable Added Offers From Admin --//
							Route::get('enblOfferAdmin/{id}/{role}/{idUser}', 'offManageController@enblOfferAdmin');

						//-- Disable Added Offers From Admin --//
							Route::get('disOfferAdmin/{id}/{role}/{idUser}', 'offManageController@disOfferAdmin');

						//-- View Added Offers From Admin --//
							Route::get('viewOfferAdmin/{id}/{role}/{idUser}', 'offManageController@viewOfferAdmin');						
					//-- ROUTES FOR OFFERS MANAGEMENT--(END)
					#
					#			
				});
		### WITH ADMIN LOGIN VALIDATION ROUTES END (ADMIN PANEL)
		###
		###
		###
		###
//--------------------------------------========= ADMIN ROUTES END ==========------------------------------------------//


