<?php
$app_description =  "Looks like you're a popular one, <b>".$posts->first_name."</b> Everyone seems to have an agenda for you! All you have to do now is find out who's who.";
?>
<div class="content-detail-page" id="page-sec">
  <div class="content-wrap box-shadow">
    <div class="item-img">
      <div id="myProgress">
        <div id="myBar"></div>
      </div>
      <div class="text-animation-loder">
        <a href="" class="typewrite" data-period="2000" data-type='[ "Someone wants to kiss you", "Everyday!" ]'>
          <span class="wrap"></span>
        </a>
      </div>
    </div>
    <div class="item-img-content">
      <div class="padding-2rem">
        <div class="btns-quiz">
          <div class="app-box-info gif-sec">
             <div class="show-gif"></div>
          </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="app-box-panel" id="loader-sec-in">
  <div class="content-wrap box-shadow">

      <div class="share-btn-header">
          <div class="item-img-content">
        <div class="app-box-info">
         <h2 class="title">{{$app_title}}</h2>
        </div>

      <button type="button" class="btn-start wb-btn wb-btn-lg wb-btn-fb btn-shadow" onclick="myFacebookLogin()">
           <p class="text-btn-start"><span></span>Share on Facebook</p>
      </button>
        <div class="border-img">
              <img class="img img-responsive reimg" width="100%"  height="auto" id="app70_img" src="{{asset($img_url)}}" alt="">
         </div>
       </div>
        </div>
          <div class="item-img-content">
         <div class="app-box-info">
          <p class="subtext"><?php echo $app_description;?></p>
          <p class="subtext">Share this result with your family and friends!</p>
           </div>

         <div class="box-button">
            <button type="button" class="btn-start wb-btn wb-btn-lg wb-btn-fb btn-shadow" onclick="myFacebookLogin()">
           <p class="text-btn-start"><span></span>Share on Facebook</p>
           </button>
           <button class="try-again-btn disabled" onclick="en_app70_createimg()"><span></span>Try Again</button>
         </div>
       </div>
  </div>
</div>

  @include('js.facebook-on-share')
