@extends('layouts.app')
@section('content')
<h3 class="col-xs-12 no-padding"><?php echo 'Thêm mới nhóm quyền' ?></h3>
<div class="alert alert-danger hide"></div>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <input type="hidden" id="tbl" value="rolegroups">
        <div id="error" class="align-center"></div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-5 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Tên nhóm quyền mới</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                     <input class="form-control check-duplicate" type="text" id="name" name="name" placeholder="Tên nhóm quyền mới" value="">
                </div>
            </div>   
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding">
                <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
                <div class="col-xs-6 no-left-padding">
                    <input class="form-control" type="checkbox" name="status">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6">
                <input type="button" class="btn btn-primary btn-sm" value="Thêm mới" onclick="GLOBAL_JS.v_fSubmitJobsValidate()">
                <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
            </div>
        </div>
    </form>
@endsection
