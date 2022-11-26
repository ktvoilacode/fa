<hr class="my-2 mb-4">
 <div class=" mb-3">
  <div class="row">
    <div class="col-6 col-md-2">

              <img src="{{ asset('images/user.png')}}" class="w-75 mb-4">

    </div>
    <div class="col-12 col-md-6 mb-3 mb-md-0">
      <div class="h4">{{$attempt->user->name}}</div>
      <div class="">Email: <span class="text-primary">{{$attempt->user->email}}</span></div>
      <div class="">Phone: <span class="text-primary">{{$attempt->user->phone}}</span></div>
    </div>
    <div class="col-12 col-md-4">
      <div class="bg-light border px-3 p-2 my- 2rounded">
       <b>Test Type :</b> 
        @if(isset($settings['testtype']))<span class="text-primary">{{ $settings['testtype']}}</span> @endif
      </div>
      <div class="bg-light border px-3 p-2 my-2 rounded">
       <b>Test Date :</b> <span class="text-primary">{{\carbon\carbon::parse($attempt->created_at)->toDayDateTimeString()}}</span>
      </div>

    </div>
  </div>
 

 </div>

 <div class="card ">
        <div class="card-body" style="background: #e0ede5;border:1px solid #a2ddb8;">

          <div class="row text-center">
              <div class="col-12 col-md-9 mb-3 mb-md-0">
                <div class="row">
                  <div class="col-6 col-md-3">
                <div class="rounded p-4 mb-3 mb-md-0" style="border:1px solid #84c39b;">
                  <h5> <b>Listening</b><br><small style="color:#40a264">Band Score</small></h5>
                  <div class="display-4">{{$attempt->t1_score}}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="rounded p-4" style="border:1px solid #84c39b;">
                  <h5> <b>Reading</b><br><small style="color:#40a264">Band Score</small></h5>
                  <div class="display-4">{{$attempt->t2_score}}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="rounded p-4" style="border:1px solid #84c39b;">
                  <h5> <b>Speaking</b> <br><small style="color:#40a264">Band Score</small></h5>
                  <div class="display-4">{{$attempt->t3_score}}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="rounded p-4" style="border:1px solid #84c39b;">
                  <h5> <b>Writing</b><br><small style="color:#40a264">Band Score</small></h5>
                  <div class="display-4">{{$attempt->t4_score}}</div>
                </div>
              </div>

                </div>
              </div>
              <div class=" col-12 col-md-3">
                 <div class=" rounded p-4" style="background: #b2e6c5;border:1px solid #a2ddb8;">
                  <h5> <b>Overall</b><br><small style="color:#40a264">Band Score</small></h5>
                  <div class="display-4">{{ round(($attempt->t1_score+$attempt->t2_score+$attempt->t3_score+$attempt->t4_score)/4,1)}} </div>
                </div>
              </div>
              
              <div class="col-6 col-md-2">
               
              </div>
          </div>

        </div>
      </div>


      <div class=" mt-4">
        <h4>Your Scores Explained</h4>
        <div class="bg-light border p-3 rounded">
           <div class="row">
              <div class="col-12  col-md-2 text-center">
                <h5>Band</h5>
                <div class="display-4 mb-3">8</div>
              </div>
              <div class="col-12  col-md-5">
                  <p>You are a very good user of english</p>
                  <p>Has full operational command of the languag except in few unfamiliar situations</p>
                  <p> Occasional unsystematic inaccuracies. Complex and detailed araumentation is handled well</p>
              </div>
           </div>
        </div>
      </div>