@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding"><?php echo $i_id == ''?'Thêm phòng ban mới':'Sửa phòng ban'?></h3>
<div class="alert alert-danger hide"></div>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <input type="hidden" id="id" value="<?php echo $i_id?>">
    <input type="hidden" id="tbl" value="departments">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="name" class="col-xs-12 col-sm-3 control-label text-left">Tên phòng ban</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <input id="name" name="name" field-name="Tên Phòng ban" <?php echo $i_id == 0 ? '' : 'old_val="'.$a_Department->name.'"'?> type="text" value="<?php echo isset($a_Department->name)?$a_Department->name:"" ?>" class="form-control check-duplicate" placeholder="Tên phòng ban" required />
            </div>
        </div>
        
    </div>
    <div class="form-group">        
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="guid" class="col-xs-12 col-sm-3 control-label text-left">Guid</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <input id="guid" name="guid" type="text" class="form-control" placeholder="guid" value="<?php echo isset($a_Department->guid)?$a_Department->guid:"" ?>" required>
            </div>
        </div>
    </div>
   
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
            <div class="col-xs-6 no-left-padding">
                <input id="status" name="status" type="checkbox" class="form-control" <?php if (isset($a_Department->status) && $a_Department->status): ?>checked<?php endif ?>>
            </div>            
        </div>
    </div>
    <?php if($i_id == 0) { ?>
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding">
                <label for="status" class="col-xs-6 control-label text-left">Thêm nhiều</label>
                <div class="col-xs-6 no-left-padding">
                    <input id="multi-insert" name="multi-insert" type="checkbox" class="form-control">
                </div>            
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="button" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm " onclick="GLOBAL_JS.v_fSubmitDepartmentValidate()"/>
            <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
        </div>
    </div>    
</form>

@endsection