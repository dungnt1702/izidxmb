@extends('layouts.app')
@section('content')
    <h3 class="col-xs-12 no-padding">Thay đổi người duyệt đánh giá tháng</h3>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <div class="col-xs-12 col-sm-4 no-padding">
                <label for="" class="col-xs-12 col-sm-12 control-label text-left" id="error"></label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Phòng ban người duyệt cấp 1</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="group_manager" onchange="GLOBAL_JS.v_fLoadDirecManager()" name="new_department_id">
                        <option value="0">Chọn phòng ban</option>
                        <?php foreach ($a_departments as $i_Department => $sz_Deparment) { ?>
                        <option value="<?php echo $i_Department?>"><?php echo $sz_Deparment?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Người duyệt đánh giá tháng cấp 1</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="direct_manager" name="new_manager_id">
                        <option value="0">Không có</option>
                    </select>
                </div>
            </div>
        </div>
        <!--Người duyet cáp 2-->
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Phòng ban người duyệt cấp 2</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="group_manager2" onchange="GLOBAL_JS.v_fLoadDirecManager2()" name="new_department_id2">
                        <option value="0">Chọn phòng ban</option>
                        <?php foreach ($a_departments as $i_Department => $sz_Deparment) { ?>
                        <option value="<?php echo $i_Department?>"><?php echo $sz_Deparment?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Người duyệt đánh giá tháng cấp 2</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" id="direct_manager2" name="new_manager_id2">
                        <option value="0">Không có</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-4 control-label text-left">Nội dung</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <textarea class="form-control" rows="5" id="comment" name="comment" placeholder="Nhập nội dung yêu cầu"></textarea>
                </div>
            </div>
        </div>
        <input type="button" class="btn btn-primary btn-sm" value="Cập nhật" onclick="GLOBAL_JS.v_fSubmitChangeManager()">
        <input type="submit" name="submit" class="btn btn-primary btn-sm hidden submit">
    </form>
@endsection
