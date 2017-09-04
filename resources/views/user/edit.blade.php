@extends('layouts.app')
@section('content')
<h3 class="col-xs-12 no-padding">Sửa thông tin User</h3>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" id="user_id" value="<?php echo $user_id?>">
        <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
        <div class="form-group">
            <div class="col-xs-12 col-sm-7 no-padding">
                <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Mã nhân viên</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="data[code]" class="form-control" placeholder="Mã nhân viên" value="<?php echo $o_user->code ?>" id="code">
                </div>
            </div> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Tên đầy đủ</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="data[name]" class="form-control" id="name" placeholder="Tên đầy đủ" value="<?php echo ($o_user?$o_user->name:$a_data['name'])?>">
                </div>
            </div>
            
        </div> 
        
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Email</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="email" disabled class="form-control" id="email" value="<?php echo ($o_user?$o_user->email:$a_data['email'])?>">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Phòng ban</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm " id="department" name="data[department_id]" onchange="GLOBAL_JS.v_fLoadGroup()">
                        <option value="0">Chọn phòng ban</option>
                        <?php foreach ($a_department as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->id == $o_user->department_id?'selected':'')?>><?php echo $val->name?></option>
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
                        <?php foreach ($a_group as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->id == $o_user->group_id?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Chức vụ</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="position" name="data[position_id]">
                        <?php foreach ($a_position as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->id == $o_user->position_id?'selected':'')?>><?php echo $val->name?></option>
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
                        <?php foreach ($a_department as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ( isset($o_direct_manager) && $val->id == $o_direct_manager->department_id?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Người quản lý trực tiếp</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="direct_manager" name="data[direct_manager_id]">
                        <option value="0">Không có</option>
                        <?php if(isset($a_manager)) { ?>
                            <?php foreach ($a_manager as $o_manager) { ?>
                                <option value="<?php echo $o_manager->id?>" <?php echo ($o_manager->id == $o_direct_manager->id?'selected':'')?>><?php echo $o_manager->name?></option>
                            <?php } ?> 
                        <?php } ?>
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
                        <option value="<?php echo $val->id?>" <?php echo ($val->id == $o_user->job_id?'selected':'')?>><?php echo $val->name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>  
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Quyền hạn</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" name="data[role_id]">
                        <?php foreach ($a_role as $val) { ?>
                        <option value="<?php echo $val->id?>" <?php echo ($val->id == $o_user->role_id?'selected':'')?>><?php echo $val->name?></option>
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
                        <option value="0" <?php echo ($o_user->hr_type == 0?'selected':'')?>>Bình thường</option>
                        <option value="1" <?php echo ($o_user->hr_type == 1?'selected':'')?>>HRM</option>
                        <option value="2" <?php echo ($o_user->hr_type == 2?'selected':'')?>>Reporter</option>
                    </select>
                </div>
            </div>  
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Manager</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="top_menu" name="data[is_manager]" type="checkbox" class="form-control change_direct_manager" <?php echo ($o_user->is_manager == 0?'':'checked="checked"')?>>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Trạng thái</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="top_menu" name="data[status]" type="checkbox" class="form-control" <?php echo ($o_user->status == 0?'':'checked="checked"')?>>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 no-padding">
                <label class="col-xs-12 col-sm-3 control-label text-left">Thêm nhiều</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input id="multi-insert" name="multi-insert" type="checkbox" class="form-control">
                </div>            
            </div>
        </div>
        <input type="button" class="btn btn-primary btn-sm" value="Cập nhật" onclick="GLOBAL_JS.v_fSubmitAddUser('edit')">
        <input type="submit" name="submit" class="btn btn-primary btn-sm hidden submit">
        </form>
@endsection
