# 数据库说明

### 数据库的使用

直接连接云服务器的数据库即可，连接信息如下：

```
// 服务器地址
'hostname'        => 'lixiaole.com',
// 数据库名
'database'        => 'recover',
// 用户名
'username'        => 'root',
// 密码
'password'        => 'lishuo613',
// 端口
'hostport'        => '3306',
```

### 数据库设计

数据库目前有4个表：

admin：管理员信息表

pickman：取货员信息表

user：用户信息表

waste：废品信息表

config：系统配置

------

##### admin：管理员信息表

id :（int）主键，自增

username ：（varchar）管理员账号

password：（varchar）管理员密码

moible:（varchar）管理员手机号（考虑以后做通过手机验证码修改密码）

permission:（varchar）权限

note：（varchar）备注



##### pickman：取货员信息表

id :（int）主键，自增

username ：（varchar）取货员账号

realname：（varchar）取货员真实姓名

password：（varchar）取货员密码

moible:（varchar）取货员手机号

sex：（varchar）取货员性别

age：（int）取货员年龄

idnumber：（varchar）取货员身份证号

area：（varchar）取货员工作区域

note：（varchar）备注



##### user：用户信息表

id：

username：

openid：（varchar）用户的微信id

createtime：（datetime）用户创建时间

score：（int）用户的积分



##### waste：废品信息表

id:

name:

price:（decimal（10,2））废品的单位价格

unit：（varchar）废品的单位：斤，个

note：（varchar）备注



##### config：系统配置

id：

key:

value:

note:备注

