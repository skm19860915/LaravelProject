<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/custom.css?v=1663783570">  
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link rel="stylesheet" href="/assets/css/CustomLoad.css">
<div id="app" class="CHatBoxS">
    <section class="section CHatBox">



        <div class="custom_loader">
            <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
        </div>
        <chat-component :auth-user="{{ auth()->user() }}" :other-user="{{ $otherUser }}"></chat-component>



    </section> 

</div>
<style>body{ overflow: hidden;}</style>
<script src="{{ asset('assets/js/jquery.min.js')}}?v=1"></script>
<script src="https://tila.in2.app.stoute.co/js/app.js?5ee2114a8f169"></script>
<script src="https://media.twiliocdn.com/sdk/js/chat/v3.3/twilio-chat.min.js"></script>