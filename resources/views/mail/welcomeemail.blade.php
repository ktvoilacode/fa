@component('mail::message')
Hi {{$user['name']}},<br>

<h3>Welcome aboard. We’re thrilled to see you here!</h3>
@if(subdomain()=='prep')
<p>We’re excited you're joining us! First Academy is the Platinum Partner of British Council. We are the most awarded training institute in South India. We have the most awesome classes on this side of the solar system.
</p>
@elseif(client('email_welcome_message'))
{{client('email_welcome_message')}}
@else
<p>We’re confident that services will help you in your career growth.</p>
@endif

@component('mail::button', ['url' => url('/') ])
Get started now !
@endcomponent


Thanks,<br>
@if(subdomain()=='prep')
First Academy
@else
client('name')
@endif
@endcomponent
