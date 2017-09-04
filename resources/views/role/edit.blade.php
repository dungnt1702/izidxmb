@extends('layouts.app')
@section('content')

<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Controller/Action</th>
                <th>ACTIVE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($a_AllRole as $sz_Controller => $a_Action)           
                <tr>
                    <td colspan="4"><strong>{{ $a_NameController[$sz_Controller] }}</strong>&nbsp &nbsp<input type="checkbox" id="{{ $sz_Controller }}" onclick="GLOBAL_JS.v_fCheckAllRoleGroup('{{ $sz_Controller }}')"></td>
                </tr>
                @foreach($a_Action as $key => $sz_ActionName)
                    <tr>
                        <td>&nbsp &nbsp &nbsp {{ $key }} </td>
                        <td>
                            <input type="checkbox" value="1" name="{{ $sz_Controller.'['.$sz_ActionName.']' }}" id="" class="{{ $sz_Controller }}" <?php if(isset($a_RoleActive[$sz_Controller])  && in_array($sz_ActionName, $a_RoleActive[$sz_Controller])) echo "checked"; ?> />
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <input class="btn btn-warning btn-sm" type="reset" value="Nhập lại" >
            <input class="btn btn-primary btn-sm" id="btn_submit_data" name="submit" value="Cập nhật" type="submit">
        </div>
    </div>    
</form>

@endsection