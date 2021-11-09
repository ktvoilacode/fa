
@component('mail::message')
# Hi {{$form['name']}}! <br>

Greetings from First Academy!

You have a message from our team.<br>

@component('mail::panel')
{{$form['comment']}} 
@endcomponent

Thanks,<br>
First Academy
@endcomponent
