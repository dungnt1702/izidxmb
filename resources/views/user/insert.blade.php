@extends('layouts.app')
@section('content')
<h3 class="col-xs-12 no-padding">Thêm User</h3>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <div class="col-xs-12 col-sm-7 no-padding">
                <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Mã nhân viên</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="data[code]" class="form-control" placeholder="Mã nhân viên" value="" id="code">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Tên đầy đủ</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="data[name]" class="form-control" placeholder="Tên đầy đủ" value="" id="name">
                </div>
            </div>
            
        </div> 
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Email</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="email" class="form-control" placeholder="Email đăng nhập (VD: abc@dxmb.vn hoặc abc)" value="" id="email">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Phòng ban</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm " id="department" name="data[department_id]" onchange="GLOBAL_JS.v_fLoadGroup()">
                        <option value="0">Chọn phòng ban</option>
                        <?php foreach ($a_departments as $val) { ?>
                        <option value="<?php echo $val->id?>"><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>          
        </div>
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Nhóm</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm " id="group" name="data[group_id]">
                        <option value="0">Chọn nhóm trong phòng ban</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Chức vụ</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="position" name="data[position_id]">
                        <?php foreach ($a_pos as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->name == 'Nhân viên'?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>     
        </div>
        
        <div class="form-group"> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Nhóm quản lý</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="group_manager" onchange="GLOBAL_JS.v_fLoadDirecManager()">
                        <option value="0">Không có</option>
                        <?php foreach ($a_departments as $val) { ?>
                        <option value="<?php echo $val->id?>"><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Người quản lý trực tiếp</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="direct_manager" name="data[direct_manager_id]">
                        <option value="0">Không có</option>
                    </select>
                </div>
            </div> 
        </div>
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Chọn nghề</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="job" name="data[job_id]">
                        <?php foreach ($a_job as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->name == 'Kinh doanh'?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Quyền hạn</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" name="data[role_id]">
                        <?php foreach ($a_role as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->name == 'staff'?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
         </div>
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Mật khẩu</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" id="password">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Nhập lại mật khẩu</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="re_password">
                </div>
            </div>    
        </div> 
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">HRM</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="hr_type" name="data[hr_type]">
                        <option value="0">Bình thường</option>
                        <option value="1">HRM</option>
                        <option value="2">Reporter</option>
                    </select>
                </div>
            </div>   
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Manager</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="top_menu" name="data[is_manager]" type="checkbox" class="form-control change_direct_manager">
                </div>
            </div>
         </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Trạng thái</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="top_menu" name="data[status]" type="checkbox" class="form-control" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label class="col-xs-12 col-sm-3 control-label text-left">Thêm nhiều</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="multi-insert" name="multi-insert" type="checkbox" class="form-control">
                </div>            
            </div>
        </div>

        <input type="button" class="btn btn-primary btn-sm" value="Cập nhật" onclick="GLOBAL_JS.v_fSubmitAddUser('insert')">
        <input type="submit" name="submit" class="btn btn-primary btn-sm hidden submit">
    </form>
@endsection
