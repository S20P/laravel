<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\SocialFacebookAccount;
use Image;
use Route;
use DB;
use Redirect;
use Session;
use File;
use View;
use URL;
class ShareController extends Controller
{
  public function __construct()
 {
  $this->middleware('preventBackHistory')->except('logout');
 }





  public function welcome(){
          return view('welcome');
  }

  public function redirect()
    {

      Session::put('url.intended',URL::previous());
       $permission = [
       'user_birthday',
       'user_likes',
       'user_friends',
       'public_profile',
       'email',
       'user_photos',
       'user_relationships',
     ];

       return Socialite::driver('facebook')->scopes($permission)->with(['auth_type' => 'rerequest'])->redirect();
    }

    public function callback(Request $request)
       {
                //  $providerUser =  Socialite::driver('facebook')->stateless()->user();


       $providerUser = Socialite::driver('facebook')->fields([
                 'name','first_name', 'last_name', 'email','gender','locale','link','birthday','age_range','verified','work',
              ])->user();



    //  dd($providerUser->user['work'][0]['position']['name']); //Get Profession
      $token =  $providerUser->token;



      Session::put('token', $token);
      $Fb_Uid = $providerUser->id;
      Session::put('fb_uid', $Fb_Uid);







        //Get fb Likes //






 // 'image' => 'https://graph.facebook.com/me/picture?width=720&height=720&access_token='.$token->access_token;

        //return Response()->json($frendlist->user);

          //$frends = $providerUser->user;


        //  return Response()->json($frends['friendlists']['data']);
           // $frendsdata = $frends['friendlists']['data'];
           // $pagein = $frends['friendlists']['paging'];

          // return Response()->json($pagein);

           //echo "<a href=".$pagein['next'].">next Frends</a>";



          // return view('home',array('likes'=>$userlike,'like_next'=>$like_next));

        //   return view('home',array('data' => $frendsdata,'pagein'=>$pagein));

         //return Response()->json($providerUser->paging);

            // $token =  $providerUser->token;
             //$this->$token = "World";
      //  return $providerUser->getAvatar();
      //return  $providerUser->getId();

     //https://graph.facebook.com/'.$fid.'/picture?type=large

       $account = SocialFacebookAccount::whereProvider('facebook')
           ->whereProviderUserId($providerUser->getId())
           ->first();


           if ($account) {
                    $user = $account->user;
                    $result  = Auth::login($user, true);

                   $posts  = User::find(Auth::user()->id);

                    //return view('home',array('posts' => $posts,'student' => $student));
                //    return view ( '/home',array('posts' => $posts));
                if ($request->is('/') or $request->is('home')) {
                    return redirect()->route("home");
                }
                else{
              //  return redirect()->back();
                // return redirect()->intended();
                  return Redirect::to(Session::get('url.intended'));
                //  return redirect()->route("home");
                }
                //  return redirect()->route("home");

                 } else {

           $account = new SocialFacebookAccount([
               'provider_user_id' => $Fb_Uid,
               'provider' => 'facebook'
           ]);

           $user = User::where('Fb_uid', $providerUser->getId())->first();

           if (!$user) {

              $email = $providerUser->getEmail();

              $fid = $providerUser->getId();
            //  $picture = "https://graph.facebook.com/$fid/picture?type=large";

            $picture = 'https://graph.facebook.com/'.$fid.'/picture?width=720&height=720';
               $user = User::create([
                   'Fb_uid' => $fid,
                   'email' =>$email,
                   'first_name'=> $providerUser['first_name'],
                   'name' => $providerUser->getName(),
                   'link'=>$providerUser['link'],
                   'Gender'=>$providerUser['gender'],
                  // 'birthdate'=>$providerUser['birthday'],
                   'age'=>$providerUser['age_range']['min'],
                   'locale'=>$providerUser['locale'],
                   'picture'=>$picture,
                   'password' => md5(rand(1,10000)),
               ]);

           }
           $account->user()->associate($user);
           $account->save();
           Auth::login($user, true);
           $posts  = User::find(Auth::user()->id);

      //     return view('home',array('posts' => $posts,'student' => $student));
          //  return redirect()->back();
          if ($request->is('/') or $request->is('home')) {
              return redirect()->route("home");
          }
          else{
            //   return redirect()->back();
            //   return redirect()->intended();
                 return Redirect::to(Session::get('url.intended'));
            // return redirect($request->session()->get('url.intended'));
          //  return redirect()->route("home");
          }


          // return view ( '/home',array('posts' => $posts));
     }
   }
         public function app1()
               {
                 return  view('app1');
               }
         public function app2(){
             return  view('app2');
         }
         public function app3(){

            return  view('app3');
        }
        public function app4(){

           return  view('app4');
       }
       public function app5(){

          return  view('app5');
      }
    // app1  img  createing proccess

