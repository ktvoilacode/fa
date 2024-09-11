 <html>

 <head>
     <style>
         /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
         @page {
             margin: 0cm 0cm;
         }

         .page-break {
             page-break-after: always;
         }

         /** Define now the real margins of every page in the PDF **/
         body {
             margin-top: 2cm;
             margin-left: 2cm;
             margin-right: 2cm;
             margin-bottom: 2cm;
             font-family: Arial, Helvetica, sans-serif;
             font-size: 18px;
         }


         /** Define the footer rules **/
         footer {
             margin-top: 30px;
             font-family: sans-serif;
             font-size: 18px;
             line-height: 1.5cm;
         }

         hr {
             height: 0.5px;
             color: #eee;
             background: #eee;
         }

         .response {
             font-family: Arial, Helvetica, sans-serif;
             font-size: 18px;
             line-height: 60px;
         }

         .question {
             font-family: Arial, Helvetica, sans-serif;
             font-size: 18px;
             line-height: 30px;
         }

         .w-100 {
             width: 100%;
         }

         .w-50 {
             width: 50%;
         }

         .w-25 {
             width: 25%;
         }

         .head {
             padding: 20px;
             background: #f8f8f8;
             margin-bottom: 30px;
             font-family: Arial, Helvetica, sans-serif;
             font-size: 18px;
             line-height: 25px;
             border: 1px solid #eee;
         }

         .bord {
             border: 1px solid #eee;
             padding: 20px;
         }
     </style>
 </head>

 <body>
     <main>
         <div class="head">
             <div style="padding-bottom: 10px"><span class="">Name: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> @if($obj->session) {{$obj->session->name}} @else {{$obj->user->name }} @endif</div>
             <div><span class="">Test date: &nbsp;&nbsp;&nbsp;&nbsp;</span>{{ date("F j, Y, g:i a",strtotime($obj->created_at))}}</div>
         </div>
         @if(strlen(strip_tags($obj->test->description))>0)
         <div class="question">
             <h4>Question</h4>
             {!! preg_replace("/<p[^>]*><\\ /p[^>]*>/", '', $obj->test->description) !!}
         </div>
         <hr>
         @endif
         <div class="">{!! nl2br($obj->response) !!}</div>
     </main>
     <div class="page-break"></div>
     <footer>
         <div class="head">
             <br>
             <img src="{{ asset('/images/logo.png')}}" width="200px">
             <p>We have been the most awarded training centre offering the best coaching for exams like the GRE, PTE, OET, and IELTS for almost two decades. With University of Cambridge certified trainers, you can be assured of the highest levels of training. </p>
             <hr>
             <p>Are you aiming for a high score? <br>Access our industry leading practice material at <br><a href="https://prep.firstacademy.in">prep.firstacademy.in</a></p>
         </div>
     </footer>

 </body>

 </html>