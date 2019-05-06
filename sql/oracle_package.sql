create or replace Package Body Pkg_Security Is
 
  Function Authenticate_User(p_User_Name Varchar2
                            ,p_Password  Varchar2) Return Boolean As
     v_Password Users.Password%Type;
     v_Status   Users.Status%Type;
     v_User_id   Users.User_id%Type;
     v_Verified Users.Verified%Type;
     
  Begin
     If p_User_Name Is Null Or p_Password Is Null Then
  
        -- Write to Session, Notification must enter a username and password
        Apex_Util.Set_Session_State('LOGIN_MESSAGE'
                                   ,'Please enter Username and password.');
        Return False;
     End If;
     ----
     Begin
        Select u.Status
              ,u.Verified
              ,u.Password
              ,u.User_id
        Into   v_Status
              ,v_Verified
              ,v_Password
              ,v_User_id
        From   Users u
        Where  u.Username = p_User_Name
        and (u.role='Admin' or u.role='Trader');
        
     Exception
        When No_Data_Found Then
      
           -- Write to Session, User not found.
           Apex_Util.Set_Session_State('LOGIN_MESSAGE'
                                      ,'User not found');
           Return False;
     End;
     If v_Password <> getMD5(p_Password) Then
    
        -- Write to Session, Password incorrect.
        Apex_Util.Set_Session_State('LOGIN_MESSAGE'
                                   ,'Password incorrect');
        Return False;
     End If;
     If v_Status <> 'active' Then
        Apex_Util.Set_Session_State('LOGIN_MESSAGE'
                                   ,'Account disabled, please contact admin');
        Return False;
     End If;
      If v_Verified <> 1 Then
        Apex_Util.Set_Session_State('LOGIN_MESSAGE'
                                   ,'Your account is not verified! Check your Email!');
        Return False;
     End If;
     ---
     -- Write user information to Session.
     --
     Apex_Util.Set_Session_State('SESSION_USERNAME'
                                ,p_User_Name);
     Apex_Util.Set_Session_State('SESSION_USER_ID'
                                ,v_User_id);
     ---
     ---
     Return True;
  End;
 
  --------------------------------------
  Procedure Process_Login(p_User_Name Varchar2
                         ,p_Password  Varchar2
                         ,p_App_Id    Number) As
     v_Result Boolean := False;
  Begin
     v_Result := Authenticate_User(p_User_Name
                                  ,p_Password);
     If v_Result = True Then
        -- Redirect to Page 1 (Home Page).
        Wwv_Flow_Custom_Auth_Std.Post_Login(p_User_Name -- p_User_Name
                                           ,p_Password -- p_Password
                                           ,v('APP_SESSION') -- p_Session_Id
                                           ,p_App_Id || ':1' -- p_Flow_page
                                            );
     Else
        -- Login Failure, redirect to page 101 (Login Page).
        Owa_Util.Redirect_Url('f?p=&APP_ID.:101:&SESSION.');
     End If;
  End;
 
End Pkg_Security;