         public function app1_createimg(){

           if (Auth::check()){

           // paste another image
             $posts  = User::find(Auth::user()->id);

             $txtarry = array('Your Beautiful Smile',' Your Strong Intuition','Your Speaking Eyes',' Your Attractive Smile','Your Beautiful Voice','Your Seanse Of Humor','Your Golden Heart');

             $i = rand(0, count($txtarry)-1); // generate random number size of the array
             $overtxt = $txtarry[$i]; // set variable equal to which random filename was chosen
             $markimg = $posts->picture;
             $img = Image::make('images/app1.png');
           //$img->insert('images/6.jpg');
           // create a new Image instance for inserting
           $watermark = Image::make($markimg);
           $watermark->resize(280, 360);
           //$img->insert($watermark, 'center');
           // insert watermark at bottom-right corner with 10px offset
           $img->insert($watermark, 'top-left', 0,0);       //top-left (default)
         //  $img->text('The quick brown fox jumps over the lazy dog.', 120, 100);
           $img->text($overtxt, 299, 150, function($font) {
               $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
               $font->size(40);
               $font->color('#00f');
               $font->align('left');   //left, right and center
               $font->valign('top');  //top, bottom , middle
               $font->angle(0);       //0,45,90,180
           });
         //example at position

         //  $img->insert($watermark, 'top', 10,10);            //top
         //  $img->insert($watermark, 'top-right', 10,10);      //top-right
         //  $img->insert($watermark, 'left', 10,10);           //left
         //  $img->insert($watermark, 'center', 10,10);         //center
         //  $img->insert($watermark, 'right', 10,10);          //right
         //  $img->insert($watermark, 'bottom-left', 10,10);    //bottom-left
         //  $img->insert($watermark, 'bottom', 10,10);         //bottom
         //   $img->insert($watermark, 'bottom-right', 10,10);   //bottom-right

         // $img->line(10, 10, 195, 195, function ($draw) {
         //         $draw->color('#f00');
         //         $draw->width(5);
         //  });
           $img->resize(600, 315);
           $ldate = date('d-m-Y');
           $t=time();
           $fb_id = $posts['Fb_uid'];
           $image_dirctory_path = 'uploads/'.$ldate;
           File::isDirectory($image_dirctory_path) or File::makeDirectory($image_dirctory_path, 0777, true, true);
           $image_name = 'app1_'.$ldate.'_'.$t.'_'.$fb_id.'.jpg';
           $fullimage_path = $image_dirctory_path.'/'.$image_name;
           $img->save($fullimage_path);
          // $img->save('images/fb1.jpg');

            //save to images
            //  $img->save('images/dd.jpg', 60); //save to set image quality
          //return  $img->response('jpg');
          //return view('/share',array('posts' => $posts,'img'=>$img));
          $html = view('share_app1',array('posts' => $posts,'img_url'=>$fullimage_path), compact('view'))->render();
          return response()->json(compact('html'));
           }
          else{
                return Redirect::route('redirect');
               }

           }


    // app2  img  createing proccess

