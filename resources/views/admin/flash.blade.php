@if (session('success'))
    <div class="alert bg-success alert-styled-left">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert bg-danger alert-styled-left">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        {{ session('error') }}
    </div>
@endif
