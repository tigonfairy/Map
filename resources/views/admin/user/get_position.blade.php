@if($users)
    <option value="">-- Chọn quản lý --</option>
    @foreach($users as $user)
        <option value="{{$user->id}}" @if($manager and $manager == $user->id) selected @endif>{{$user->name}}-{{\App\Models\User::$positionTexts[$user->position]}}</option>
    @endforeach
    @else

@endif