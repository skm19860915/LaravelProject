@extends('layouts.admin-master')

@section('title')
Chat
@endsection
<style type="text/css">
ul.sidebar-menu.chatuser {
    padding: 0;
    list-style: none;
    overflow-y: scroll;
    height: 100%;
    max-height: 420px;
    margin-top: 46px;
}
.serchuser {
    width: calc(100% - 30px);
    padding: 12px 15px;
    border: 1px solid rgba(0,0,0,.125);
    position: absolute;
    top: 0;
    left: 15px;
    z-index: 9;
}
</style>
@section('content')
<section class="section">
	<div class="section-header">
		<h1>Chat</h1>
    </div>

	<div class="section-body">
		
   <div class="row">
      <div class="col-md-3">
        <input type="text" name="" placeholder="Type here..." class="serchuser">
        <ul class="sidebar-menu chatuser">
            @if ($users->isEmpty())
            <p>No users</p>
            @else
            @foreach ($users as $user)
            <li>
                <a href="{{ route('admin.chat.chat', [ 'ids' => auth()->user()->id  . '-' . $user->id ]) }}" class="list-group-item list-group-item-action">
                    {{ $user->name }}<span class="text-gray">
                    <?php if($user->role_id == 4) {
                        echo '(Firm Admin)';
                    }
                    else {
                        echo '(VA User)';
                    }
                    ?></span>
                </a>
            </li>
            @endforeach
            @endif
        </ul>
    </div>
    <div class="col-md-9">
     <div class="card">
        <div class="card-body" style="min-height: 464px;">
            <div class="custom_loader">
                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
            </div>
            <chat-component :auth-user="{{ auth()->user() }}" :other-user="{{ $otherUser }}"></chat-component>
        </div>
    </div>
</div>
</div>
</div>
</section>
@endsection

@push('footer_script')
<script src="https://media.twiliocdn.com/sdk/js/chat/v3.3/twilio-chat.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.serchuser').on('keyup', function(){
        var v = $(this).val();
            v = v.replace(/ /g, '').toLowerCase();
        console.log(v);
        $('.chatuser li').hide();
        $('.chatuser li').each(function(){
            var u = $(this).find('a').text();
                u = u.replace(/ /g, '').toLowerCase();
                var n = u.includes(v);
                
                if(n) {
                    console.log(v+'----------'+u);
                    $(this).show();
                }

        });
    });
});    
</script>
@endpush