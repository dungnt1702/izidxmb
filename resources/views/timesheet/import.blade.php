@extends('layouts.app')
@section('content')

<div class="col-xs-6 alert alert-success">
  <strong>Chú ý!</strong> bạn cần nhập file (xls, xlsx) <br/>
  File phải bao gồm các cột là date, code (mã nhân viên), name (tên), checkin (chấm công vào), 
  checkout(chấm công ra).<br/>
  Các file trong excel phải định dạng text. (format cells > text).<br/>
  Tên file cần viết liền không dấu.
</div>
<h3 class="col-xs-12 no-padding">Import bảng chấm công</h3>
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    @if(isset($a_Res))
    <div class="form-group">
        <div class="col-xs-12 col-sm-12 no-padding">
            <label class="alert alert-warning">{!! $a_Res !!}</label>
        </div>
    </div>
    @endif
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <input type="file" name="excel" id="excel" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="submit" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm ">
        </div>
    </div>
</form>

@endsection