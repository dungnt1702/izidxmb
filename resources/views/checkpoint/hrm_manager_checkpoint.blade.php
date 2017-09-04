@extends('layouts.app')

@section('content')
<h3 class="col-xs-12 no-padding">HRM kiểm duyệt đánh giá hàng tháng</h3>
<form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
        <div class="form-group">
            <select id="search_department" name="search_department" class="form-control input-sm">
                <option value="">Chọn phòng ban</option>
                <?php foreach ($a_Departments as $i_Department => $sz_Deparment) { ?>
                    <option value="<?php echo $i_Department?>" <?php echo isset($a_search['search_department']) && $a_search['search_department'] == $i_Department?'selected':''?>><?php echo $sz_Deparment?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_year" name="search_year" class="form-control input-sm">
            <option value="0">Chọn năm</option>
            <?php 
                $current_year = date("Y");
                for ($i = $current_year - 1; $i <= $current_year; $i++) {
            ?>
            <option value="<?php echo $i?>" <?php echo isset($a_search['search_year']) && $a_search['search_year'] == $i? 'selected':''?>>Năm <?php echo $i?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_month" name="search_month" class="form-control input-sm">
            <option value="0">Chọn tháng</option>
            <?php 
                $current_month = date("m");
                for ($i = 1; $i <=12; $i++) { 
            ?>
                <option value="<?php echo $i?>" <?php echo isset($a_search['search_month']) && $a_search['search_month'] == $i?'selected':''?>>Tháng <?php echo $i?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select id="search_month" name="search_status" class="form-control input-sm">
                <option value="1" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 1?'selected':''?>>Chờ duyệt</option>
                <option value="3" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 3?'selected':''?>>Đã duyệt</option>
            </select>
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmit()">
            <input type="submit" class="btn btn-success btn-sm submit hide">
        </div>
    </form>
<form method="POST" action="">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<h3 class="col-xs-12 no-padding">Danh sách phiếu đánh giá tháng</h3>
<input type="submit" class="btn-sm btn btn-primary check-check-all" name="allow_all" value="Duyệt nhiều" onclick="return confirm('Bạn có chắc chắn muốn đồng ý các phiếu đánh giá này?')" />
    <table class="table table-responsive table-hover table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <th><input type="checkbox" id="check_all" class="checkAll"></th>
            <td><strong>Mã NV</strong></td>
            <td><strong>Họ tên</strong></td>
            <td><strong>Email</strong></td>
            <td><strong>Phòng ban</strong></td>
            <td><strong>Chức vụ</strong></td>
            <td><strong>Tổng điểm</strong></td>
            <td><strong>Xếp loại</strong></td>
            <td><strong>Trạng thái</strong></td>
            <td><strong>Ngày làm</strong></td>
            <td><strong>Ngày sửa</strong></td>
            <td><strong>Action</strong></td>
        </tr>                        
    @foreach ($a_checkpoints as $key => $o_checkpoint)

        <tr id="tr_<?php echo $o_checkpoint->id ?>">
            <td><?php echo ($key + $a_checkpoints->perPage() * $a_checkpoints->currentPage() - $a_checkpoints->perPage() + 1 )?></td>
            <td><input type="checkbox" class="chk_item" value="<?php echo $o_checkpoint->id?>" name="check[]"/></td>
            <td>    {{ $a_Users[$o_checkpoint->user_id]['code'] }}</td>
            <td>    {{ $a_Users[$o_checkpoint->user_id]['name'] }}</td>
            <td>    {{ $a_Users[$o_checkpoint->user_id]['email'] }}</td>
            <td>    {{ $a_Users[$o_checkpoint->user_id]['department'] }}</td>
            <td>    {{ $a_Users[$o_checkpoint->user_id]['position'] }}</td>
            <td>    {{ $o_checkpoint->total_point }}</td>
            <td>    {{ $o_checkpoint->level_point }}</td>
            <td>    <?php echo $o_checkpoint->status == 2 ? 'Quản lý đã duyệt' : 'Nhân sự đã duyệt'?></td>
            <td>    {{ $o_checkpoint->created_at }}</td>
            <td>    {{ $o_checkpoint->updated_at }}</td>
            <td>             
                <?php if($o_checkpoint->status == 2 || ($o_checkpoint->status == 1 && $o_checkpoint->censor_id == 397)) { ?>
                <button type="button" class="btn-sm btn btn-primary allow-checkpoint" data-id="{{ $o_checkpoint->id }}">Đồng ý</button>
                <a href="/checkpoint-by-month?id= {{ $o_checkpoint->id }}"><button type="button" class="btn-sm btn btn-danger">Sửa lại</button></a>
                <?php } ?>
            </td>
        </tr>
    @endforeach
    </table>
    @if (count($a_checkpoints) == 0)
        <div class="alert alert-danger no-data">
            <tr>
                <strong>{{ 'Không có dữ liệu' }}</strong>
            </tr>
        </div>
    @endif
    <input type="submit" class="btn-sm btn btn-primary check-check-all" name="allow_all" value="Duyệt nhiều" onclick="return confirm('Bạn có chắc chắn muốn đồng ý các phiếu đánh giá này?')" />
    </form>
    <!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="checkpoint">
    {!! $a_checkpoints->links() !!}  
@endsection
