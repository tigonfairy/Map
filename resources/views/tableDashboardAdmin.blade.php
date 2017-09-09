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
        @php

                $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                                   ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->whereIn('agents.id',$listAgentIds)
                                                   ->get()->sum('capacity');
                 $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->whereIn('agents.id',$listAgentIds)
                                    ->get()->sum('sales_plan');


        @endphp


        <tr class="title">
            <td>Tổng dung lượng vùng</td>
            <td>{{number_format($dlv)}}</td>
            <td>Tổng sản lượng kế hoạch</td>
            <td>{{number_format($slkh)}}</td>
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
                                $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->whereIn('agents.id',$listAgentIds)->where('product_id',$product->id)
                                ->get()->sum('sales_real');

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
<<<<<<< HEAD
            @endif
=======





            @endif

>>>>>>> 85bef5124b19aea4dc9d53ca4212b35e255f6189
        @endforeach
    @endif
</table>
</body>
</html>
