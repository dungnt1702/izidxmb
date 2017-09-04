@extends('layouts.app')
@section('content')

    <h3 class="col-xs-12 no-padding">Danh sách vắng mặt</h3>
    <div class="">
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr>
                <td><strong>STT</strong></td>
                <td><strong>Nội dung</strong></td>
                <td><strong>Trạng thái</strong></td>
                <td><strong>Loại</strong></td>
                <td><strong>Thời gian</strong></td>
                <td><strong>Quản lý comment</strong></td>
                <td><strong>HRM comment</strong></td>
                <td><strong>Tổng thời gian</strong></td>
            </tr>

            @foreach ($a_leaveRequest as $a_val)
                <tr id="tr_<?=$a_val->id?>">
                    <td>    {{ $a_val->stt }}</td>
                    <td>    {{ $a_val->user_comment }}</td>
                    <td>    {{ $a_val->status }}</td>
                    <td>    {{ $a_val->leave_type_name }}</td>
                    <td>    <?php echo $a_val->time?></td>
                    <td>    {{ $a_val->manager_comment }}</td>
                    <td>    {{ $a_val->hrm_comment }}</td>
                    <td>    {{ $a_val->total_time }} Tiếng</td>
                </tr>
            @endforeach
        </table>
        @if (count($a_leaveRequest) == 0)
            <div class="alert alert-danger no-data">
                <tr>
                    <strong>{{ 'Bạn chưa có đơn vắng mặt cá nhân nào' }}</strong>
                </tr>
            </div>
        @endif
    </div>

    <!--Hidden input-->
    <input type="hidden" name="tbl" id="tbl" value="leave_requests">
    {!! $a_leaveRequest->links() !!}

@endsection