         public function app2_createimg(){
            $posts  = User::find(Auth::user()->id);
            $markimg = $posts->picture;
            $img = Image::make('images/app2.png');

            $watermark = Image::make($markimg);
            $watermark->resize(250, 240);

            $img->insert($watermark, 'top-left', 10,56);
            $arr_at1 = array( 'Crossandra',
                              'Velvetleaf', 'Indian','Mallow',
                              'Hoya', 'Plants',
                              'Ixora','Flowers',
                              'Calotropis', 'Gigantea','Crown','Flower',
                              'Yesterday','Today', 'Brunfelsia', 'Latifolia',
                              'Thailan', 'Parrot', 'Flower',
                              'Caper', 'Plant', 'Flower',
                              'Balsam', 'Apple', 'Flowers',
                              'Bleeding', 'Heart',
                              'Firecracke', 'Plant',
                              'Aigrette',
                              'Solandra', 'Maxima',
                              'Voodoo', 'Lily',
                              'Lion');
            $i = rand(7, count($arr_at1)-1); // generate random number size of the array
            $at1 = $arr_at1[$i]; // set variable equal to which random filename was chosen
           $img->text($at1, 350, 95, function($font) {
              $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
              $font->size(40);
              $font->color('#00f');
              $font->align('left');   //left, right and center
              $font->valign('top');  //top, bottom , middle
              $font->angle(0);       //0,45,90,180
           });


           $arr_at2 = array('Purple', 'Heart',
                        'Parrot', 'Lily',
                        'Allamanda', 'Cathartica',
                        'African' ,'Tulip',
                        'Brooksville' ,'bellflower',
                        'Twin' ,'Flowered', 'Agave',
                        'Flower' ,'Power',
                        'Luscious', 'Lotus',
                        'Orchid' ,'in', 'the', 'Blue',
                        'Purple', 'Foxglov',
                        'Passion', 'Flower',
                        'Black', 'Bat' ,'Flower' ,'Tacca ','chantrieri',
                        'Snowdonia' ,'Hawkweed',
                        'Lithops',
                        'Lunaria', 'annua');
           $i = rand(0, count($arr_at2)-1); // generate random number size of the array
           $at2 = $arr_at2[$i]; // set variable equal to which random filename was chosen

           $img->text($at2, 350, 136, function($font) {
              $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
              $font->size(40);
              $font->color('#00f');
              $font->align('left');   //left, right and center
              $font->valign('top');  //top, bottom , middle
              $font->angle(0);       //0,45,90,180
           });
           $arr_at3 = array(
             'Abbott',
              'Acevedo',
              'Acosta',
              'Adams',
              'Adkins',
              'Aguilar',
              'Aguirre',
              'Albert',
              'Alexander',
              'Alford',
              'Allen',
              'Allison',
              'Alston',
              'Alvarado',
              'Alvarez',
              'Anderson',
              'Andrews',
              'Anthony',
              'Armstrong',
              'Arnold',
              'Ashley',
              'Atkins',
              'Atkinson',
              'Austin',
              'Avery',
              'Avila',
              'Ayala',
              'Ayers',
              'Bailey',
           );
           $i = rand(3, count($arr_at3)-1); // generate random number size of the array
           $at3 = $arr_at3[$i]; // set variable equal to which random filename was chosen

           $img->text($at3, 350, 178, function($font) {
              $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
              $font->size(40);
              $font->color('#00f');
              $font->align('left');   //left, right and center
              $font->valign('top');  //top, bottom , middle
              $font->angle(0);       //0,45,90,180
           });

            $img->resize(600, 315);
          //  $img->save('images/fb2.jpg');

          $ldate = date('d-m-Y');
          $t=time();
          $fb_id = $posts['Fb_uid'];
          $image_dirctory_path = 'uploads/'.$ldate;
          File::isDirectory($image_dirctory_path) or File::makeDirectory($image_dirctory_path, 0777, true, true);
          $image_name = 'app2_'.$ldate.'_'.$t.'_'.$fb_id.'.jpg';
          $fullimage_path = $image_dirctory_path.'/'.$image_name;
          $img->save($fullimage_path);


            $html = view('share_app2',array('posts' => $posts,'img_url'=>$fullimage_path), compact('view'))->render();
            return response()->json(compact('html'));
         }


