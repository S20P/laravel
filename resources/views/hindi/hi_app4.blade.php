@extends('layouts.app')
@section('content')
<div id="app">
  <div class="content-detail-page" id="page-sec">
    <div class="content-wrap box-shadow">
      <div class="item-img">
        <div id="myProgress">
          <div id="myBar"></div>
        </div>
        <div class="text-animation-loder">
          <a href="" class="typewrite" data-period="2000" data-type='[ "आपके परिणाम की गणना कर रहे हैं.."]'>
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
<div class="content-detail-page"  id="loader-sec-in">
  <div class="content-wrap box-shadow">
    <div class="item-img">
    <img class="img img-responsive" width="100%"  height="auto" src="{{ asset('images/hindi/app4/home_image_app4.png') }}" alt="">
    </div>
    <div class="item-img-content">
      <div class="padding-2rem">
       <p class="title">लोग आपके चेहरे में सबसे पहले क्या देखते हैं?</p>
        <p class="subtext">
          खेले और पता लगाये कि आपके चेहरे में लोग सबसे पहले क्या देखते हैं?
        </p>
        <!-- <p class="subtext">
        2018 में आप कौनसी कार खरीदेंगे?</p> -->
        <div class="btns-quiz">

          @if (Auth::check())

          <button type="button"  class="btn-start wb-btn wb-btn-lg wb-btn-fb btn-shadow disabled" onClick="hi_app4_createimg()">
         <p class="text-btn-start"><span></span>Continue as {{ Auth::user()->name }}</p>
          </button>
           @else
           <p class="text-click">
             <i></i>अपना परिणाम देखने के लिए कृपया Facebook से लॉगिन करें
           </p>
         <a href="{{url('redirect')}}" class="cwf-btn"><button type="button" class="btn-start wb-btn wb-btn-lg wb-btn-fb btn-shadow">
          <p class="text-btn-start"><span></span>Facebook से लॉग इन करें</p>
           </button>
         </a>
           @endif
        </div>
      </div>
    </div>
  </div>
</div>

</div>

            @section('sidebar')
            @parent

            @endsection

@endsection
