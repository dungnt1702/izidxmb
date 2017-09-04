@extends('layouts.app')

@section('content')
<h3 class="col-xs-12 no-padding">Danh Mục Nghề nghiệp</h3>
<form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <div class="form-group">
        <select id="search_status" name="search_status" class="form-control input-sm">
            <option value="">Trạng thái</option>
            <option value="1" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 1?'selected':''?>>Trạng thái Hiển thị</option>
            <option value="0" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 0?'selected':''?>>Trạng thái Ẩn</option>
            <option value="2" <?php echo isset($a_search['search_status']) && $a_search['search_status'] == 2?'selected':''?>>Trạng thái Thùng rác</option>
        </select>
    </div>
    <div class="form-group">
        <input id="search_field" name="search_field" type="text" class="form-control input-sm" placeholder="Nhập tên nghề nghiệp" value="<?php echo isset($a_search['search_field'])?$a_search['search_field']:''?>">
    </div>
    <div class="form-group">
        <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmitAll()">
        <input type="submit" class="btn btn-success btn-sm submit hide">
    </div>
</form>
    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Tên nghề nghiệp</strong></td>
            <td><strong>Trạng thái</strong></td>
            <td><strong>Ngày tạo</strong></td>
            <td><strong>Ngày sửa</strong></td>                
            <td><strong>Action</strong></td>
        </tr>                        
    @foreach ($a_jobs as $key => $o_job)

        <tr>
            <td><?php echo ($key + $a_jobs->perPage() * $a_jobs->currentPage() - $a_jobs->perPage() + 1 )?></td>
            <td>    {{ $o_job->name }}</td>
            <td class="text-center"><input id="status_<?= $o_job->id;?>" name="status_<?=$o_job->id;?>" type="checkbox" class="" value="1" <?php if($o_job->status == 1) echo "checked"?> disabled/></td>
            <td>    {{ $o_job->created_at }}</td>
            <td>    {{ $o_job->updated_at }}</td>
            <td>                    
                <?php
                    if($o_job->status == 1 || $o_job->status == 0){
                ?>
                <a title="Edit" href="<?php echo Request::root().'/jobs/modify?id='.$o_job->id;?>" title="Edit" class="not-underline">
                    <i class="fa fa-edit fw"></i>
                </a>
                <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $o_job->id }},1)" title="Cho vào thùng rác" class="not-underline">
                <i class="fa fa-trash fa-fw text-danger"></i>
                </a>
                <?php }else{ ?>
                <a title="Khôi phục user" href="javascript:GLOBAL_JS.v_fRecoverRow({{ $o_job->id }})"  title="Edit" class="not-underline">
                    <i class="fa fa-upload fw"></i>
                </a>
                <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $o_job->id }},2)" title="Xóa vĩnh viễn" class="not-underline">
                    <i class="fa fa-trash-o fa-fw text-danger"></i>
                </a>
                <?php }?>
            </td>
        </tr>
    @endforeach
    </table>
<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="jobs">
    {!! $a_jobs->links() !!}  
@endsection
