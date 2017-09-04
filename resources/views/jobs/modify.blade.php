@extends('layouts.app')
@section('content')
    <h3 class="col-xs-12 no-padding"><?php echo $i_id == ''?'Thêm nghề nghiệp mới':'Sửa nghề nghiệp'?></h3>
    <div class="alert alert-danger hide"></div>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <input type="hidden" id="tbl" value="jobs">
        <input type="hidden" id="id" value="<?php echo $i_id?>">
        <div id="error" class="align-center"></div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding">
                <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-5 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Tên nghề nghiệp</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                     <input class="form-control check-duplicate" type="text" field-name="Tên nghề nghiệp" id="name" name="data[name]" <?php echo $i_id ==''?'':'old_val="'.$o_job->name.'"'?> field_name="Tên nghề nghiệp"  placeholder="Tên nghề nghiệp" value="<?php echo $i_id == ''?'':$o_job->name?>">
                </div>
            </div>   
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding">
                <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
                <div class="col-xs-6 no-left-padding">
                    <input class="form-control" type="checkbox" name="data[status]"  <?php echo ($i_id ==''?'':($o_job->status == 0?'':'checked="checked"'))?>>
                </div>
            </div>
        </div>
        <?php if($i_id == 0) { ?>
            <div class="form-group">
                <div class="col-xs-12 col-sm-3 no-padding">
                    <label class="col-xs-6 control-label text-left">Thêm nhiều</label>
                    <div class="col-xs-6 no-left-padding">
                        <input id="multi-insert" name="multi-insert" type="checkbox" class="form-control">
                    </div>            
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <input type="button" class="btn btn-primary btn-sm" value="Cập nhật" onclick="GLOBAL_JS.v_fSubmitJobsValidate()">
                <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
            </div>
        </div>
    </form>
@endsection