            // app3  img  createing proccess

            public function app3_createimg(request $request){


return response()->json("eeeee");
              $filedata = $request->app_img_url;

              $path = $filedata;
              $type = pathinfo($path, PATHINFO_EXTENSION);
              $data = file_get_contents($path);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
              //return $base64;

              //$filedata = Image::make($filedata);
            //  $watermark = Image::make($filedata);
            //  $watermark->save('images/test/satish.jpg');
            //  return;
                $base64_img = Image::make(base64_decode(explode(',',$base64)[1]));
              // file_put_contents('images/img.jpg', base64_decode(explode(',',$base64string)[1]));

                $watermark = Image::make($base64_img);
                $watermark->resize(208, 208);

               $img = Image::make('images/app3.png');
               $img->insert($watermark, 'top-left', 21,62);
               $app3_at1 = array( 'Crossandra',
                                 'Velvetleaf', 'Indian','Mallow',
                                 'Hoya', 'Plants',
                                 'Ixora','Flowers',
                                 'Calotropis', 'Gigantea','Crown','Flower',
                                 'Yesterday','Today', 'Brunfelsia', 'Latifolia',
                                 'Thailan', 'Parrot', 'Flower',
                                 'Caper', 'Plant', 'Flower',
                                 'Balsam', 'Apple', 'Flowers',
                                 'Bleeding', 'Heart',
                                 'Firecracke', 'Plant',
                                 'Aigrette',
                                 'Solandra', 'Maxima',
                                 'Voodoo', 'Lily',
                                 'Lion');
               $i = rand(7, count($app3_at1)-1); // generate random number size of the array
               $at1 = $app3_at1[$i]; // set variable equal to which random filename was chosen
              $img->text($at1, 280, 100, function($font) {
                 $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
                 $font->size(20);
                 $font->color('#00f');
                 $font->align('left');   //left, right and center
                 $font->valign('top');  //top, bottom , middle
                 $font->angle(0);       //0,45,90,180
              });


              $app3_at2 = array('Purple', 'Heart',
                           'Parrot', 'Lily',
                           'Allamanda', 'Cathartica',
                           'African' ,'Tulip',
                           'Brooksville' ,'bellflower',
                           'Twin' ,'Flowered', 'Agave',
                           'Flower' ,'Power',
                           'Luscious', 'Lotus',
                           'Orchid' ,'in', 'the', 'Blue',
                           'Purple', 'Foxglov',
                           'Passion', 'Flower',
                           'Black', 'Bat' ,'Flower' ,'Tacca ','chantrieri',
                           'Snowdonia' ,'Hawkweed',
                           'Lithops',
                           'Lunaria', 'annua');
              $i = rand(0, count($app3_at2)-1); // generate random number size of the array
              $at2 = $app3_at2[$i]; // set variable equal to which random filename was chosen

              $img->text($at2, 280, 125, function($font) {
                 $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
                 $font->size(20);
                 $font->color('#00f');
                 $font->align('left');   //left, right and center
                 $font->valign('top');  //top, bottom , middle
                 $font->angle(0);       //0,45,90,180
              });
              $app3_at3 = array(
                'Abbott',
                 'Acevedo',
                 'Acosta',
                 'Adams',
                 'Adkins',
                 'Aguilar',
                 'Aguirre',
                 'Albert',
                 'Alexander',
                 'Alford',
                 'Allen',
                 'Allison',
                 'Alston',
                 'Alvarado',
                 'Alvarez',
                 'Anderson',
                 'Andrews',
                 'Anthony',
                 'Armstrong',
                 'Arnold',
                 'Ashley',
                 'Atkins',
                 'Atkinson',
                 'Austin',
                 'Avery',
                 'Avila',
                 'Ayala',
                 'Ayers',
                 'Bailey',
              );
              $i = rand(3, count($app3_at3)-1); // generate random number size of the array
              $at3 = $app3_at3[$i]; // set variable equal to which random filename was chosen

              $img->text($at3, 280, 150, function($font) {
                 $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
                 $font->size(20);
                 $font->color('#00f');
                 $font->align('left');   //left, right and center
                 $font->valign('top');  //top, bottom , middle
                 $font->angle(0);       //0,45,90,180
              });


              $app3_at4 = array( '$28,002.000',
                                '$78,0001.000', '$78,000.000','$98,000.000',
                                '$87,002.000', '$87,000.000',
                                '$77,003.000','$55,000.000',
                                '$66,004.000', '$54,000.020','$60,000.000','$76,000.000',
                                '$44,005.000','$13,000.000', '$99,000.000', '$87,000.000',
                                '$09,006.000', '$17,002.002', '$72,000.000',
                                '$10,007.000', '$20,000.000', '$54,000.000',
                                '$11,008.000', '$25,040.040', '$32,4000.000',
                                '$21,009.000', '$12,000.000',
                                '$20,000.000', '$204,04400.000',
                                '$204,000.0040',
                                '$25,0400.000', '$16,000.000',
                                '$25,500.000', '$24,4500.000',
                                '$22,000.000');
              $i = rand(8, count($app3_at4)-1); // generate random number size of the array
              $at4 = $app3_at4[$i]; // set variable equal to which random filename was chosen
             $img->text($at4, 280, 230, function($font) {
                $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
                $font->size(25);
                $font->color('#00f');
                $font->align('left');   //left, right and center
                $font->valign('top');  //top, bottom , middle
                $font->angle(0);       //0,45,90,180
             });
               $img->resize(600, 315);
            //   $img->save('images/fb3.jpg');
               $posts  = User::find(Auth::user()->id);
               $ldate = date('d-m-Y');
               $t=time();
               $fb_id = $posts['Fb_uid'];
               $image_dirctory_path = 'uploads/'.$ldate;
               File::isDirectory($image_dirctory_path) or File::makeDirectory($image_dirctory_path, 0777, true, true);
               $image_name = 'app3_'.$ldate.'_'.$t.'_'.$fb_id.'.jpg';
               $fullimage_path = $image_dirctory_path.'/'.$image_name;
               $img->save($fullimage_path);

               $html = view('share_app3',array('posts' => $posts,'img_url'=>$fullimage_path), compact('view'))->render();
              return $html;
              //return response()->json(compact('html'));
             //return response()->json($html);
            }

