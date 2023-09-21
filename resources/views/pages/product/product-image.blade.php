<div style="text-align: center; margin: 50px;" >
@if($product_image->drawing_image)
<img  src="{{url('img/productimg/'.$product_image->drawing_image)}}" alt="">
@else
<h4 style="color: orange;">Images Not Uploaded..</h4>
@endif
</div>