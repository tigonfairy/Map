<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;

        }

        tr > th, tr > td {
            wrap-text: true
        }

        /*.title {*/
        /*background-color: ;*/
        /*color:#e0dfe3;*/
        /*}*/
        .group_product {

            font-weight: bold;
            /*border: 1px solid #D9D9D9;*/
        }
        .group_product td {
            background-color: #FFC000;
        }
        .tieude {
            /*background-color: #404040;*/
            /*color: #FFC000;*/

        }

        .title {
            /*background-color: #D9D9D9;*/
            text-align: left;

        }

        .thang {
            /*background-color: #404040;*/
            font-weight: bold;
            color: #ffffff;
        }
        .thang td {
            background-color: #404040;
        }
    </style>
</head>
<body>
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    @if($type == 1)
        @php $agent = \App\Models\Agent::find($user);@endphp
        <tr class="title">
            <td>Đại lý</td>
            <td>{{$agent->name}}</td>
            <td>Mã đại lý</td>
            <td>{{$agent->code}}</td>
        </tr>
        @php $manager = $agent->user; @endphp
    @endif


        @php
        if($type != 1){
            $manager = \App\Models\User::find($id);
        }
            $nvkd = null;
            $gsv = null;
            $tv = null;
            $gdv = null;
                if($manager->position == \App\Models\User::NVKD) {
                    $nvkd = $manager;
                    if($nvkd->manager and $nvkd->manager->position == \App\Models\User::GSV) {
                        $gsv = $nvkd->manager;
                         if($gsv->manager and $gsv->manager->position == \App\Models\User::TV) {
                            $tv = $gsv->manager;
                            if($tv->manager and $tv->manager->position == \App\Models\User::GĐV) {
                                $gdv = $gsv->manager;
                            }
                         }
                          if($gsv->manager and $gsv->manager->position == \App\Models\User::GĐV) {
                            $gdv = $gsv->manager;
                         }
                    }
                    if($nvkd->manager and $nvkd->manager->position == \App\Models\User::TV) {
                          $tv = $nvkd->manager;
                              if($tv->manager and $tv->manager->position == \App\Models\User::GĐV) {
                                $gdv = $tv->manager;
                            }
                    }
                     if($nvkd->manager and $nvkd->manager->position == \App\Models\User::GĐV) {
                        $gdv = $nvkd->manager;
                       }

                }
                if($manager->position == \App\Models\User::GSV) {
                            $gsv = $manager;

                                 if($gsv->manager and $gsv->manager->position == \App\Models\User::TV) {
                                    $tv = $gsv->manager;
                                    if($tv->manager and $tv->manager->position == \App\Models\User::GĐV) {
                                        $gdv = $gsv->manager;
                                    }
                                 }
                                  if($gsv->manager and $gsv->manager->position == \App\Models\User::GĐV) {
                                    $gdv = $gsv->manager;
                                 }

                }
                  if($manager->position == \App\Models\User::TV) {
                       $tv = $manager;
                                      if($tv->manager and $tv->manager->position == \App\Models\User::GĐV) {
                                        $gdv = $tv->manager;
                                    }
                  }
                 if($manager->position == \App\Models\User::GĐV) {
                         $gdv = $manager;
                  }
        @endphp

        @if($nvkd)
            <tr class="title">
                <td>NVKD</td>
                <td>{{$nvkd->name}}</td>
                <td>Mã nhân viên</td>
                <td>{{$nvkd->code}}</td>
            </tr>

        @endif
        @if($gsv)
            <tr class="title">
                <td>Giám sát</td>
                <td>{{$gsv->name}}</td>
                <td>Mã nhân viên</td>
                <td>{{$gsv->code}}</td>
            </tr>

        @endif
        @if($tv)
            <tr class="title">
                <td>Trưởng vùng</td>
                <td>{{$tv->name}}</td>
                <td>Mã nhân viên</td>
                <td>{{$tv->code}}</td>
            </tr>

        @endif
        @if($gdv)
            <tr class="title">
                <td>Giám đốc vùng</td>
                <td>{{$gdv->name}}</td>
                <td>Mã nhân viên</td>
                <td>{{$gdv->code}}</td>
            </tr>

        @endif

        @php
            if($type == 1) {

                $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                                   ->groupBy('agent_id','month')->where('agent_id',$user)
                                                   ->get()->sum('capacity');
                 $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->groupBy('agent_id','month')->where('agent_id',$user)
                                    ->get()->sum('sales_plan');
               }
                if($type == 2) {

                $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                                   ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gsv',$manager->id)
                                                   ->get()->sum('capacity');
                 $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gsv',$manager->id)
                                    ->get()->sum('sales_plan');
                }
              if($type == 3) {

                $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                                   ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.tv',$manager->id)
                                                   ->get()->sum('capacity');
                 $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.tv',$manager->id)
                                    ->get()->sum('sales_plan');
                }
              if($type == 4) {

                $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                                   ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$manager->id)
                                                   ->get()->sum('capacity');
                 $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$manager->id)
                                    ->get()->sum('sales_plan');
                }
        @endphp


        <tr class="title">
            <td>Tổng dung lượng vùng</td>
