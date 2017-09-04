@extends('layouts.app')
@section('content')
<h3 class="col-xs-12 no-padding">Thay đổi mật khẩu</h3>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <div class="col-xs-12 col-sm-4 no-padding">
                <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
            </div>
        </div>
        
        <div class="form-group"> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Nhập mật khẩu cũ</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" class="form-control" name="" placeholder="Nhập mật khẩu hiện tại của bạn" id="old_password"> 
                </div>
            </div> 
        </div>
        <div class="form-group"> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Mật khẩu mới</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" name="password" class="form-control" name="" placeholder="Nhập mật khẩu mới" id="password">
                </div>
            </div> 
        </div>
        <div class="form-group"> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Nhập lại Mật khẩu mới</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" class="form-control" name="" placeholder="Nhập lại mật khẩu mới" id="re_password">
                </div>
            </div> 
        </div>
        <input type="button" class="btn btn-primary btn-sm" value="Cập nhật" onclick="GLOBAL_JS.v_fSubmitChangePassword()">
        <input type="submit" name="submit" class="btn btn-primary btn-sm hidden submit">
    </form>
@endsection
