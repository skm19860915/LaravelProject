<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/emojis.css') }}?v=<?php echo rand(); ?>">
<script type="text/javascript" src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/DisMojiPicker.js') }}?v=<?php echo rand(); ?>"></script>
<script src="https://www.jqueryscript.net/demo/Emoji-Picker-For-Textarea-jQuery-Emojiarea/assets/js/jquery.emojiarea.js"></script>
<script>
$(".emojis").disMojiPicker()
    $(".emojis").picker(emoji => $('.emoji-editor').html($('.emoji-editor').html()+emoji));
    twemoji.parse(document.body);
    // $('textarea').val('');
</script>
