<a href="{{route('Admin::saleAgent@edit', ['agentId' => $agent_id , 'month' => $month ])}}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a>
<a onclick="return xoaCat();" href="{{ route('Admin::saleAgent@delete',['agentId' => $agent_id , 'month' => $month ]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a>