     //Example for Base64 to Img
      // public function getAjax(Request $request)
      //   {
      //     $filedata = $request->app_img_url;
      //     $img = Image::make(base64_decode(explode(',',$filedata)[1]));
      //     // file_put_contents('images/img.jpg', base64_decode(explode(',',$base64string)[1]));
      //     $img->resize(100,100);
      //     $img->save('images/test/satish.jpg');
      //     return response()->json($filedata);
      //   }

  // app4  img  createing proccess
      public function app4_createimg(){
        if (Auth::check()){

        // paste another image
          $posts  = User::find(Auth::user()->id);

          $txtarry = array(' Small Waist and big butt','Bigger butt','Flat tummy','Younger looking and big boobs','big butt and big boobs','Big boobs and flat tummy','Perfect body and big butt','Beefy six pack abs','Bigger chest','Bigger beer belly');

          $i = rand(0, count($txtarry)-1); // generate random number size of the array
          $overtxt = $txtarry[$i]; // set variable equal to which random filename was chosen
          $markimg = $posts->picture;
        //  $img = Image::make('images/app4/fb4.jpg');
          $img = Image::canvas(800, 420, '#ffffff');
        //$img->insert('images/6.jpg');
        // create a new Image instance for inserting
        $watermark = Image::make($markimg);
        $watermark->resize(380, 420);
        //$img->insert($watermark, 'center');
        // insert watermark at bottom-right corner with 10px offset
        $img->insert($watermark, 'top-left', 0,0);       //top-left (default)
      //  $img->text('The quick brown fox jumps over the lazy dog.', 120, 100);

        $watermark2 = Image::make('images/app4/app4_1.png');
        $img->insert($watermark2, 'top-left', 350,60);
        // $img->text($overtxt, 370, 150, function($font) {
        //     $font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
        //     $font->size(60);
        //     $font->color('#FF0000');
        //     $font->align('left');   //left, right and center
        //     $font->valign('middle');  //top, bottom , middle
        //     $font->angle(0);       //0,45,90,180
        // });
        $string = wordwrap($overtxt,10,"|");
        //create array of lines
        $strings = explode("|",$string);
        $i=80; //top position of string
        //for each line added
        foreach($strings as $string){
        $img->text($string, 650, $i, function($font) {
        $font->file('fonts/theboldfont.ttf');
        $font->size(60);
        $font->color('#FF0000');
        $font->align('center');
        $font->valign('middle');
        });
      $i=$i+60; //shift top postition down 42
        }

      //example at position

      //  $img->insert($watermark, 'top', 10,10);            //top
      //  $img->insert($watermark, 'top-right', 10,10);      //top-right
      //  $img->insert($watermark, 'left', 10,10);           //left
      //  $img->insert($watermark, 'center', 10,10);         //center
      //  $img->insert($watermark, 'right', 10,10);          //right
      //  $img->insert($watermark, 'bottom-left', 10,10);    //bottom-left
      //  $img->insert($watermark, 'bottom', 10,10);         //bottom
      //   $img->insert($watermark, 'bottom-right', 10,10);   //bottom-right

      // $img->line(10, 10, 195, 195, function ($draw) {
      //         $draw->color('#f00');
      //         $draw->width(5);
      //  });
        $img->resize(800, 420);
        $ldate = date('d-m-Y');
        $t=time();
        $fb_id = $posts['Fb_uid'];
        $image_dirctory_path = 'uploads/'.$ldate;
        File::isDirectory($image_dirctory_path) or File::makeDirectory($image_dirctory_path, 0777, true, true);
        $image_name = 'app4_'.$ldate.'_'.$t.'_'.$fb_id.'.jpg';
        $fullimage_path = $image_dirctory_path.'/'.$image_name;
        $img->save($fullimage_path);
       // $img->save('images/fb1.jpg');

         //save to images
         //  $img->save('images/dd.jpg', 60); //save to set image quality
       //return  $img->response('jpg');
       //return view('/share',array('posts' => $posts,'img'=>$img));
       $html = view('share_app4',array('posts' => $posts,'img_url'=>$fullimage_path), compact('view'))->render();
       return response()->json(compact('html'));
        }
       else{
             return Redirect::route('redirect');
            }

        }

// app5  img  createing proccess

