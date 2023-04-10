<div class="chatingUser animate__fadeInRight animate__animated">
    <i class="fa fa-play-circle rightheansideclose"></i> 
    <input ng-model="searchText" type="text" name="" placeholder="Type here..." class="serchuser">
    <ul class="chatwindows" >
        <li ng-repeat="i in chatuser | orderBy:'notify' | filter:searchText" ng-class="i.notify==1?'beep active':'inactive'" data-IDS='<% i.id %>' data-chating="<% i.chatWith %>-<% i.id %>">
            <img class="mr-2 rounded-circle w2"  ng-src="<% i.avatar == null && '<?php echo url('/assets/img/avatar/avatar-1.png'); ?>' || i.avatar %>">
            <% i.name %> (<% i.type %>) <span ng-if="i.numberofmsg>0" class="notificationsmsgshow"><% i.numberofmsg %></span>
        </li>
    </ul>
         
</div>