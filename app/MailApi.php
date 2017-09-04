<?php

namespace App;

use SoapClient;
use Illuminate\Database\Eloquent\Model;
use App\Util;

class MailApi extends Model
{
    const AUTH_ADMIN    = 'admin';

    const AUTH_PWD      = 'TwI1_Og8xSk5&](n-s3+JhaTSw!Dge&02';

    const DOMAIN_NAME   = 'dxmb.vn';

    const SERVICE_WSDL  = 'https://mail.dxmb.vn/Services/';

    const USER_ADMIN    = 'svcUserAdmin';

    const ALIAS_ADMIN    = 'svcAliasAdmin';
    
    const EMAIL_ADDRESS = 'EmailAddress';

    protected $_a_AuthAdmin     = array();

    protected $_a_AuthDomain    = array();

    public function __construct()
    {
        $this->_a_AuthAdmin = array(
                'AuthUserName' => self::AUTH_ADMIN,
                'AuthPassword' => self::AUTH_PWD,
        );
        $this->_a_AuthDomain = array(
                'AuthUserName' => self::AUTH_ADMIN,
                'AuthPassword' => self::AUTH_PWD,
                'DomainName' => self::DOMAIN_NAME
        );
    }

    /**
     * Soap connect
     * @author DungNT
     * @since 16/11/2015
     * @param string $the_sz_Module
     * @return SoapClient|boolean
     */
    protected function o_fSoapClient($the_sz_Module)
    {
        if ($the_sz_Module) {
            try {
                $o_Client = new SoapClient(self::SERVICE_WSDL . "$the_sz_Module.asmx?WSDL", array(
                        'trace' => 1,
                        'exceptions' => true
                ));
                return $o_Client;
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
        return false;
    }

    /**
     * Get users info
     * @author DungNT
     * @since 16/11/2015
     * @return array|boolean
     */
    public function a_fGetUsers()
    {
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $o_Users = $o_SoapClient->GetUsers($this->_a_AuthDomain);
            //Check Users data
            if(Util::b_fCheckObject($o_Users) && Util::b_fCheckObject($o_Users->GetUsersResult)){
                //Check result
                if($o_Users->GetUsersResult->Result && Util::b_fCheckObject($o_Users->GetUsersResult->Users)){
                    return (array)$o_Users->GetUsersResult->Users->UserInfo;
                }
            }
        }
        return false;
    }

    /**
     * Add users on Mail Server
     * @author HuyNN
     * @since 27/01/2015
     * @return array|boolean
     */
    public function b_fAddUsers($a_Insert)
    { 
        $sz_mes = '';
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $sz_Guid = $a_Insert['Guid']; unset($a_Insert['Guid']);
            $sz_UserGroupName = $a_Insert['UserGroupName']; unset($a_Insert['UserGroupName']);
            $a_UserNames = $a_Insert['UserNames']; unset($a_Insert['UserNames']);
            
            $a_InsertEmailAPI = array_merge($a_Insert,$this->_a_AuthDomain);
            $o_Insert = $o_SoapClient->AddUser2($a_InsertEmailAPI);
            if(Util::b_fCheckObject($o_Insert) && Util::b_fCheckObject($o_Insert->AddUser2Result))
            {
                //Check result
                if($o_Insert->AddUser2Result->Result)
                {
                    $a_UpdateUserGroup['UserGroupID'] = $sz_Guid;
                    $a_UpdateUserGroup['UserGroupName'] = $sz_UserGroupName;
                    $a_UpdateUserGroup['UserNames'] = $a_UserNames;
                    
                    $a_UpdateUserGroupAPI = array_merge($a_UpdateUserGroup,$this->_a_AuthDomain);
                    $o_UpdateUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateUserGroupAPI);

                    if(Util::b_fCheckObject($o_UpdateUserGroup) && Util::b_fCheckObject($o_UpdateUserGroup->UpdateUserGroupResult)){
                        if(!$o_UpdateUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể add user mới vào Phòng ban trên Mail Server!';
                    }
                    else $sz_mes = 'Không có kết quả trả về khi add user mới vào Phòng ban trên Mail Server!';
                }
                else $sz_mes = 'Không thể thêm user mới trên Mail server!';
            }
            else $sz_mes = 'Không có kết quả trả về khi thêm User mới trên Mail Server!';
        }
        return $sz_mes;
    }
    
