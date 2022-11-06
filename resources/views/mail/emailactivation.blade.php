@component('mail::message')
Hi {{$user['name']}},<br>

<h3>Welcome aboard.</h3>
@if(subdomain()=='prep')
<p>We’re excited you're joining us! First Academy is the Platinum Partner of British Council. We are the most awarded training institute in South India. We have the most awesome classes on this side of the solar system.
</p>
@endif
To complete your sign up, please verify your email:

@component('mail::button', ['url' =>  route('email.verify',$user['activation_token']).'?email='.$user['email']])
Verify Email
@endcomponent

Or visit this link:<br>
<a href="{{ route('email.verify',$user['activation_token']) }}?email={{$user['email']}}">
 {{ route('email.verify',$user['activation_token']) }}?email={{$user['email']}}
</a>

Thanks,<br>
@if(subdomain()=='prep')
First Academy
@else
{{client('name')}}
@endif
@endcomponent
