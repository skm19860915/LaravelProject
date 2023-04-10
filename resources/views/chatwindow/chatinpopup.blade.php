<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>@yield('title', 'Stisla Laravel') &mdash; {{ env('APP_NAME') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- General CSS Files -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        <!-- CSS Libraries -->

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/components.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/CustomLoad.css')}}?v=<?php echo rand(); ?>">
        @stack('header_styles')
        @stack('header_script')
    </head>

    <body>
        <div id="app" class="newchatwindow">
            <div class="main-wrapper">
                <!-- Main Content -->
                <div class="main-content">
                    <div class="chatwindowssd" >
                        <div class="headerbox"><span></span>

                            <a class="closepopup"> 
                                <i class="fa-times fa"></i> 
                            </a>
                            <a class="minifypopup"> 
                                <i class="fa-minus fa"></i>
                            </a>
                            <a class="maximizepopup">
                                <i class="fa fa-arrows-alt"></i>
                            </a>
                        </div>   
                        <div class="msgshow" data-datavalue="<?php echo $ids; ?>">
                            <div class="chtmsgboxsall"></div>
                            <div class="chtmsgboxmsg">
                                <div class="emojis hide"></div>
                                <img class="showemojis" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjc2Ljg5MXB4IiBoZWlnaHQ9IjI3Ni44OTFweCIgdmlld0JveD0iMCAwIDI3Ni44OTEgMjc2Ljg5MSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjc2Ljg5MSAyNzYuODkxIg0KCSB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxjaXJjbGUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjExIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGN4PSIxMzguNDQ1IiBjeT0iMTM4LjQ0NSIgcj0iMTMyLjk0NSIvPg0KPGNpcmNsZSBjeD0iNjguMTIiIGN5PSIxMjUuMzk1IiByPSIxNi41MDciLz4NCjxjaXJjbGUgY3g9IjIwOC42MTciIGN5PSIxMjUuMzk1IiByPSIxNi41MDgiLz4NCjxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIxMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik02OC4xMiwxODIuMDM0DQoJYzAsMCw2OS43OTMsNzAuNzA0LDE0MC40OTgsMCIvPg0KPC9zdmc+DQo=">
                                <i class="fa-paperclip fa attchfileicons"></i>
                                <span data-emojiarea data-type="unicode" data-global-picker="true">
                                    <form class="ChatFileSaved"><input type="file" name="attchment[]" id="attchfileinputFileShow"  class="attchfileinput" multiple ></form>
                                    <textarea  class="chattextbox"></textarea> 
                                </span>
                                <i class="Sendbutton  btn btn-danger  fa fa-paper-plane"></i></div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ route('js.dynamic') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>


        <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ asset('assets/js/stisla.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>


        <script src="https://clipboardjs.com/dist/clipboard.min.js"></script>
        <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="//rawgit.com/notifyjs/notifyjs/master/dist/notify.js"></script>
        <script src="/assets/js/newcustomjs.js?v=<?php echo rand(); ?>"></script> 



        <link rel="stylesheet" type="text/css" href="/assets/css/emojis.css">
        <script type="text/javascript" src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js"></script>
        <script type="text/javascript" src="/assets/js/DisMojiPicker.js"></script>
        <script src="https://www.jqueryscript.net/demo/Emoji-Picker-For-Textarea-jQuery-Emojiarea/assets/js/jquery.emojiarea.js"></script>
        <script>
$(".emojis").disMojiPicker()
$(".emojis").picker(emoji => $('.emoji-editor').html($('.emoji-editor').html() + emoji));
twemoji.parse(document.body);
$('textarea').val('&#x1f604;');

$(document).ready(function () {
    ChatMessangerUp();
});
        </script>  

    </body>
</html>