        public function app5_createimg(){

          if (Auth::check()){

          // paste another image
            $posts  = User::find(Auth::user()->id);


            $dir = "images/app5/transperant_car/";
            $pictures = glob("$dir/{*.jpg,*.jpeg,*.gif,*.png}",GLOB_BRACE);
            $img_path = $pictures[mt_rand(0,count($pictures)-1)];

            $img = Image::make($img_path);
            $ext = pathinfo($img_path);
            $car_name = $ext['filename'];

          $overtxt = $posts->first_name;
          $img->text($overtxt, 140, 348, function($font) {
              $font->file('fonts/theboldfont.ttf');
              $font->size(20);
              $font->color('#000');
              $font->align('center');   //left, right and center
              $font->valign('middle');  //top, bottom , middle
              $font->angle(0);       //0,45,90,180
          });

          $markimg = $posts->picture;
          $watermark = Image::make($markimg);
          $watermark->resize(220, 220, function ($constraint){
              $constraint->aspectRatio();
          });

          $canvas = Image::canvas(800, 420);
          $canvas->insert($watermark, 'top-left', 45, 105);
          $canvas->insert($img);

          //$canvas->save('images/final.png');
          // $markimg = $posts->picture;
          // $watermark = Image::make($markimg);
          // $watermark->resize(195, 201);

        //  $img->insert($watermark, 'top-left',50,107);

          //$img->resize(800, 420);
          $ldate = date('d-m-Y');
          $t=time();
          $fb_id = $posts['Fb_uid'];
          $image_dirctory_path = 'uploads/'.$ldate;
          File::isDirectory($image_dirctory_path) or File::makeDirectory($image_dirctory_path, 0777, true, true);
          $image_name = 'app5_'.$ldate.'_'.$t.'_'.$fb_id.'.png';
          $fullimage_path = $image_dirctory_path.'/'.$image_name;
          $canvas->save($fullimage_path);
         // $img->save('images/fb1.jpg');

           //save to images
           //  $img->save('images/dd.jpg', 60); //save to set image quality
         //return  $img->response('jpg');
         //return view('/share',array('posts' => $posts,'img'=>$img));
         $html = view('share_app5',array('posts' => $posts,'img_url'=>$fullimage_path,'car_name'=>$car_name), compact('view'))->render();
         return response()->json(compact('html'));
          }
         else{
               return Redirect::route('redirect');
              }

          }