<<<<<<< HEAD
            <td>{{number_format($dlv)}}</td>
            <td>Tổng sản lượng kế hoạch</td>
            <td>{{number_format($slkh)}}</td>
=======
<<<<<<< HEAD
            <td>{{number_format($dlv)}}</td>
            <td>Tổng sản lượng kế hoạch</td>
            <td>{{number_format($slkh)}}</td>
=======
            <td>{{$dlv}}</td>
            <td>Tổng sản lượng kế hoạch</td>
            <td>{{$slkh}}</td>
>>>>>>> 7feb217f61f296eb572a7c997c41ed3805319b3a
>>>>>>> c93895fbfe175da1ff5a6d4334c04e891cc15889
        </tr>


        <tr class="thang">
            <td></td>
            <td colspan="2">
                Sản lượng đại lý /GS vùng /Giám đốc vùng Tháng {{$startMonth}}
            </td>
            <td></td>
        </tr>


    <tr style="text-align: center" class="tieude">
        <td>Mã số</td>
        <td colspan="2">Sản phẩm</td>
        <td>Sản lượng thực tế</td>
    </tr>
    @php  $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
    @endphp
    @if($groupProduct->count())
        @foreach($groupProduct as $group)
            @php
                $array = [];
                $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
            @endphp

            @if($products->count())
                @php $string = null;
                @endphp
                    @php $slGroup = 0 @endphp
                    @foreach($products as $product)
                        @php
                            if($type == 1) {
                             $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)->where('product_id',$product->id)
                            ->where('agent_id',$user)
                                ->get()->sum('sales_real');
                            }
                            if($type == 2) {
                                $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gsv',$manager->id)->where('product_id',$product->id)
                                ->get()->sum('sales_real');
                            }
                             if($type == 3) {
                                $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.tv',$manager->id)->where('product_id',$product->id)
                                ->get()->sum('sales_real');
                            }
                             if($type == 4) {
                                $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$manager->id)->where('product_id',$product->id)
                                ->get()->sum('sales_real');
                            }





                                $slGroup += $sltt;
                                if($sltt ) {
                                 $string .= '<tr style="text-align: left">';
                        $string .= '<td>'.$product->code.'</td>';
                        $string .= '<td colspan="2">'.$product->name_vn.'</td>';
                        $string .= '<td >'.number_format($sltt).'</td>';
                        $string .= '</tr>';
                                }

                        @endphp
                    @endforeach
                    @if($slGroup > 0)
                    <tr style="text-align: center" class="group_product">
                        <td>{{$group->code}}</td>
                        <td colspan="2">{{$group->name_vn}}</td>
                        <td>{{number_format($slGroup)}}</td>

                    </tr>
                        {!! $string !!}
                        @endif





            @endif

        @endforeach
    @endif
</table>
</body>
</html>
