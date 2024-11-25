<div class="compose-right widget-shadow">
    <div class="panel-default">
        <div class="panel-heading">
            Compose New Message
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                Please fill details to send a new message
            </div>
            <form class="com-mail" action="{{ $action ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control1 control3" name="to" placeholder="To :" required>
                <input type="text" class="form-control1 control3" name="subject" placeholder="Subject :" required>
                <textarea rows="6" class="form-control1 control2" name="message" placeholder="Message :" required></textarea>
                <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fa fa-paperclip"></i> Attachment
                        <input type="file" name="attachment">
                    </div>
                    <p class="help-block">Max. 32MB</p>
                </div>
                <input type="submit" value="Send Message">
            </form>
        </div>
    </div>
</div>

{{-- 
ussed:
<x-compose-message :action="route('send.message')" /> --}}
