<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<html lang="en">
<head>
<meta name="keywords" content=""/>
<meta name="description" content="" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	
	<form action='/welcome/login' method="post">

		<table>
			<tr> 
				<td>用户名：</td>
				<td><input name="account" type="text"></td>
			</tr>

	        <tr> 
				<td>密码：</td>
				<td><input name="password" type="password"></td>
			</tr>
			<tr> 

	 
				<td>角色：</td>
				<td><select name="role">
					<option value="teacher">教师</option>
                    <option value="student">学生</option>

				</select></td>
			</tr>
			<tr> 
				
				<td><input name="submit" type="submit" value="登录"></td>
			</tr>
			<tr>
              <!--  <td><input name="zhuce" type="button" value="注册" onclick="window.location.href='/welcome/teacherzhuce'" ></td></tr>-->
		</table>



	</form>
	<input name="zhuce" type="button" value="注册" onclick="window.location.href='/welcome/teachertiaozhuan'" >
</body>
</html>