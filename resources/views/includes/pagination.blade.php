   <div class="box-footer clearfix">
    <style>
    .pagination-nav{
    width:100%;
    }
    .pagination{
    float:right;
    margin:0px;   
    margin-top: -16px;
    }
    </style>
    <nav class=" pagination-nav">
    <div class="pull-left">
    <a> Page : {{$data['page']}} , NO of entries : {{$data['no_of_entries']}} , Total Page : {{$data['total_page']}} , Total entries : {{$data['total_entries']}}</a>
    </div>
<form  id="formsubmit">
    @if(request()->get('pr_id'))
<input type="hidden" name="pr_id" value="{{request()->get('pr_id')}}" >
    @endif
        <ul class="pagination pagination-primary">

            <li class="page-item"><a class="page-link" onclick="pagination('prew');" href="javascript:void(0)"><i class="icon ion-ios-arrow-back"></i></a></li>
        <li class="page-item active"><input type="number" name="page" onfocusout="pagination('text');" min="1" max="{{$data['total_page']}}" id="pagenum" value="{{request()->page ? request()->page : 1}}" style="height: 38px;width: 51px;"></li>
            <li class="page-item"><a class="page-link" onclick="pagination('next');" href="javascript:void(0)"><i class="icon ion-ios-arrow-forward"></i></a></li>
        </ul>
    </form>
    </nav>
    <script>
    $("#pagenum").keyup(function(){
        let value =  parseInt($('#pagenum').val());
        let totalpage = parseInt("{{$data['total_page']}}");
        if(value < 1){
            $('#pagenum').val(1);
        }
        if(totalpage < $('#pagenum').val()){
            $('#pagenum').val(totalpage);
        }
    });
    function pagination(data){
        let totalpage = parseInt("{{$data['total_page']}}");
        let value =  parseInt($('#pagenum').val());
        if(data  == 'prew'){
            if(value > 1){
                $('#pagenum').val( (+value - 1));
            }else{
               return false;
            }
        }
        if(data  == 'next'){
            if(value >= totalpage) { return false;}
            $('#pagenum').val( (+$('#pagenum').val() + 1));
        }
        $('#formsubmit').submit();
    }
    </script>
    </div>