        public function fb_like(){

          $token = Session::get('token');
          include('plugin/Facebook/autoload.php');
         //  include(asset('plugin/Facebook/autoload.php'));
         $fb = new \Facebook\Facebook([
          'app_id' => '1361360707308056',
          'app_secret' => '0d2dda56f37756784caf4f83e311bd24',
          'default_graph_version' => 'v2.10',
          'default_access_token' => $token, // optional
         ]);


      $requestFriends = $fb->get('/me/?fields=work');


      dd($requestFriends);








        //  $requestFriends = $fb->get('/me/taggable_friends?fields=name,picture,id,first_name&limit=10');
        //  $friends = $requestFriends->getGraphEdge();

        //   $graphNode = $requestFriends->getGraphList();
        //
        //
        //   $allFriends = $graphNode->asArray();
        //   $frends_details = array();
        // foreach ($allFriends as $key) {
        //   $frends_first_name=$key['first_name'];
        //   $frends_picture_url=$key['picture']['url'];
        //     array_push($frends_details,["frends_first_name"=>$frends_first_name,"frends_picture_url"=>$frends_picture_url]);
        // }
        // $i = rand(0, count($frends_details)-1); // generate random number size of the array
        // $friends = $frends_details[$i]; // set variable equal to which random filename was chosen
        //
        //
        //  echo "<img src=".$friends['frends_picture_url']." width='150' height='150'><br>";
        //  echo $friends['frends_first_name'];
        //
        //
        //   if ($fb->next($friends)) {
        //       $allFriends = array();
        //       $friendsArray = $friends->asArray();
        //       $allFriends = array_merge($friendsArray, $allFriends);
        //       while ($friends = $fb->next($friends)) {
        //         $friendsArray = $friends->asArray();
        //         $allFriends = array_merge($friendsArray, $allFriends);
        //       }
        //         $frends_details = array();
        //       foreach ($allFriends as $key) {
        //         $frends_first_name=$key['first_name'];
        //         $frends_picture_url=$key['picture']['url'];
        //           array_push($frends_details,["frends_first_name"=>$frends_first_name,"frends_picture_url"=>$frends_picture_url]);
        //       }
        //       $i = rand(0, count($frends_details)-1); // generate random number size of the array
        //       $friends = $frends_details[$i]; // set variable equal to which random filename was chosen
        //
        //        print_r($friends['frends_first_name']);
        //        print_r($friends['frends_picture_url']);
        //
        //     }

        //  return Response()->json($token);
         //return view('like',array('token' => $token));

          // $Data = Socialite::with('facebook')->user();
          // return $Data->id;

          //return $token;

          //$url = 'https://graph.facebook.com//me/taggable_friends?fields=name,picture,id,first_name,last_name&access_token='.$token;
        //  $userlike = json_decode(file_get_contents($url));
        //  $likes_data = $userlike->data;
        // return Response()->json($userlike);

      //    return view('like',array('likes'=>$userlike,'like_next'=>$like_next));

          // return Socialite::driver('facebook')
          //  ->scopes(['user_likes', 'user_friends'])->redirect("/home");

          // $providerData = Socialite::driver('facebook')->fields([
           //         'name','first_name', 'last_name', 'email','gender','locale','link','birthday','age_range'
           //        ])->user();
           //return $providerData;
          // return   Socialite::driver('facebook')->fields(['first_name', 'email', 'gender', 'verified', 'friends'])->user();
        }

