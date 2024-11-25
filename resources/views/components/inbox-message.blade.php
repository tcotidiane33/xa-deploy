<div class="inbox-row widget-shadow" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="mail"><input type="checkbox" class="checkbox"></div>
    <div class="mail"><img src="{{ $message['avatar'] }}" alt=""/></div>
    <div class="mail mail-name"><h6>{{ $message['sender'] }}</h6></div>
    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $message['id'] }}" aria-expanded="true" aria-controls="collapse{{ $message['id'] }}">
        <div class="mail"><p>{{ $message['subject'] }}</p></div>
    </a>
    <div class="mail-right dots_drop">
        <div class="dropdown">
            <a href="#" data-toggle="dropdown" aria-expanded="false">
                <p><i class="fa fa-ellipsis-v mail-icon"></i></p>
            </a>
            <ul class="dropdown-menu float-right">
                <li>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $message['id'] }}" aria-expanded="true" aria-controls="collapse{{ $message['id'] }}">
                        <i class="fa fa-reply mail-icon"></i>
                        Reply
                    </a>
                </li>
                <li>
                    <a href="#" title="">
                        <i class="fa fa-download mail-icon"></i>
                        Archive
                    </a>
                </li>
                <li>
                    <a href="#" class="font-red" title="">
                        <i class="fa fa-trash-o mail-icon"></i>
                        Delete
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="mail-right"><p>{{ $message['date'] }}</p></div>
    <div class="clearfix"></div>
    <div id="collapse{{ $message['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $message['id'] }}">
        <div class="mail-body">
            <p>{{ $message['content'] }}</p>
            <form>
                <input type="text" placeholder="Reply to sender" required="">
                <input type="submit" value="Send">
            </form>
        </div>
    </div>
</div>
