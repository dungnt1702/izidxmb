@extends('layouts.app')

@section('content')
    <h3 class="col-xs-12 no-padding">Danh sách đánh giá tháng</h3>

    <table class="table table-responsive table-hover table-striped table-bordered">
        <tr>
            <td><strong>STT</strong></td>
            <td><strong>Tên nhân viên</strong></td>
            <td><strong>Tháng</strong></td>
            <td><strong>Năm</strong></td>
            <td><strong>Tổng điểm</strong></td>
            <td><strong>Xếp loại</strong></td>
            <td><strong>Action</strong></td>
        </tr>
        @foreach ($dataCheckPoint as $key => $o_checkpoint)
            <tr>
                <td>{{$key +1}}</td>
                <td> {{ Auth::user()->name }}</td>
                <td>    {{$o_checkpoint->month}}</td>
                <td>    {{$o_checkpoint->year}}</td>
                <td>    {{$o_checkpoint->total_point}}</td>
                <td>    {{$o_checkpoint->level_point}}</td>
                <td>
                    <a href="/checkpoint-by-month?id=<?php echo $o_checkpoint->id?>"><button type="button" class="btn-sm btn btn-danger"><?php echo $o_checkpoint->status == 1?'Sửa lại':'Xem'?></button></a>
                </td>
            </tr>
        @endforeach
    </table>
    <!--Hidden input-->
    <input type="hidden" name="tbl" id="tbl" value="checkpoint">

@endsection