        public function profile(){
          $token = Session::get('token');
          return view('profile',array('token' => $token,'user'=>Auth::user()));
        }

        public function CreateFolderDirectory(){

        // $fb_uid = Session::get('fb_uid');

            $posts  = User::find(Auth::user()->id);
            $fb_id = $posts['Fb_uid'];
        //    return $fb_id;
  //       $date = date_create();
  //     $f = date_timestamp_get($date);
  // echo(date("Y-m-d:H:m:s",$f));
      //  $t=time();
      //  echo($t . "<br>");
      //  echo(date("Y-m-d:H:m:s",$t));
        return;
            $ldate = date('d-m-Y h:m:s');
              return $ldate;
              //return $ldate;
              $path = 'images/'.$ldate;
              File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
              return;
        }

 public function multilinetext_img(){
   $textToBeShown ="The man, the dwarf and the girlguardian";
$img2 = Image::canvas(300,250);
// inserts character where string is to be split into new line (after 15 characters, keeping words intact)
$string = wordwrap($textToBeShown,15,"|");
//create array of lines
$strings = explode("|",$string);
$i=3; //top position of string
//for each line added
foreach($strings as $string){
$img2->text($string, 150, $i, function($font) {
$font->file('fonts/MotionPicture_PersonalUseOnly.ttf');
$font->size(40);
$font->color('#826c61');
$font->align('center');
$font->valign('top');
});
$i=$i+42; //shift top postition down 42
}
$img2->save('images/multi.jpg');
 }
public function randum(){
$dir = "images/"; // The relative path to the image directory
$pictures = glob("$dir/{*.jpg,*.jpeg,*.gif,*.png}",GLOB_BRACE);
$img = $pictures[mt_rand(0,count($pictures)-1)];


echo '<img src="' . $img . '">';

$ext = pathinfo($img);

echo $ext['dirname'] . '<br/>';   // Returns folder/directory
echo $ext['basename'] . '<br/>';  // Returns file.html
echo $ext['extension'] . '<br/>'; // Returns .html
echo $ext['filename'] . '<br/>';  // Returns file
}




  public function radius(){
    $cover = Image::make('images/app5/main.png');
  //  $cover = Image::make('images/main.png');
    $r1 = Image::make('images/img.jpg');


    $r1->resize(220, 220, function ($constraint){
        $constraint->aspectRatio();
    });

    $canvas = Image::canvas(800, 420);
    $canvas->insert($r1, 'top-left', 45, 105);
    $canvas->insert($cover);

    $canvas->save('images/final.png');
  }


public function compress_and_resize(){


  $dir    = 'images/img-compress/test';

  // $cover = Image::make('images/test/test.jpg');
  //
  // $cover->resize(360, 189, function ($constraint){
  //     $constraint->aspectRatio();
  // });
  //
  // $cover->save('images/test/thumb/final1.jpg');

  $ffs = scandir($dir);

  unset($ffs[array_search('.', $ffs, true)]);
  unset($ffs[array_search('..', $ffs, true)]);

  // prevent empty ordered elements
  if (count($ffs) < 1)
      return;


  foreach($ffs as $ff){


    $string1 = $ff;

            // echo $dir;
            // echo "<br>";
            // echo $ff;

            $url = $dir."/".$ff;

            $cover = Image::make($url);

            $cover->resize(360, 189);

            $savedurl = "images/img-compress/thumb/".$ff;
            $cover->save($savedurl);

      if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);

  }
}

}
