@if($users)
    <option value="">-- Chọn quản lý --</option>
    @foreach($users as $user)
        <option value="{{$user->id}}">{{$user->name}}-{{\App\Models\User::$positionTexts[$user->position]}}</option>
    @endforeach
    @else

@endif