    /**
     * Update users on Mail Server
     * @author HuyNN
     * @since 27/01/2015
     * @return array|boolean
     */
    public function b_fUpdateUsers($a_Update)
    {
        $sz_mes = '';
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient))
        {
            if(isset($a_Update['UpdateNewDepartment']))
            {
                $a_UpdateNewDepartment = array_merge($a_Update['UpdateNewDepartment'],$this->_a_AuthDomain);
                unset($a_Update['UpdateNewDepartment']);
                if(isset($a_Update['UpdateDepartment']))
                {
                    $a_UpdateDepartment = array_merge($a_Update['UpdateDepartment'],$this->_a_AuthDomain);
                    unset($a_Update['UpdateDepartment']);
                }  
            }
            $a_UpdateEmailAPI = array_merge($a_Update,$this->_a_AuthAdmin);
            
            $o_UpdateEmail = $o_SoapClient->UpdateUser2($a_UpdateEmailAPI);
            if(Util::b_fCheckObject($o_UpdateEmail) && Util::b_fCheckObject($o_UpdateEmail->UpdateUser2Result))
            {
                if(isset($a_UpdateNewDepartment))
                {
                    $o_UpdateNewUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateNewDepartment);
                    if(isset($a_UpdateDepartment))
                    {
                        $o_UpdateUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateDepartment);
                    }
                    
                    if(Util::b_fCheckObject($o_UpdateNewUserGroup) && Util::b_fCheckObject($o_UpdateNewUserGroup->UpdateUserGroupResult))
                    {
                        if(!$o_UpdateNewUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể cập nhật UserGroup mới trên Mail Server!';
                        else
                        {
                            if(isset($a_UpdateDepartment))
                            {
                                if(Util::b_fCheckObject($o_UpdateUserGroup) && Util::b_fCheckObject($o_UpdateUserGroup->UpdateUserGroupResult))
                                {
                                    if(!$o_UpdateUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể cập nhật UserGroup cũ trên Mail Server!';
                                }
                                else $sz_mes = 'Không có kết quả trả về khi cập nhật danh sách User trong Phòng ban cũ trên Mail Server!';
                            }
                        }
                    }
                    else $sz_mes = 'Không có kết quả trả về khi cập nhật danh sách User trong Phòng ban mới trên Mail Server!';
                }
            }
            else $sz_mes = 'Không có kết quả trả về khi update User lên Mail Server!'; 
        }
        else $sz_mes = 'Kiểm tra lại Soap!';
        return $sz_mes;
    }
    
    /**
     * Get users groups info
     * @author DungNT
     * @since 16/11/2015
     * @return array|boolean
     */
    public function a_fGetUserGroupsByDomain()
    {
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $o_UserGroups = $o_SoapClient->GetUserGroupsByDomain($this->_a_AuthDomain);
            //Check Users data
            if(Util::b_fCheckObject($o_UserGroups) && Util::b_fCheckObject($o_UserGroups->GetUserGroupsByDomainResult)){
                //Check result
                if($o_UserGroups->GetUserGroupsByDomainResult->Result && Util::b_fCheckObject($o_UserGroups->GetUserGroupsByDomainResult->UserGroups)){
                    return (array)$o_UserGroups->GetUserGroupsByDomainResult->UserGroups->UserGroupInfo;
                }
            }
        }
        return false;
    }
    
    /**
     * Update UserGroup in Mail Server afrer Reporter approve//
     * @author HuyNN
     * @since 17/03/2016
     * @return array|boolean
     */
    public function a_fUpdateUserGroups($a_UpdateOldDepartment,$a_UpdateNewDepartment)
    {
        $sz_mes = '';
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient))
        {
            if(count($a_UpdateOldDepartment) > 0)
            {
                $a_UpdateOldDepartmentAPI = array_merge($a_UpdateOldDepartment,$this->_a_AuthDomain);
                $o_UpdateOldUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateOldDepartmentAPI);
            }
            
            $a_UpdateNewDepartmentAPI = array_merge($a_UpdateNewDepartment,$this->_a_AuthDomain);
            $o_UpdateNewUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateNewDepartmentAPI);
            if(Util::b_fCheckObject($o_UpdateNewUserGroup) && Util::b_fCheckObject($o_UpdateNewUserGroup->UpdateUserGroupResult))
            {
                if(count($a_UpdateOldDepartment) > 0)
                {
                    if(Util::b_fCheckObject($o_UpdateOldUserGroup) && Util::b_fCheckObject($o_UpdateOldUserGroup->UpdateUserGroupResult))
                    {
                        if(!$o_UpdateNewUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể cập nhật lại UserGroup cũ trên Mail Server!';
                    }
                    else $sz_mes = 'Không có kết quả trả về khi cập nhật danh sách User trong Phòng ban cũ trên Mail Server!';
                }
                if(!$o_UpdateNewUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể cập nhật lại UserGroup mới trên Mail Server!';
            }
            else $sz_mes = 'Không có kết quả trả về khi cập nhật danh sách User trong Phòng ban mới trên Mail Server!';
        }
        return $sz_mes;
    }
    
    /**
     * Delete User in Mail Server//
     * @author HuyNN
     * @since 04/04/2016
     * @return string
     */
    public function b_fDeleteUsers($a_UpdateDepartment,$sz_Email)
    {
        $sz_mes = '';
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $a_DeleteUser = $this->_a_AuthDomain;
            $a_DeleteUser['Username'] = $sz_Email;
            $o_DeleteUser = $o_SoapClient->DeleteUser($a_DeleteUser);
            
            $a_UpdateDepartmentAPI = array_merge($a_UpdateDepartment,$this->_a_AuthDomain);
            $o_UpdateUserGroup = $o_SoapClient->UpdateUserGroup($a_UpdateDepartmentAPI);
            if(Util::b_fCheckObject($o_DeleteUser) && Util::b_fCheckObject($o_DeleteUser->DeleteUserResult) && Util::b_fCheckObject($o_UpdateUserGroup) && Util::b_fCheckObject($o_UpdateUserGroup->UpdateUserGroupResult))
            {
                if(!$o_DeleteUser->DeleteUserResult->Result ||  !$o_UpdateUserGroup->UpdateUserGroupResult->Result) $sz_mes = 'Không thể xóa User trên Mail Server!';
            }
            else $sz_mes = 'Không có kết quả trả về khi xóa User trên Mail Server!';
        }
        else $sz_mes = 'Kiểm tra lại Soap!';
        return $sz_mes;
    }
    
    /**
     * Get users groups info
     * @author HuyNN
     * @since 04/04/2016
     * @return array|boolean
     */
    public function a_fGetUserAlias()
    {
        $o_SoapClient = $this->o_fSoapClient(self::ALIAS_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $o_AllAlias = $o_SoapClient->GetAliases($this->_a_AuthDomain);
            //Check All Alias data
            if(Util::b_fCheckObject($o_AllAlias) && Util::b_fCheckObject($o_AllAlias->GetAliasesResult)){
                //Check result
                if($o_AllAlias->GetAliasesResult->Result && Util::b_fCheckArray($o_AllAlias->GetAliasesResult->AliasInfos->AliasInfo)){
                    $a_NameIntoAlias = $o_AllAlias->GetAliasesResult->AliasInfos->AliasInfo;
                    foreach ($a_NameIntoAlias as $o_val) {
                        if(isset($o_val->Addresses->string))
                        {
                            $a_ConvertArr[$o_val->Name] = $o_val->Addresses->string;
                        }
                        else  $a_ConvertArr[$o_val->Name] = array();
                    }
                    return $a_ConvertArr;
                } 
            }
        }
        return false;
    }
    
    /**
     * Convert array alias get from Mail Server
     * @author HuyNN
     * @since 04/04/2016
     */
    public function a_fConvertArrAlias($a_UsersDB,$a_AllAlias)
    {
        $a_EmailAndAlias = array();
        foreach ($a_UsersDB as $o_UserDB) 
        {
           $a_Users[$o_UserDB->email] = $o_UserDB->id;
        }

        foreach ($a_AllAlias as $key => $o_val) 
        {
            $sz_Name = $o_val->Name;
            if(Util::b_fCheckArray($o_val->Addresses->string))
            {
                foreach ($o_val->Addresses->string as $sz_Email) 
                {
                    if(array_key_exists($sz_Email,$a_Users)){
                        $a_EmailAndAlias[$sz_Name] =  array_key_exists($sz_Name, $a_EmailAndAlias)?$a_EmailAndAlias[$sz_Name].','.$sz_Email:$sz_Email;
                    } 
                }
            }
            else 
            {
                if(array_key_exists($o_val->Addresses->string,$a_Users))
                {
                    $a_EmailAndAlias[$sz_Name] =  array_key_exists($sz_Name, $a_EmailAndAlias)?$a_EmailAndAlias[$sz_Name].','.$o_val->Addresses->string:$o_val->Addresses->string;
                } 
            }
        }
        return $a_EmailAndAlias;
    }
    
    /**
     * Update users into Alias on Mail Server
     * @author HuyNN
     * @since 04/04/2016
     * @return array|boolean
     */
    public function b_fUpdateAlias($sz_NameAlias, $a_Emails)
    { 
        $sz_mes = '';
        $o_SoapClient = $this->o_fSoapClient(self::ALIAS_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){       
            $a_Update = $this->_a_AuthDomain;
            $a_Update['AliasName'] = $sz_NameAlias;
            $a_Update['Addresses'] = $a_Emails;
    
            $o_UpdateAlias = $o_SoapClient->UpdateAlias($a_Update);
           
            if(Util::b_fCheckObject($o_UpdateAlias) && Util::b_fCheckObject($o_UpdateAlias->UpdateAliasResult))
            {
                if(!$o_UpdateAlias->UpdateAliasResult->Result) $sz_mes = 'Không thể Update Alias trên Mail Server!';
            }
            else $sz_mes = 'Không có kết quả trả về khi Update lại Alias trên Mail Server!';
        }
        else $sz_mes = 'Kiểm tra lại Soap!';
        return $sz_mes;
    }
    
    /**
     * Get all User Quotas
     * @author HuyNN
     * @since 12/04/2016
     * @return array|boolean
     */
    public function a_fGetQuotas()
    { 
        $o_SoapClient = $this->o_fSoapClient(self::USER_ADMIN);
        //Check soap client connect
        if(Util::b_fCheckObject($o_SoapClient)){
            $o_Quotas = $o_SoapClient->GetUserQuotas($this->_a_AuthDomain);
            if(Util::b_fCheckObject($o_Quotas) && Util::b_fCheckObject($o_Quotas->GetUserQuotasResult)){
                //Check result
                if($o_Quotas->GetUserQuotasResult->Result && Util::b_fCheckObject($o_Quotas->GetUserQuotasResult->Users)){
                    return (array)$o_Quotas->GetUserQuotasResult->Users->UserQuota;
                }
            } 
        }
        return false;
    }
}
