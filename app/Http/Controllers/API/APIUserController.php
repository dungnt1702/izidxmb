<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Users as users_model;
use Psy\Util\Json;
use Auth;
use DB;

class APIUserController extends Controller
{
    const TOKEN_KEY = 'cd546e97a498989f71d5c6c4930e5d61'; //DxmbIZIApi2016

    protected $_o_UsersModel;

    protected $_b_TokenKey = false;
    protected $_sz_Fnc = '';
    protected $_sz_Email = '';
    protected $_sz_Pwd = '';

    public function __construct(Request $o_Request)
    {
        $this->_o_UsersModel = new users_model();

        // Get token key from param
        $this->_b_TokenKey = $o_Request->input('_token') == self::TOKEN_KEY ? true : false;
        // Get email and password from param
        $this->_sz_Email = trim($o_Request->input('email'));
        $this->_sz_Pwd = trim($o_Request->input('pwd'));
        // Get function from param
        $this->_sz_Fnc = $o_Request->input('fnc');
    }

    /**
     * Function navigation
     * https://izitest.dxmb.vn/user_api?token=cd546e97a498989f71d5c6c4930e5d61
     * @author DungNT
     * @since 31/01/2016
     */
    public function sz_fFunctionNavigation()
    {
        $o_Result = new \stdClass();
        $o_Result->result = 'failed';
        $o_Result->message = '';
        $o_Result->data = new \stdClass();
        $o_Result->log = '';

        // Start micro time
        $i_StartTime = microtime(true);

        // Auth user
        if(Auth::once(['email' => $this->_sz_Email, 'password' => $this->_sz_Pwd])) {
            // Check token key
            if($this->_b_TokenKey) {
                // Navigate to correct function
                switch ($this->_sz_Fnc) {
                    case 'user_login':
                        // https://izitest.dxmb.vn/api/user_api?fnc=user_login&email=...&pwd=...&_token=cd546e97a498989f71d5c6c4930e5d61
                        $o_Result->data = $this->_o_UsersModel->o_fGetActiveUserByEmail($this->_sz_Email, $this->_sz_Pwd);
                        break;
                    default: $o_Result->message = "Need the correct Function to navigate!\n"; break;
                }
                // Check return data
                if($o_Result->data && is_object($o_Result->data)) {
                    $o_Result->result = 'success';
                    $o_Result->message = "Đăng nhập thành công!\n";
                } else {
                    $o_Result->message = "Email hoặc mật khẩu không chính xác!\n";
                }
            } else {
                $o_Result->message = "Wrong token key!\n";
            }
        } else {
            $o_Result->message = "Email hoặc mật khẩu không chính xác!\n";
        }
        // End micro time
        $i_EndTime = microtime(true);
        // Get execute time
        $i_ExecuteTime = $i_EndTime - $i_StartTime;
        // Log execute times
        $o_Result->log = "Execute times: $i_ExecuteTime.\n";
        // Return JSON encode
        return response()->json($o_Result);
    }

}
