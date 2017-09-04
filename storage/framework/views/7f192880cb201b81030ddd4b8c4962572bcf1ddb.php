<?php $__env->startSection('content'); ?>
    <h3 class="col-xs-12 no-padding">Thông tin User</h3>
    <div class="alert alert-success fade in" style="margin-top:18px;">
        <strong>Tên nhân viên:</strong> <?php echo Auth::user()->name ?><br>
        <strong>Chức vụ:</strong> <?php echo isset($o_position)?$o_position->name:'';  ?><br>
        <?php if(isset($o_group)) { ?><strong>Nhóm:</strong> <?php echo isset($o_group)?$o_group->name:'' ?><br> <?php }?>
        <strong>Phòng ban:</strong> <?php echo Auth::user()->department_id !=0?(isset($o_department)?$o_department->name:''): '<span style="color:#cd0a0a">Bạn chưa có phòng ban, hãy liên lạc với phòng hành chính nhân sự để cập nhật thông tin!</span>'?><br>
        <strong>Nghề nghiệp:</strong> <?php echo isset($o_job)?$o_job->name:'' ?><br>
        <strong>Người quản lý trực tiếp:</strong> <?php echo Auth::user()->direct_manager_id !=0?(isset($o_direct_manager)?$o_direct_manager->name:''):'<span style="color:#cd0a0a">Bạn chưa có người quản lý trực tiếp, hãy liên lạc với phòng hành chính nhân sự để cập nhật thông tin!</span>' ?><br>
    </div>
    <?php
    if(Auth::user()->direct_manager_id !=0 && Auth::user()->department_id !=0 || Auth::user()->email == 'quyetvc@dxmb.vn' || Auth::user()->email == 'admin@dxmb.vn')
    {
    ?>
    <h3 class="col-xs-12 no-padding">Đăng ký thêm giờ</h3>
    <div class="">
        <form class="form-horizontal" method="post" action="">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="form-group">
                <div class="col-xs-12 col-sm-7 no-padding">
                    <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left">Chọn Loại</label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <select class="form-control input-sm " id="typeot" name="data[type_ot]">
                            <option value="1">làm thêm cộng phép nghỉ bù</option>
                            <?php if((is_object($o_job)) && $o_job->id != 3){?><option value="2">làm thêm tính tăng ca</option><?php }?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group go_business">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left">Từ ngày</label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <input type="text" class="form-control datetimepicker" id="from_time_business" name="data[from_time_business]" placeholder="Chọn thời gian bắt đầu đi công tác">
                    </div>
                </div>
            </div>
            <div class="form-group go_business">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left">Đến ngày</label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <input type="text" class="form-control datetimepicker" id="to_time_business" name="data[to_time_business]" placeholder="Chọn thời gian kết thúc công tác">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left">Thời gian (tiếng)</label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <input type="number" class="form-control" name="data[total_time]" placeholder="Ví dụ 1.5" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left"><?php echo ($o_direct_manager->id == $o_hrm->id?'HRM':'Người duyệt đơn')?></label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <select class="form-control input-sm" name="data[manager_id]">
                            <?php if(isset($o_direct_manager) && is_object($o_direct_manager)) { ?>
                            <option value="<?php echo $o_direct_manager->id?>"><?php echo $o_direct_manager->name?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="" class="col-xs-12 col-sm-6 control-label text-left">Nội dung</label>
                    <div class="col-xs-12 col-sm-6 no-padding">
                        <textarea class="form-control" rows="5" id="user_comment" name="data[user_comment]" placeholder="Nhập nội dung vắng mặt"></textarea>
                    </div>
                </div>
            </div>

            <span class="hide" id="numb_of_work"><?php echo (is_object($o_department))?$o_department->numb_of_work:''?></span>
            <span class="hide" id="enable_sunday"><?php echo (is_object($o_job))?$o_job->enable_sunday:''?></span>
            <div class="col-sm-5 submit_register"><input type="button" class="btn btn-primary btn-sm" value="Đăng ký" onclick="GLOBAL_JS.v_fSubmitOverTime()"></div>
            <input type="submit" name="submit" class="btn btn-primary btn-sm hidden submit">
        </form>
    </div>
    <?php }?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>