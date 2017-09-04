var GLOBAL_JS = {
    go_on_business : 'Phiếu công tác',
    tbl: $('#tbl').val(),
    /**
     * Auth: Dienct
     * Des: delete record
     * Since: 31/12/2015
     * */
    b_fValidateEmpty : function(e) {
		var t=/^ *$/;
		if(e==""||t.test(e)) {
			return true;
		}
		return false;
	},
    b_fCheckEmail : function(e) {
            var t = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
            return t.test(e)
    },
    b_fCheckEmailDXMB : function(sz_Email) {
        var sz_check = sz_Email.substr(sz_Email.indexOf("@") + 1);
        if(sz_check != 'dxmb.vn') return false;
        else return true;
    },
    b_fCheckMinLength : function(e, the_i_Length) {
		if (e.length < the_i_Length) {
			return false;
		}
		return true;
	},
    b_fCheckMaxLength : function(e, the_i_Length) {
            if (e.length > the_i_Length) {
                    return false;
            }
            return true;
    },
    b_fCheckConfirmPwd : function(e, t) {
            if (e == t) {
                    return true;
            }
            return false;
	},
    v_fDelRow : function(id,type) {
        var sz_confirm = type == 1 ? "Bạn có muốn cho vào thùng rác?" : "Xóa vĩnh viễn tài khoản trên Izi. Bạn có muốn tiếp tục?";
		if(confirm(sz_confirm)) {
                    var o_data = {
                        id: id,
                        type:type,
                        func:'delete-row',
                        tbl: GLOBAL_JS.sz_Tbl,
                    };
			$.ajax({
                            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                            type: 'POST',
                            data: o_data,
                            dataType: 'json',
                            success: function (data) {
                                alert(data.success);
                                location.reload();

                            }
                        });
		}
	},
    /**
     * Auth: Dienct
     * Des: recover record
     * Since: 31/12/2015
     * */
    v_fRecoverRow : function(id) {

        var o_data = {
            id: id,
            func:'recover-row',
            tbl: GLOBAL_JS.sz_Tbl,
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
                alert(data.success);
                location.reload();
            }
        });
    },
    /**
     * Auth: HuyNN
     * Des: Submit with Validate Form Add or Edit User
     * Since: 05/01/2015
     * */
    v_fSubmitAddUser : function(sz_Act) {
        var o_ErrorAlert = $('#error');
        var sz_Name = $('#name').val();
        var sz_Email = $('#email').val();
        var i_department = $('#department').val();
        var sz_code = $('#code').val();
        var sz_Pass = $('#password').val();
        var sz_Repass = $('#re_password').val();
        var i_HrmType = $('#hr_type').val();
        var i_user_id = 0;
        if($('#user_id').length) i_user_id = $('#user_id').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_code)){
            o_ErrorAlert.text('Bạn chưa nhập mã nhân viên'); $('#code').focus(); return false;
        }
        if(GLOBAL_JS.b_fValidateEmpty(sz_Name)){
            o_ErrorAlert.text('Bạn phải nhập tên đầy đủ'); $('#name').focus(); return false;
        }
        if(!GLOBAL_JS.b_fCheckMinLength(sz_Name, 3)){
            o_ErrorAlert.text('Tên đầy đủ ít nhất 3 ký tự'); $('#name').focus(); return false;
        }
        if(GLOBAL_JS.b_fValidateEmpty(sz_Email)){
            o_ErrorAlert.text('Bạn phải nhập Email'); $('#email').focus(); return false;
        }
        if(sz_Email.search("@") > 0)
        {
            if(!GLOBAL_JS.b_fCheckEmailDXMB(sz_Email)){
                o_ErrorAlert.text('Email không đúng định dạng! Cần nhập theo định dạng "abc@dxmb.vn" hoặc nhập "abc"'); $('#email').focus(); return false;
            }
        }
        if(i_department == 0){
            o_ErrorAlert.text('Bạn chưa chọn phòng ban'); $('#department').focus(); return false;
        }
        if(sz_Act == 'insert' || (sz_Act == 'edit' && (sz_Pass != '' || sz_Repass != '')))
        {
            if(GLOBAL_JS.b_fValidateEmpty(sz_Pass)){
	    	o_ErrorAlert.text('Bạn phải nhập mật khẩu'); $('#password').focus(); return false;
            }
            if(!GLOBAL_JS.b_fCheckMinLength(sz_Pass, 6)){
                o_ErrorAlert.text('Mật khẩu ít nhất 6 ký'); $('#password').focus(); return false;
            }
            if(GLOBAL_JS.b_fValidateEmpty(sz_Repass)){
                o_ErrorAlert.text('Bạn phải điền vào ô nhập lại mật khẩu'); $('#re_password').focus(); return false;
            }
            if(!GLOBAL_JS.b_fCheckConfirmPwd(sz_Pass, sz_Repass)){
                o_ErrorAlert.text('2 ô mật khẩu chưa trùng khớp'); $('#re_password').focus(); return false;
            }
        }

        var a_data = {
                sz_code:sz_code,
                sz_email:sz_Email,
                i_user_id: i_user_id,
                i_HrmType: i_HrmType,
                func:'validate-add-edit-user'
            };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                if(res.result)
                {
                    $('.submit').click();
                }
                else
                {
                    if(res.idFocus == 'hr_type')
                    {
                        if(!confirm('Hệ thống đã có 1 Hrm bạn có muốn thay đổi không?'))
                        {
                            o_ErrorAlert.text(res.mes); $('#'+res.idFocus).focus(); return false;
                        }
                        else $('.submit').click();
                    }
                    else o_ErrorAlert.text(res.mes); $('#'+res.idFocus).focus(); return false;
                }
            }
        });
    },

    /**
     * Auth: HuyNN
     * Des: Submit with Validate Form Leave Request
     * Since: 05/01/2015
     * */
    v_fSubmitLeaveRequest : function() {
        var o_ErrorAlert = $('#error');
        var sz_comment = $('#user_comment').val();
        if($('.go_business').hasClass('hide'))
        {
            var sz_FromTime = $('#from_time').val();
            var f_NumbLeave = $('#numb_leave').val();
            var sz_grub = $('#grub').val();
            if(GLOBAL_JS.b_fValidateEmpty(sz_FromTime)){
                o_ErrorAlert.text('Bạn phải điền ngày bắt đầu'); $('#from_time').focus(); return false;
            }
            if(f_NumbLeave == 0 || GLOBAL_JS.b_fValidateEmpty(f_NumbLeave)){
                o_ErrorAlert.text('Bạn phải chọn số ngày nghỉ'); $('#numb_leave').focus(); return false;
            }
            var a_data = {
                sz_FromTime: sz_FromTime,
                sz_grub: sz_grub,
                f_NumbLeave: f_NumbLeave,
                func:'check-duplicate-leave-request',
            };
        }
        else
        {
            var sz_FromTimeBusiness = $('#from_time_business').val();
            var sz_ToTimeBusiness = $('#to_time_business').val();

            if(GLOBAL_JS.b_fValidateEmpty(sz_FromTimeBusiness)){
                o_ErrorAlert.text('Bạn phải điền ngày bắt đầu nghỉ'); $('#from_time_business').focus(); return false;
            }
            if(GLOBAL_JS.b_fValidateEmpty(sz_ToTimeBusiness)){
                o_ErrorAlert.text('Bạn phải điền ngày kết thúc nghỉ'); $('#to_time_business').focus(); return false;
            }
            var a_data = {
                sz_FromTimeBusiness: sz_FromTimeBusiness,
                sz_ToTimeBusiness: sz_ToTimeBusiness,
                func:'check-duplicate-leave-request',
            };
        }

        if(GLOBAL_JS.b_fValidateEmpty(sz_comment)){
                o_ErrorAlert.text('Bạn phải điền comment'); $('#user_comment').focus(); return false;
            }
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                if(res.result)
                {
                    $('.submit').click();
                }
                else
                {
                    o_ErrorAlert.text(res.mes); return false;
                }
            }
        });
    },

    /**
     * Auth: Dienct
     * Des: Submit with Validate Form OT
     * Since: 18/11/2016
     * */
    v_fSubmitOverTime : function() {
        var o_ErrorAlert = $('#error');
        var sz_comment = $('#user_comment').val();

            var sz_FromTimeBusiness = $('#from_time_business').val();
            var sz_ToTimeBusiness = $('#to_time_business').val();

            if(GLOBAL_JS.b_fValidateEmpty(sz_FromTimeBusiness)){
                o_ErrorAlert.text('Bạn phải điền ngày bắt đầu nghỉ'); $('#from_time_business').focus(); return false;
            }
            if(GLOBAL_JS.b_fValidateEmpty(sz_ToTimeBusiness)){
                o_ErrorAlert.text('Bạn phải điền ngày kết thúc nghỉ'); $('#to_time_business').focus(); return false;
            }
            var a_data = {
                sz_FromTimeBusiness: sz_FromTimeBusiness,
                sz_ToTimeBusiness: sz_ToTimeBusiness,
                func:'check-duplicate-ot',
            };

        if(GLOBAL_JS.b_fValidateEmpty(sz_comment)){
            o_ErrorAlert.text('Bạn phải điền comment'); $('#user_comment').focus(); return false;
        }
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                if(res.result)
                {
                    $('.submit').click();
                }
                else
                {
                    o_ErrorAlert.text(res.mes); return false;
                }
            }
        });
    },


    /**
     * Auth: HuyNN
     * Des: Submit search Leave Request Report, deny search if not selected year
     * Since: 15/01/2016
     * */
    v_fSearchLeaveRequestReport : function() {
        var i_year = $('#search_year').val();
        var i_month = $('#search_month').val();
        if(i_month != 0 || i_year != 0)
        {
            if(i_month == 0 || i_year == 0)
            {
                alert('Bạn cần chọn cả năm và tháng để tiến hành tìm kiếm!');
                return false;
            }
        }
        $('.submit').click();
    },
    /**
     * Auth: HuyNN
     * Des: Submit search Users
     * Since: 20/01/2016
     * */
    v_fSearchSubmit : function() {
        var sz_search_by = $('#search_by').val();
        var sz_search_field = $('#search_field').val();
        if(sz_search_by != '' && sz_search_field =='')
        {
            alert('Bạn chưa nhập từ khóa!');
            return false;
        }
        if(sz_search_by == '' && sz_search_field !='')
        {
            alert('Bạn cần tìm kiếm theo tiêu chí nào đó (Mã nhân viên, email, code)');
            return false;
        }
        $('.submit').click();
    },

    /**
     * Auth: Luongnv
     * Des: Submit search All module
     * Since: 23/02/2016
     * */
    v_fSearchSubmitAll : function() {
        var sz_search_field = $('#search_field').val();
        $('.submit').click();
    },

    /**
     * Auth: HuyNN
     * Des: Load Department Groups
     * Since: 05/01/2015
     * */
    v_fLoadGroup : function() {
        var o_ErrorAlert = $('#error');
        var i_department = $('#department').val();

        var a_data = {
            i_department: i_department,
            func:'load-group',
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                var html="<option value='0'>Chọn nhóm trong phòng ban</option>";
                if(res.result)
                {
                    $.each(res.data,function(key,value)
                    {
                        html = html+"<option value='"+value.id+"'>"+value.name+"</option>";
                    });
                    $('#group').html(html);
                }
                else
                {
                   $('#group').html(html);
                }
            }
        });
    },

    /**
     * Auth: HuyNN
     * Des: Load Direct Manager
     * Since: 05/01/2015
     * */
    v_fLoadDirecManager : function() {
        var o_ErrorAlert = $('#error');
        var i_group_manager = $('#group_manager').val();
        var a_data = {
            i_group_manager: i_group_manager,
            func:'load-direct-manager',
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                var html="<option value='0'>Không có</option>";
                if(res.result)
                {
                    $.each(res.data,function(key,value)
                    {
                        html = html+"<option value='"+value.id+"'>"+value.name+"</option>";
                    });
                    $('#direct_manager').html(html);
                }
                else
                {
                   $('#direct_manager').html(html);
                }
            }
        });
    },
    /**
     * Auth: Dienct
     * Des: Load Direct Manager 2
     * Since: 27/12/2016
     * */
    v_fLoadDirecManager2 : function() {
        var o_ErrorAlert = $('#error');
        var i_group_manager = $('#group_manager2').val();
        var a_data = {
            i_group_manager: i_group_manager,
            func:'load-direct-manager',
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                var html="<option value='0'>Không có</option>";
                if(res.result)
                {
                    $.each(res.data,function(key,value)
                    {
                        html = html+"<option value='"+value.id+"'>"+value.name+"</option>";
                    });
                    $('#direct_manager2').html(html);
                }
                else
                {
                   $('#direct_manager2').html(html);
                }
            }
        });
    },

    /**
     * Auth: Dienct
     * Des: confirm leave request
     * Since: 7/1/2016
     * */
    v_fConfirmLeaveRequest : function(i_Id, type_confirm) {
        $(".confirm_request").click(function(){
            if(type_confirm == 0) $(".notice-cmt").hide();
            var comment = type_confirm == 0 ? $('#comment_reject').val() : $('#comment_allow').val();
            if(type_confirm == 0 && comment == ""){
                $(".notice-cmt").show();
                return;
            }
            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'allow-leave-request',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            alert(data.success);
                            location.reload();

                        }
            });
            GLOBAL_JS.v_fSendMail(i_Id, type_confirm,comment);
        });
    },
    //
    /**
     * Auth: Dienct
     * Des: HRM confirm OT
     * Since: 23/11/2016
     * */
    v_fHRMConfirmOT : function(i_Id, type_confirm) {
        $(".confirm_ot").click(function(){
            if(type_confirm == 0) $(".notice-cmt").hide();
            var comment = type_confirm == 0 ? $('#comment_reject').val() : $('#comment_allow').val();
            if(type_confirm == 0 && comment == ""){
                $(".notice-cmt").show();
                return;
            }
            var o_data = {
                id: i_Id,
                comment: comment,
                type_confirm:type_confirm,//0 reject; 1 allow;
                func:'approve-ot',
            };
            $.ajax({
                url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                type: 'POST',
                data: o_data,
                dataType: 'json',
                success: function (data) {
                    alert(data.success);
                    location.reload();

                }
            });
            GLOBAL_JS.v_fHRMSendMailOT(i_Id, type_confirm,comment);
        });
    },


    /**
     * Auth: Dienct
     * Des: confirm leave request
     * Since: 7/1/2016
     * */

    v_fSendMail : function(i_Id, type_confirm,comment) {
            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'send-mail',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            // not problem
                        }
            });
    },
    /**
     * Auth: Dienct
     * Des: HRM approve OT
     * Since: 23/11/2016
     * */

    v_fHRMSendMailOT : function(i_Id, type_confirm,comment) {
        var o_data = {
            id: i_Id,
            comment: comment,
            type_confirm:type_confirm,//0 reject; 1 allow;
            func:'hrm-send-mail-ot',
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
                // not problem
            }
        });
    },

    /**
     * Auth: Dienct
     * Des: chekc all role
     * Since: 13/1/2016
     * */
    v_fCheckAllRoleGroup : function(the_sz_Id) {
        if($('#'+the_sz_Id).is(':checked'))
            {
                $('.'+the_sz_Id).prop('checked', true);
            }
            else $('.'+the_sz_Id).prop('checked', false);
    },
    /**
     * Auth: Dienct
     * Des: Manager confirm leave request
     * Since: 8/1/2016
     * */
    v_fManagerConfirmLeaveRequest : function(i_Id, type_confirm) {
        $(".confirm_request").click(function(){
            if(type_confirm == 0) $(".notice-cmt").hide();
            var comment = type_confirm == 0 ? $('#comment_reject').val() : $('#comment_allow').val();
            if(type_confirm == 0 && comment == ""){
                $(".notice-cmt").show();
                return;
            }

            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'manager-confirm-leave-request',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            alert(data.success);
                            location.reload();
                        }
            });
            GLOBAL_JS.v_fManagerSendMail(i_Id, type_confirm,comment);
        });
    },

    /**
     * Auth: Dienct
     * Des: Manager confirm over time
     * Since: 22/11/2016
     * */
    v_fManagerConfirmOT : function(i_Id, type_confirm) {
        $(".confirm_ot").click(function(){
            if(type_confirm == 0) $(".notice-cmt").hide();
            var comment = type_confirm == 0 ? $('#comment_reject').val() : $('#comment_allow').val();
            if(type_confirm == 0 && comment == ""){
                $(".notice-cmt").show();
                return;
            }

            var o_data = {
                id: i_Id,
                comment: comment,
                type_confirm:type_confirm,//0 reject; 1 allow;
                func:'manager-confirm-ot',
            };
            $.ajax({
                url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                type: 'POST',
                data: o_data,
                dataType: 'json',
                success: function (data) {
                    alert(data.success);
                    location.reload();
                }
            });
            GLOBAL_JS.v_fManagerSendMailApproveOT(i_Id, type_confirm,comment);
        });
    },

    /**
     * Auth: HuyNN
     * Des: Reporter confirm Request Change Manager
     * Since: 8/1/2016
     * */
    v_fConfirmChangeManager : function(i_Id, type_confirm) {
        $(".confirm_request").click(function(){
            var comment = type_confirm == 0 ? $('#comment_reject').val() : $('#comment_allow').val();
            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'confirm-change-manager',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            if(data.result == 1) GLOBAL_JS.v_fReportSendMailChangeManager(i_Id, type_confirm,comment);
                            alert(data.success);
                            location.reload();
                        }
            });

        });
    },
    /**
     * Auth: Dienct
     * Des: send mail when manager approve OT
     * Since: 7/1/2016
     * */
    v_fManagerSendMailApproveOT : function(i_Id, type_confirm,comment) {

        var o_data = {
            id: i_Id,
            comment: comment,
            type_confirm:type_confirm,//0 reject; 1 allow;
            func:'manager-send-mail-ot',
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
                // not problem
            }
        });
    },



    /**
     * Auth: Dienct
     * Des: confirm leave request
     * Since: 7/1/2016
     * */
    v_fManagerSendMail : function(i_Id, type_confirm,comment) {

            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'manager-send-mail',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            // not problem
                        }
            });
    },

    /**
     * Auth: HuyNN
     * Des: Send Mail User after Reporter confirm Request Change Manager
     * Since: 15/03/2016
     * */
    v_fReportSendMailChangeManager : function(i_Id, type_confirm,comment) {
            var o_data = {
                        id: i_Id,
                        comment: comment,
                        type_confirm:type_confirm,//0 reject; 1 allow;
                        func:'confirm-change-manager-send-mail',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            // not problem
                    }
            });
    },



    /**
     * Auth: HuyNN
     * Des: Show time when change leave type
     * Since: 05/01/2015
     * */
    v_fChangeLeaveType : function(val) {
        var sz_type = $("#type_id option:selected").text();
        if(GLOBAL_JS.go_on_business == sz_type)
        {
            $('.none_go_business').addClass('hide');
            $('.none_go_business').find('#from_time').prop('disabled', true);
            $('.none_go_business').find('#grub').prop('disabled', true);
            $('.none_go_business').find('#numb_leave').prop('disabled', true);

            $('.go_business').removeClass('hide');
            $('.go_business').find('#from_time_business').prop('disabled', false);
            $('.go_business').find('#to_time_business').prop('disabled', false);

            $('#from_time').val('');
            $('.show_to_time').remove();
        }
        else
        {
            $('.none_go_business').removeClass('hide');
            $('.none_go_business').find('#from_time').prop('disabled', false);
            $('.none_go_business').find('#grub').prop('disabled', false);
            $('.none_go_business').find('#numb_leave').prop('disabled', false);

            $('.go_business').addClass('hide');
            $('.go_business').find('#from_time_business').prop('disabled', true);
            $('.go_business').find('#to_time_business').prop('disabled', true);

        }
    },

    /**
     * Auth: HuyNN
     * Des: Submit Jobs with validate
     * Since: 09/01/2015
     * */
    v_fSubmitJobsValidate : function()
    {
        var sz_name = $('#name').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_name)){
           $('.required_name').remove();
           $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên nghề nghiệp</strong></p>');
           $('.alert-danger').removeClass('hide');
           $('#name').focus();
           return false;
        }
        if($('.alert-danger').text() != '') return false;
        $('.submit').click();
    },
    /**
     * Auth: Dienct
     * Des: Submit Group with validate
     * Since: 11/01/2015
     * */
    v_fSubmitGroupValidate : function()
    {
        var sz_name = $('#name').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_name)){
           $('.required_name').remove();
           $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên nhóm</strong></p>');
           $('.alert-danger').removeClass('hide');
           $('#name').focus();
           return false;
        }
        if($('.alert-danger').text() != '') return false;
        $('.submit').click();
    },
    /**
     * Auth: Dienct
     * Des: Submit Department with validate
     * Since: 11/01/2015
     * */
    v_fSubmitDepartmentValidate : function()
    {
        var sz_name = $('#name').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_name)){
           $('.required_name').remove();
           $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên phòng</strong></p>');
           $('.alert-danger').removeClass('hide');
           $('#name').focus();
           return false;
        }
        if($('.alert-danger').text() != '') return false;
        $('.submit').click();
    },
    /*
    * Auth: Dienct
    * Des: Submit Form check point
    * Since: 28/10/2016
    * **/
    v_fAddCheckPoint : function()
    {

        total_point = $('#total_point').val();
        level_point = $('#level_point').val();

        var r = confirm("Xác nhận \nTổng điểm: "+total_point+" xếp loại :"+level_point);
        if (r == true) {
            $('#add-check-point').click();
        } else {
            return;
        }

    },

    /**
     * Auth: Dienct
     * Des: delete leave request when yet to confirm
     * Since: 30/01/2016
     * */
    v_fDeleteMyLeaveRequest : function(i_RequestId)
    {
        if(confirm("Bạn có chắc muốn xóa?")){
            $("#tr_"+i_RequestId).fadeOut();
            var o_data = {
                        id: i_RequestId,
                        func:'delete-my-leave-request',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            alert(data.mes);
                            location.reload();
                        }
            });
        }
    },
    /**
     * Auth: HuyNN
     * Des: Manager delete leave request
     * Since: 02/02/2016
     * */
    v_fManagerDelete : function(i_RequestId)
    {
        if(confirm("Bạn có chắc muốn xóa?")){
            var o_data = {
                        id: i_RequestId,
                        func:'manager-del-leave-request',
                    };
                    $.ajax({
                        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                        type: 'POST',
                        data: o_data,
                        dataType: 'json',
                        success: function (data) {
                            alert(data.mes);
                            if(data.result == 1) location.reload();
                        }
            });
        }
    },

    /**
     * Auth: HuyNN
     * Des: Submit Leave Types with validate
     * Since: 09/01/2015
     * */
    v_fSubmitLeaveTypesValidate : function()
    {
        var sz_name = $('#name').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_name)){
           $('.required_name').remove();
           $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên loại nghỉ phép</strong></p>');
           $('.alert-danger').removeClass('hide');
           $('#name').focus();
           return false;
        }
        if($('.alert-danger').text() != '') return false;
        $('.submit').click();
    },

    /**
     * Auth: HuyNN
     * Des: Submit Positions with validate
     * Since: 11/01/2015
     * */
    v_fSubmitPositionsValidate : function()
    {
        var sz_name = $('#name').val();
        if(GLOBAL_JS.b_fValidateEmpty(sz_name)){
           $('.required_name').remove();
           $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên chức vụ</strong></p>');
           $('.alert-danger').removeClass('hide');
           $('#name').focus();
           return false;
        }
        if($('.alert-danger').text() != '') return false;
        $('.submit').click();
    },

    v_fToggleLeftSide : function() {
        if($('#side-menu').hasClass('hide')){
                $('#side-menu').removeClass('hide');
        } else {
                $('#side-menu').addClass('hide');
        }
        if($('#page-wrapper').hasClass('no-margin')){
                $('#page-wrapper').removeClass('no-margin');
        } else {
                $('#page-wrapper').addClass('no-margin');
        }
    },

    v_fUpdateStatus : function(id,type) {
        var sz_confirm = type == 1 ? "Bạn có muốn cho vào thùng rác?" : "Bạn có muốn xóa đơn này?";
		if(confirm(sz_confirm)) {
                    var o_data = {
                        id: id,
                        type:type,
                        func:'update-status',
                        tbl: GLOBAL_JS.sz_Tbl,
                    };
			$.ajax({
                            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                            type: 'POST',
                            data: o_data,
                            dataType: 'json',
                            success: function (data) {
                                alert(data.success);
                                location.reload();

                            }
                        });
		}
	},
        v_fSubmitChangeManager : function() {
            var o_ErrorAlert = $('#error');
            var i_department = $('#group_manager').val();
            var i_manager = $('#direct_manager').val();

            if(i_department == 0){
                o_ErrorAlert.text('Bạn chưa chọn phòng ban'); $('#department').focus(); return false;
            }
            if(i_manager == 0){
                o_ErrorAlert.text('Bạn chưa chọn Quản lý trực tiếp'); $('#direct_manager').focus(); return false;
            }
            $('.submit').click();
        } ,

        v_fSubmitChangePassword : function() {
            var o_ErrorAlert = $('#error');
            var sz_old_pass = $('#old_password').val();
            var sz_new_pass = $('#password').val();
            var sz_re_new_pass = $('#re_password').val();

            if(GLOBAL_JS.b_fValidateEmpty(sz_old_pass)){
                o_ErrorAlert.text('Bạn phải nhập mật khẩu hiện tại của bạn'); $('#old_password').focus(); return false;
            }
            if(GLOBAL_JS.b_fValidateEmpty(sz_new_pass)){
                o_ErrorAlert.text('Bạn phải nhập mật khẩu mới'); $('#password').focus(); return false;
            }
            if(GLOBAL_JS.b_fValidateEmpty(sz_re_new_pass)){
                o_ErrorAlert.text('Bạn phải nhập lại mật khẩu mới'); $('#sz_re_new_pass').focus(); return false;
            }
            if(!GLOBAL_JS.b_fCheckMinLength(sz_new_pass, 6)){
                o_ErrorAlert.text('Mật khẩu mới phải ít nhất 6 ký tự'); $('#password').focus(); return false;
            }
            if(!GLOBAL_JS.b_fCheckConfirmPwd(sz_new_pass, sz_re_new_pass)){
                o_ErrorAlert.text('2 ô mật khẩu chưa trùng khớp'); $('#re_password').focus(); return false;
            }
            var a_data = {
                sz_old_pass:sz_old_pass,
                func:'validate-change-password'
            };
            $.ajax({
                url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                type: 'POST',
                data: a_data,
                dataType: 'json',
                success: function (res) {
                    if(res.result)
                    {
                        $('.submit').click();
                    }
                    else
                    {
                        o_ErrorAlert.text(res.mes);
                        $('#'+res.idFocus).focus();
                        return false;
                    }
                }
            });
        }

};

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var sz_alert = $('#alert').text();
    if(sz_alert !='') alert(sz_alert);
    ///Nếu làm 5,5 ngày 1 tuần thì off chủ nhật
    //console.log($('#numb_of_work').text());
    if(($('#numb_of_work').text() == '5.5') || ($('#numb_of_work').text() == '5.50'))
    {
        $('.datepicker').datepicker({
        dateFormat:'dd/mm/yy',
        timeFormat:'HH:mm',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date)
            {
                var day = date.getDay();
                return [(day != 0), ''];
            },
        });

        $('.datetimepicker').datetimepicker({
            dateFormat:'dd/mm/yy',
            timeFormat:'HH:mm',
            showSecond:false,
            showMillisec:false,
            showMicrosec:false,
            showTimezone:false,
            changeMonth: true,
            changeYear: true,
            timeInput:true,
            beforeShowDay: function(date) {
                var enable_sunday = $('#enable_sunday').text();
                var day = date.getDay();
                ///Nếu cho phép mở ngày chủ nhật => mở hết tất cả các ngày
                if(enable_sunday == 1) return [(day != 10), ''];
                /// Ngược lại thì off cn
                else  return [(day != 0), ''];
            },
            onSelect: function(date)
            {
                if($('#to_time_business').val() != "")
                {
                    var maxDate = $('#to_time_business').val();
                    $('#from_time_business').datetimepicker('option','maxDate',maxDate)
                }
                if($('#from_time_business').val() != "")
                {
                    var minDate = $('#from_time_business').val();
                    $('#to_time_business').datetimepicker('option','minDate',minDate)
                }
            }
        });
    }
    // Nếu làm 5 ngày 1 tuần off cả t7 +cn
    else
    {
        $('.datepicker').datepicker({
        dateFormat:'dd/mm/yy',
        timeFormat:'HH:mm',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: $.datepicker.noWeekends,
        });

        $('.datetimepicker').datetimepicker({
            dateFormat:'dd/mm/yy',
            timeFormat:'HH:mm',
            showSecond:false,
            showMillisec:false,
            showMicrosec:false,
            showTimezone:false,
            changeMonth: true,
            changeYear: true,
            timeInput:true,
            beforeShowDay: function(date)
            {
                var enable_sunday = $('#enable_sunday').text();
                var day = date.getDay();
                ///Nếu cho phép mở ngày chủ nhật
                if(enable_sunday == 1) return [(day != 6), ''];
                /// Ngược lại thì off t7 + cn
                else  return [(day != 6 && day != 0), ''];

                    //return $.datepicker.noWeekends;
            },
//            beforeShowDay: $.datepicker.noWeekends,
            onSelect: function(date)
            {
                if($('#to_time_business').val() != "")
                {
                    var maxDate = $('#to_time_business').val();
                    $('#from_time_business').datetimepicker('option','maxDate',maxDate)
                }
                if($('#from_time_business').val() != "")
                {
                    var minDate = $('#from_time_business').val();
                    $('#to_time_business').datetimepicker('option','minDate',minDate)
                }
            }
        });
    }


    $(".get_to_time").on("change", function(){
        var o_ErrorAlert = $('#error');
        if($('.go_business').hasClass('hide'))
        {
            var sz_FromTime = $('#from_time').val();
            var sz_grub = $('#grub').val();
            var f_numb_leave = $('#numb_leave').val();
            var a_data = {
                sz_FromTime: sz_FromTime,
                sz_grub: sz_grub,
                f_numb_leave: f_numb_leave,
                func:'get-to-time',
            };
        }
        else
        {
            var sz_FromTimeBusiness = $('#from_time_business').val();
            var sz_ToTimeBusiness = $('#to_time_business').val();
            var a_data = {
                sz_FromTimeBusiness: sz_FromTimeBusiness,
                sz_ToTimeBusiness: sz_ToTimeBusiness,
                func:'get-to-time',
            };
        }
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: a_data,
            dataType: 'json',
            success: function (res) {
                if(res.result == 0)
                {
                    o_ErrorAlert.text(res.mes);
                }
                else
                {
                    //o_ErrorAlert.text('');
                    $('.show_to_time').remove();
                    $('#numb_leave').closest('.none_go_business').after('<div class="form-group show_to_time"><div class="col-xs-12 col-sm-6 no-padding"><label class="col-xs-12 col-sm-6 control-label text-left">Ngày đi làm</label><div class="col-xs-12 col-sm-6 no-padding"><input type="text" class="form-control" disabled value="'+res.a_res.sz_to_time+'"><input type="text" class="form-control hide" name="data[to_time]" value="'+res.a_res.sz_to_time_db+'"></div></div><div class="col-xs-12 col-sm-4 no-padding"><label for="" class="col-xs-12 col-sm-6 control-label text-left">Thời gian</label><div class="col-xs-12 col-sm-6 no-padding"><select class="form-control input-sm" disabled><option>'+res.a_res.sz_grub_end+'</option></select></div></div></div>');
                    if(res.a_res.sz_CheckDayFrom == 'Saturday')  $('#evening').addClass('hide');
                    else $('#evening').removeClass('hide');
                }

            }
        });
    });

    var t = $(location).attr("href");
    GLOBAL_JS.sz_CurrentHost = t.split("/")[0] + "//" + t.split("/")[2];
    GLOBAL_JS.sz_Tbl = $('#tbl').val();

    //HRM confirm leave request
    $('.hrm-allow').click(function(){
        $(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fConfirmLeaveRequest(i_Id, 1);
    });
    $('.hrm-reject').click(function(){
        $(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fConfirmLeaveRequest(i_Id, 0);
    });

    //HRM confirm leave request
    $('.manager-allow').click(function(){
        $(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fManagerConfirmLeaveRequest(i_Id, 1);
    });
    $('.manager-reject').click(function(){
        $(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fManagerConfirmLeaveRequest(i_Id, 0);
    });
    /**
     * manager approve OT
     * */
    $('.manager-allow-ot').click(function(){
        $(".confirm_ot").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fManagerConfirmOT(i_Id, 1);
    });
    $('.manager-reject-ot').click(function(){
        $(".confirm_ot").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fManagerConfirmOT(i_Id, 0);
    });

    //HRM approve OT
    $('.hrm-allow-ot').click(function(){
        $(".confirm_ot").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fHRMConfirmOT(i_Id, 1);
    });
    $('.hrm-reject-ot').click(function(){
        $(".confirm_ot").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fHRMConfirmOT(i_Id, 0);
    });

    //// Confirm Request change Manager///
    $('.allow-change-manager ').click(function(){
        //$(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fConfirmChangeManager(i_Id, 1);
    });
    $('.reject-change-manager').click(function(){
        //$(".confirm_request").off("click");
        var i_Id = $(this).attr('id').replace('confirm_','');
        GLOBAL_JS.v_fConfirmChangeManager(i_Id, 0);
    });


    ///Check Duplicate field/////////
    $('.check-duplicate').on("change", function(){
        var call_ajax = 0;
        var field = $(this).attr('id');
        var val = $(this).val();

        if(val == '') return false;
        else $('.required_'+field).remove();

        var old_val =  $(this).attr('old_val');

        if (typeof old_val !== typeof undefined && old_val !== false)
        {
            if(val != old_val)
            {
                call_ajax = 1;
            }
        }
        else
        {

            old_val = '';
            call_ajax = 1;
        }

        if(call_ajax == 1)
        {
            var me = this;
            var field_name = $(this).attr('field-name');

            var o_data = {
            val: val,
            old_val: old_val,
            tbl: GLOBAL_JS.tbl,
            field: field,
            func:'check-duplicate-field',
            };
                $.ajax({
                url: GLOBAL_JS.sz_CurrentHost+'/ajax',
                type: 'POST',
                data: o_data,
                dataType: 'json',
                success: function (res) {
                    if(res.result == 0)
                    {
                       $('.alert-danger .duplicate_'+field).remove();
                       $('.alert-danger').append('<p class="duplicate_'+field+'"><strong>'+field_name+' đã tồn tại. Xin vui lòng kiểm tra lại</strong></p>');
                       $('.alert-danger').removeClass('hide');
                       $(me).focus();
                    }
                    else
                    {
                        $('.alert-danger .duplicate_'+field).remove();
                        if($('.alert-danger').text() == '') $('.alert-danger').addClass('hide');
                    }
                }
            });
        }
    });


    $( ".chosse-check-point" ).change(function() {
        var work_quality = $('input[name=work_quality]:checked').val() != undefined ? $('input[name=work_quality]:checked').val() : 0;
        var progress = $('input[name=progress]:checked').val() != undefined ? $('input[name=progress]:checked').val() : 0 ;
        var exploit_customer = $('input[name=exploit_customer]:checked').val() != undefined ? $('input[name=exploit_customer]:checked').val() : 0;
        var revenue = $('input[name=revenue]:checked').val() != undefined ? $('input[name=revenue]:checked').val() : 0 ;
        var report_week = $('input[name=report_week]:checked').val()  != undefined ? $('input[name=report_week]:checked').val() : 0;
        var morale = $('input[name=morale]:checked').val();
        var connect = $('input[name=connect]:checked').val();
        var cultural = $('input[name=cultural]:checked').val();
        var discipline = $('input[name=discipline]:checked').val();
        discipline = discipline != undefined ? discipline : 0;
        /*
        var totalError = $('#totalError').val();
        if(totalError > 2)*/

        var pointError =  0;

        total_point = parseFloat(work_quality) + parseFloat(progress) +parseFloat(exploit_customer) + parseFloat(revenue)
            +parseFloat(report_week) +parseFloat(morale) +parseFloat(connect) +parseFloat(cultural)+parseFloat(discipline) - parseFloat(pointError);
        total_point= total_point.toFixed(2);
        var level;
        if(total_point >= 4.5) level = 'A';
        else if(4 <= total_point && total_point < 4.5) level = 'B';
        else if(3 <= total_point && total_point < 4) level = 'C';
        else if(2 <= total_point && total_point < 3) level = 'D';
        else if(total_point < 2) level = 'E';
        $('.level-point').html(level);
        $('.total-point').html(total_point);

        $('#total_point').val(total_point);
        $('#level_point').val(level);

    });




    $('.check_enough').on("click",function(){
        var $this = $(this);
        var i_user_id = $(this).attr('user_id');
        var i_date = $(this).attr('date');
        var i_month = $(this).attr('month');
        var i_year = $(this).attr('year');

        if($(this).hasClass('fa-square-o'))
        {
            var i_stt = 1;
        }
        else {
            var i_stt = 0;
        }
        var o_data = {
            user_id: i_user_id,
            field: i_date,
            month: i_month,
            year: i_year,
            val: i_stt,
            tbl: GLOBAL_JS.tbl,
            func:'update_stt_leave_request_report',
        };
        $.ajax({
        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
        type: 'POST',
        data: o_data,
        dataType: 'json',
        success: function (res) {
            if(res.result == 0)
            {
               alert(res.success);
            }
            else
            {
                if(i_stt == 1)
                {
                    $this.removeClass('fa-square-o').addClass('fa-check-square-o');
                    $this.closest('td').css('background-color','yellow');
                }
                else
                {
                    $this.removeClass('fa-check-square-o').addClass('fa-square-o');
                    $this.closest('td').css('background-color','transparent');
                }
            }
        }
    });

});


    ////Send Multi mail for user - Modul HRM confirm - Check Multi Leave Request////
    if (typeof a_SendMultiEmail !== 'undefined') {
        var o_data = {
            a_Data: a_SendMultiEmail,
            func:'send_multi_mail_hrm',
        };

        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
            }
        });
    };

    if (typeof a_SendMultiEmailOT !== 'undefined') {
        var o_data = {
            a_Data: a_SendMultiEmailOT,
            func:'send_multi_mail_OT_hrm',
        };

        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost+'/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
            }
        });
    };
    //////////////////////End///////////////////////////////

});

