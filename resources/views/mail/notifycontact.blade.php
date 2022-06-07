
@component('mail::message')
# Hi {{$form['name']}}! <br>

Greetings from First Academy!

You have a message from our team.<br>

@component('mail::panel')
{{$form['comment']}} 

@if(isset($form['test_name']))
<hr>
Test Name: <b>{{ $form['test_name'] }}</b><br>
@endif
@endcomponent



Thanks,<br>
First Academy
@endcomponent
