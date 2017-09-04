<?php $__env->startSection('content'); ?>

    <h3 class="col-xs-12 no-padding">Đánh giá hiệu quả làm việc tháng</h3>
    <div class="alert alert-danger hide"></div>
    <form class="form-horizontal" method="post" action="">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" id="id" value="<?php echo $i_id?>">
        <input type="hidden" id="totalError" name="totalError" value="0">
        <div class="form-group">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>NỘI DUNG ĐÁNH GIÁ</th>
                    <th>Điểm</th>
                    <th>Điểm quy đổi</th>
                    <th>Chọn</th>
                </tr>
                </thead>
                <tbody>
                <?php if($job_id == 2): ?>
                    <tr class="bg-success">
                        <th><b class="text-primary"><b>Chất lượng công việc</b></th>
                        <th class="text-danger">40%</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Kết quả công việc không như mong đợi</th>
                        <th>0%</th>
                        <th>0</th>
                        <th><input class="chosse-check-point" type="radio" name="work_quality" <?php echo (isset($o_checkpoint) && $o_checkpoint->work_quality == '0' ? 'checked' :'')?> value="0" checked></th>
                    </tr>
                    <tr>
                        <th>Kết quả công việc chấp nhận được, chưa chuyên nghiệp, chưa có sáng tạo trong quá trình làm việc.</th>
                        <th>10%</th>
                        <th>0,5</th>
                        <th><input class="chosse-check-point" type="radio" name="work_quality" <?php echo (isset($o_checkpoint) && $o_checkpoint->work_quality == '0.5' ? 'checked' :'')?> value="0.5" ></th>
                    </tr>
                    <tr>
                        <th>Kết quả làm việc khá, làm việc theo chỉ đạo cấp trên, có sáng tạo nhưng chưa nhiều, có trách nhiệm cao.</th>
                        <th>20%</th>
                        <th>1</th>
                        <th><input class="chosse-check-point" type="radio" name="work_quality" <?php echo (isset($o_checkpoint) && $o_checkpoint->work_quality == '1' ? 'checked' :'')?> value="1" ></th>
                    </tr>
                    <tr>
                        <th>Kết quả làm việc tốt, có sáng tạo trong công việc, trách nhiệm cao.</th>
                        <th>30%</th>
                        <th>1,5</th>
                        <th><input class="chosse-check-point" type="radio" name="work_quality" <?php echo (isset($o_checkpoint) && $o_checkpoint->work_quality == '1.5' ? 'checked' :'')?> value="1.5"></th>
                    </tr>
                    <tr>
                        <th>Kết quả làm việc vượt xa mong đợi, luôn luôn sáng tạo để đạt hiệu quả cao nhất cho công ty.</th>
                        <th>40%</th>
                        <th>2</th>
                        <th><input class="chosse-check-point" type="radio" name="work_quality" <?php echo (isset($o_checkpoint) && $o_checkpoint->work_quality == '2' ? 'checked' :'')?> value="2"></th>
                    </tr>
                    <tr class="bg-success">
                        <th><b class="text-primary"><b>Tiến độ thực hiện công việc</b></th>
                        <th class="text-danger"></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Thường xuyên chậm so với kế hoạch: đạt dưới 50%</th>
                        <th>0%</th>
                        <th>0</th>
                        <th><input class="chosse-check-point" type="radio" name="progress" <?php echo (isset($o_checkpoint) && $o_checkpoint->progress == '0' ? 'checked' :'')?> value="0" checked></th>
                    </tr>
                    <tr>
                        <th>Thỉnh thoảng chậm: đạt 50-99% công việc được giao</th>
                        <th>20%</th>
                        <th>1</th>
                        <th><input class="chosse-check-point" type="radio" name="progress" <?php echo (isset($o_checkpoint) && $o_checkpoint->progress == '1' ? 'checked' :'')?> value="1"></th>
                    </tr>
                    <tr>
                        <th>Nhanh: hoàn thành 100% công việc được giao</th>
                        <th>30%</th>
                        <th>1,5</th>
                        <th><input class="chosse-check-point" type="radio" name="progress" <?php echo (isset($o_checkpoint) && $o_checkpoint->progress == '1.5' ? 'checked' :'')?> value="1.5"></th>
                    </tr>
                    <tr>
                        <th>Luôn luôn hoàn thành tất cả các công việc trước thời hạn, dành thời gian hỗ <br/> trợ các phòng ban khác để công việc thuận lợi hơn.</th>
                        <th>40%</th>
                        <th>2</th>
                        <th><input class="chosse-check-point" type="radio" name="progress" <?php echo (isset($o_checkpoint) && $o_checkpoint->progress == '2' ? 'checked' :'')?> value="2"></th>
                    </tr>
                <?php endif; ?>
                <?php if($job_id != 2): ?>
                    <tr>
                        <th><b class="text-primary"><b>Khối lượng cộng việc</b></th>
                        <th class="text-danger">80%</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr class="bg-success">
                        <th>1. Chỉ tiêu khai thác khách hàng</th>
                        <th>20%</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>* Không đạt chỉ tiêu</th>
                        <th>0%</th>
                        <th>0</th>
                        <th><input class="chosse-check-point" type="radio" name="exploit_customer" <?php echo (isset($o_checkpoint) && $o_checkpoint->exploit_customer == '0' ? 'checked' :'')?> value="0" checked></th>
                    </tr>
                    <tr>
                        <th>* Đạt dưới 50% chỉ tiêu
                        </th>
                        <th>5%</th>
                        <th>0,25</th>
                        <th><input class="chosse-check-point" type="radio" name="exploit_customer" <?php echo (isset($o_checkpoint) && $o_checkpoint->exploit_customer == '0.25' ? 'checked' :'')?> value="0.25" ></th>
                    </tr>

                    <tr>
                        <th>* Đạt 51-100% chỉ tiêu
                        </th>
                        <th>10%</th>
                        <th>0,5</th>
                        <th><input class="chosse-check-point" type="radio" name="exploit_customer" <?php echo (isset($o_checkpoint) && $o_checkpoint->exploit_customer == '0.5' ? 'checked' :'')?> value="0.5" ></th>
                    </tr>
                    <tr>
                        <th>* Đạt 101% -150% chỉ tiêu
                        </th>
                        <th>15%</th>
                        <th>0,75</th>
                        <th><input class="chosse-check-point" type="radio" name="exploit_customer" <?php echo (isset($o_checkpoint) && $o_checkpoint->exploit_customer == '0.75' ? 'checked' :'')?> value="0.75" ></th>
                    </tr>
                    <tr>
                        <th>* Đạt 151% chỉ tiêu trở lên
                        </th>
                        <th>20%</th>
                        <th>1</th>
                        <th><input class="chosse-check-point" type="radio" name="exploit_customer" <?php echo (isset($o_checkpoint) && $o_checkpoint->exploit_customer == '1' ? 'checked' :'')?> value="1" ></th>
                    </tr>
                    <tr class="bg-success">
                        <th>2. Chỉ tiêu doanh thu</th>
                        <th>50%</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>* Không đạt chỉ tiêu</th>
                        <th>0%</th>
                        <th>0</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '0' ? 'checked' :'')?> value="0" checked></th>
                    </tr>
                    <tr>
                        <th>* Đạt dưới 50% chỉ tiêu</th>
                        <th>10%</th>
                        <th>0,5</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '0.5' ? 'checked' :'')?> value="0.5" ></th>
                    </tr>
                    <tr>
                        <th>* Đạt 51%-99% chỉ tiêu</th>
                        <th>20%</th>
                        <th>1</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '1' ? 'checked' :'')?> value="1" ></th>
                    </tr>
                    <tr>
                        <th><?php if($job_id == 3): ?> * Đạt 100%-150% chỉ tiêu <?php else: ?> * Đạt trên 91%-120% chỉ tiêu <?php endif; ?></th>
                        <th>30%</th>
                        <th>1,5</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '1.5' ? 'checked' :'')?> value="1.5" ></th>
                    </tr>
                    <tr>
                        <th><?php if($job_id == 3): ?> * Đạt 151% -200% chỉ tiêu <?php else: ?> * Đạt trên 121% -150% chỉ tiêu <?php endif; ?></th>
                        <th>40%</th>
                        <th>2</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '2' ? 'checked' :'')?> value="2" ></th>
                    </tr>
                    <tr>
                        <th><?php if($job_id == 3): ?> * Đạt 201% chỉ tiêu trở lên <?php else: ?> * Đạt 151% chỉ tiêu trở lên <?php endif; ?></th>
                        <th>50%</th>
                        <th>2,5</th>
                        <th><input class="chosse-check-point" type="radio" name="revenue" <?php echo (isset($o_checkpoint) && $o_checkpoint->revenue == '2.5' ? 'checked' :'')?> value="2.5" ></th>
                    </tr>
                    <!--bao cao tuan-->
                    <tr class="bg-success">
                        <th>3. Báo cáo tuần</th>
                        <th>10%</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>* Không báo cáo</th>
                        <th>0%</th>
                        <th>0</th>
                        <th><input class="chosse-check-point" type="radio" name="report_week" <?php echo (isset($o_checkpoint) && $o_checkpoint->report_week == '0' ? 'checked' :'')?> value="0" checked></th>
                    </tr>
                    <tr>
                        <th>* báo cáo sơ sài, chậm nộp, báo cáo sai…</th>
                        <th>2%</th>
                        <th>0,1</th>
                        <th><input class="chosse-check-point" type="radio" name="report_week" <?php echo (isset($o_checkpoint) && $o_checkpoint->report_week == '0.1' ? 'checked' :'')?> value="0.1" ></th>
                    </tr>
                    <tr>
                        <th>* Báo cáo đủ, chuẩn, đúng thời hạn</th>
                        <th>10%</th>
                        <th>0,5</th>
                        <th><input class="chosse-check-point" type="radio" name="report_week" <?php echo (isset($o_checkpoint) && $o_checkpoint->report_week == '0.5' ? 'checked' :'')?> value="0.5" ></th>
                    </tr>


                <?php endif; ?>

                <?php /*Tinh thần, thái độ và tác phong làm việc*/ ?>
                <tr class="bg-success">
                    <th><b>Tinh thần, thái độ và tác phong làm việc </b></th>
                    <th class="text-danger">5%</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Kém <br/> (Tác phong làm việc chậm chạp, chưa hiệu quả)</th>
                    <th>0%</th>
                    <th>0</th>
                    <th><input class="chosse-check-point" type="radio" name="morale" value="0" checked></th>
                </tr>
                <tr>
                    <th>Trung bình  <br/> (Tác phong làm việc thiếu linh hoạt, chưa chuyên nghiệp tuy nhiên có trách nhiệm đối với công việc)</th>
                    <th>1%</th>
                    <th>0,05</th>
                    <th><input class="chosse-check-point" type="radio" name="morale" <?php echo (isset($o_checkpoint) && $o_checkpoint->morale == '0.05' ? 'checked' :'')?> value="0.05" ></th>
                </tr>
                <tr>
                    <th>Đạt yêu cầu <br/> (Khá chủ động, có trách nhiệm trong công việc, tuân thủ chỉ đạo của cấp trên)</th>
                    <th>2%</th>
                    <th>0,1</th>
                    <th><input class="chosse-check-point" type="radio" name="morale" <?php echo (isset($o_checkpoint) && $o_checkpoint->morale == '0.1' ? 'checked' :'')?> value="0.1" ></th>
                </tr>
                <tr>
                    <th>Tốt <br/> (Thực hiện công việc tương đối khoa học, hợp lý, tác phong nhanh nhẹn, thái độ làm việc <br/>tích cực, có trách nhiệm với công việc, tuân thủ chỉ đạo cấp trên)</th>
                    <th>3%</th>
                    <th>0,15</th>
                    <th><input class="chosse-check-point" type="radio" name="morale" <?php echo (isset($o_checkpoint) && $o_checkpoint->morale == '0.15' ? 'checked' :'')?> value="0.15" ></th>
                </tr>
                <tr>
                    <th>Xuất sắc <br/> (Phong cách làm việc chuyên nghiệp, nhanh nhẹn, linh hoạt, chủ động, cầu thị trong công<br/> việc. Tinh thần hăng say, nhiệt tình, ý thức trách nhiệm cao đối với công việc)</th>
                    <th>5%</th>
                    <th>0,25</th>
                    <th><input class="chosse-check-point" type="radio" name="morale" <?php echo (isset($o_checkpoint) && $o_checkpoint->morale == '0.25' ? 'checked' :'')?> value="0.25" ></th>
                </tr>
                <?php /*Phối kết hợp*/ ?>
                <tr class="bg-success">
                    <th><b>Phối kết hợp</b></th>
                    <th class="text-danger">5%</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Không có năng lực phối kết hợp <br/>(Làm việc theo tinh thần cá nhân, cho riêng mình)</th>
                    <th>0%</th>
                    <th>0</th>
                    <th><input class="chosse-check-point" type="radio" name="connect" <?php echo (isset($o_checkpoint) && $o_checkpoint->connect == '0' ? 'checked' :'')?> value="0" checked></th>
                </tr>
                <tr>
                    <th>Cơ bản<br/>(Có nhận thức là thành viên của tổ chức, nhưng chưa chủ động làm việc phối hợp trong tổ chức)</th>
                    <th>1%</th>
                    <th>0,05</th>
                    <th><input class="chosse-check-point" type="radio" name="connect" <?php echo (isset($o_checkpoint) && $o_checkpoint->connect == '0.05' ? 'checked' :'')?> value="0.05" ></th>
                </tr>
                <tr>
                    <th>Đạt yêu cầu<br/>(Có sự phối hợp, lắng nghe và hợp tác có hiệu quả với đồng nghiệp)</th>
                    <th>2%</th>
                    <th>0,1</th>
                    <th><input class="chosse-check-point" type="radio" name="connect" <?php echo (isset($o_checkpoint) && $o_checkpoint->connect == '0.1' ? 'checked' :'')?> value="0.1" ></th>
                </tr>
                <tr>
                    <th>Tốt<br/>(Tạo sự liên kết, hỗ trợ nhau có khả năng giải quyết mâu thuẫn trong tổ chức)</th>
                    <th>3%</th>
                    <th>0,15</th>
                    <th><input class="chosse-check-point" type="radio" name="connect" <?php echo (isset($o_checkpoint) && $o_checkpoint->connect == '0.15' ? 'checked' :'')?> value="0.15" ></th>
                </tr>
                <tr>
                    <th>Xuất sắc<br/>(Dự đoán, giải quyết được những khó khăn, mâu thuẫn trong tổ chức, giúp hoàn thành chỉ tiêu của tổ chức và chỉ tiêu cá nhân)</th>
                    <th>5%</th>
                    <th>0,25</th>
                    <th><input class="chosse-check-point" type="radio" name="connect" <?php echo (isset($o_checkpoint) && $o_checkpoint->connect == '0.25' ? 'checked' :'')?> value="0.25" ></th>
                </tr>
                <?php /*Van hoa giao tiep*/ ?>
                <tr class="bg-success">
                    <th><b>Văn hóa, Giao tiếp</b></th>
                    <th class="text-danger">5%</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Yếu<br/>(Thiếu tôn trọng các chuẩn mực, giá trị văn hóa, giao tiếp của Đất Xanh)</th>
                    <th>0%</th>
                    <th>0</th>
                    <th><input class="chosse-check-point" type="radio" name="cultural" <?php echo (isset($o_checkpoint) && $o_checkpoint->cultural == '0' ? 'checked' :'')?> value="0" checked></th>
                </tr>
                <tr>
                    <th>Cơ bản<br/>(Ý thức được các chuẩn mực, giá trị văn hóa, giao tiếp của Đất Xanh)</th>
                    <th>1%</th>
                    <th>0,05</th>
                    <th><input class="chosse-check-point" type="radio" name="cultural" <?php echo (isset($o_checkpoint) && $o_checkpoint->cultural == '0.05' ? 'checked' :'')?> value="0.05" ></th>
                </tr>
                <tr>
                    <th>Đạt yêu cầu<br/>(Hành động theo các chuẩn mực, giá trị văn hóa, giao tiếp của Đất Xanh)</th>
                    <th>2%</th>
                    <th>0,1</th>
                    <th><input class="chosse-check-point" type="radio" name="cultural" <?php echo (isset($o_checkpoint) && $o_checkpoint->cultural == '0.1' ? 'checked' :'')?> value="0.1" ></th>
                </tr>
                <tr>
                    <th>Tốt<br/>(Giúp đỡ, khuyến khích đồng nghiệp ý thức và hành động theo các chuẩn mực, giá trị văn hóa, giao tiếp của Đất Xanh)</th>
                    <th>3%</th>
                    <th>0,15</th>
                    <th><input class="chosse-check-point" type="radio" name="cultural" <?php echo (isset($o_checkpoint) && $o_checkpoint->cultural == '0.15' ? 'checked' :'')?> value="0.15" ></th>
                </tr>
                <tr>
                    <th>Xuất sắc<br/>(Góp phần xây dựng và phát triển các chuẩn mực, giá trị mới phù hợp với chiến lược phát triển của Đất Xanh)</th>
                    <th>5%</th>
                    <th>0,25</th>
                    <th><input class="chosse-check-point" type="radio" name="cultural" <?php echo (isset($o_checkpoint) && $o_checkpoint->cultural == '0.25' ? 'checked' :'')?> value="0.25" ></th>
                </tr>
                <?php /*Ky luat*/ ?>
                <tr class="bg-success">
                    <th><b>Ý thức chấp hành kỷ luật</b></th>
                    <th class="text-danger">5%</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Không có vi phạm</th>
                    <th>2%</th>
                    <th>0,1</th>
                    <th><input class="chosse-check-point" type="radio" name="discipline" <?php echo (isset($o_checkpoint) && $o_checkpoint->discipline == '0.1' ? 'checked' :'')?> value="0.1" ></th>
                </tr>
                <tr>
                    <th>Gương mẫu chấp hành <br/>Không có vi phạm, tích cực góp ý nhắc nhở đồng nghiệp thực hiện tốt NQLĐ</th>
                    <th>5%</th>
                    <th>0,25</th>
                    <th><input class="chosse-check-point" type="radio" name="discipline" <?php echo (isset($o_checkpoint) && $o_checkpoint->discipline == '0.25' ? 'checked' :'')?> value="0.25" ></th>
                </tr>

                <tr class="bg-danger">
                    <th> Nếu anh/chị có vi phạm lỗi trong tháng thì điểm mục 5 sẽ thay đổi <br>tùy theo số lỗi bạn vi phạm <br> 1 lỗi trừ 0,1 điểm</th>
                    <th class="text-danger"></th>
                    <th></th>
                    <th></th>
                </tr>
                </tbody>
            </table>

            <?php /*tong diem && xep loai*/ ?>
            <div class="col-xs-12 col-sm-3">
                <span class="text-primary"><b>Tổng điểm: </b></span><span class="text-danger total-point"><?php echo (isset($o_checkpoint)? $o_checkpoint->total_point :'0')?></span>
            </div>
            <div class="col-xs-12 col-sm-3">
                <span class="text-primary"><b>Xếp loại: </b></span><span class="text-danger level-point"><?php echo (isset($o_checkpoint)? $o_checkpoint->level_point :'E')?></span>
            </div>
        </div>
        <input type="hidden" id="total_point" name="total_point" value="<?php echo (isset($o_checkpoint)? $o_checkpoint->total_point :'0')?>">
        <input type="hidden" id="level_point" name="level_point" value="<?php echo (isset($o_checkpoint)? $o_checkpoint->level_point :'E')?>">
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="comment" class="col-xs-12 col-sm-3 control-label text-left">Ý kiến của người đánh giá</label>
                <div class="col-xs-12 col-sm-9 no-padding">
                    <textarea class="form-control" rows="3" id="comment" name="comment" <?php echo (isset($o_checkpoint) && ($o_checkpoint->user_id != Auth::user()->id || $o_checkpoint->status != 1)) ? 'disabled' : ''?>><?php echo (isset($o_checkpoint)? $o_checkpoint->comment :'')?></textarea>
                </div>
            </div>
        </div>
        <?php if(isset($o_checkpoint)) { ?>
        <?php if($o_checkpoint->censor_id != 397) { ?>
            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="censor_comment" class="col-xs-12 col-sm-3 control-label text-left">Ý kiến của Giám đốc phòng</label>
                    <div class="col-xs-12 col-sm-9 no-padding">
                        <textarea class="form-control" rows="3" id="censor_comment" name="censor_comment" <?php echo ($o_checkpoint->censor_id != Auth::user()->id || $o_checkpoint->status == 3 ) ? 'disabled' :''?>><?php echo (isset($o_checkpoint)? $o_checkpoint->censor_comment :'')?></textarea>
                    </div>
                </div>
            </div>
        <?php } ?>
            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <label for="hrm_comment" class="col-xs-12 col-sm-3 control-label text-left">Ý kiến của HRM</label>
                    <div class="col-xs-12 col-sm-9 no-padding">
                        <textarea class="form-control" rows="3" id="hrm_comment" name="hrm_comment" <?php echo (Auth::user()->hr_type != 1) ? 'disabled' :''?>><?php echo (isset($o_checkpoint)? $o_checkpoint->hrm_comment :'')?></textarea>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(!isset($i_DisableUpdate)) { ?>
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding">
                <button type="reset" class="btn btn-default">Nhập lại</button>
                <input type="button" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm " onclick="GLOBAL_JS.v_fAddCheckPoint();"/>
                <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit" id="add-check-point" >
            </div>
        </div>
        <?php } ?>
    </form>
    <!-- Modal Error time sheet-->
    <div class="modal fade" id="errorTimeSheet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lỗi vắng không phép</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr >
                                <th>Thời gian</th>
                                <th>Loại</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($errorTimeSheet) && count($errorTimeSheet) > 0): ?>
                            <?php foreach($errorTimeSheet as $key => $val): ?>
                                <tr>
                                    <th scope="row"><?php echo e($key); ?>-<?php echo e($month); ?>-<?php echo e($year); ?></th>
                                    <td><?php echo e($val); ?></td>
                                    <td><?php if($val == 'v'): ?> Vắng 1 buổi không phép <?php elseif($val == 'v/2'): ?> Vắng nửa buổi khôg phép <?php endif; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <span class="text-danger">(*)</span> Mọi thắc mắc liên hệ với phòng hành chính nhân sự.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Error late-->
    <div class="modal fade" id="errorLate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lỗi vắng đi muộn về sớm</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr >
                            <th>Thời gian</th>
                            <th>Chấm vào</th>
                            <th>Chấm ra</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($errorLate) && count($errorLate)> 0): ?>
                            <?php foreach($errorLate as $key => $val): ?>
                                <?php if($key != 'countError'): ?>
                                <?php $checkInCheckOut = explode("|",$val)?>
                                <tr>
                                    <th scope="row"><?php echo e($key); ?>-<?php echo e($month); ?>-<?php echo e($year); ?></th>
                                    <td><?php echo e(isset($checkInCheckOut[0]) ? $checkInCheckOut[0] : ''); ?></td>
                                    <td><?php echo e(isset($checkInCheckOut[1]) ? $checkInCheckOut[1] : ''); ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>