$('#submit_login').on("click", function(event) {
    var str_email = $('#login_email').val();
    if(str_email != '')
    {
        if(str_email.indexOf('@dxmb.vn') == -1){
            str_email += '@dxmb.vn';
        }
        $('#login_email').val(str_email);
    }
});

$('#update_user').click(function(e){
    var sz_confirm = "Bạn có muốn đồng bộ User từ Mail Server về Izi?";
        if(!confirm(sz_confirm)) {
            e.preventDefault();
    }
});

$(".video-tutorial").on("click", function() {
	   $('#myModal').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
	   $('#myModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
});

$('.allow-checkpoint').on("click",function(){
    var checkpointId = $(this).attr('data-id');
    var o_data = {
        checkpointId: checkpointId,
        func:'update-checkpoint',
    };
    $.ajax({
        url: GLOBAL_JS.sz_CurrentHost+'/ajax',
        type: 'POST',
        data: o_data,
        dataType: 'json',
        success: function (res) {
            alert(res.success);
            window.location = window.location.href;
        }
    });
});

//////////////////Check hoặc UnCheck tất cả checkbox////////////////////////
    $('#check_all').click(function(event)
    {
        if(this.checked) {
            $('.chk_item').each(function() {
                this.checked = true;
                $(this).closest("tr").addClass("tr_check");
            });
        }else{
            $('.chk_item').each(function() {
                this.checked = false;
                $(this).closest("tr").removeClass("tr_check");
            });
        }
    });

    /////////////////Khi bỏ chọn bất kỳ một checkbox nào thì bỏ chọn luôn checkAll////////////////
    $('.chk_item').click(function()
    {
        if (this.checked)
        {
            $(this).closest("tr").addClass("tr_check");
            var no_check=0;
            $('.chk_item').each(function(){
                    if(this.checked==false) no_check=no_check+1;
            });
            if(no_check==0)
            {
                    $('.checkAll').each(function(){this.checked = true;});
            }
        }
        else
        {
            $(this).closest("tr").removeClass("tr_check");
            $('.checkAll').each(function(){this.checked = false;});
        }
    });

    $('.check-check-all').click(function(event)
    {
        var check = 0;

        $('.chk_item').each(function(){
            if($(this).is(':checked'))
            {
                check = 1;
            }
        });

        if(check == 0)
        {
            alert('Bạn chưa tích chọn dòng nào!');
            event.preventDefault();
        }
    });

$(document).ready(function () {
    $(".video-tutorial").click(function (e) {
        e.preventDefault();
        $("#frame").attr("src", $(this).attr("href"));
    })

    $(".fa-check-square-o").closest('td').css('background-color','yellow');

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip('show');


})
