<a href="{{route('Admin::map@editMap', $id)}}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a>
<a onclick="return xoaCat();" href="{{ route('Admin::map@deleteMap', $id) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a>
