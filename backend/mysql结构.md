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

数据库目前有7个表：

admin：管理员信息表

pickman：取货员信息表

user：用户信息表

waste：废品信息表

order_master：订单信息表

config：系统配置

feedback: 用户反馈

------

##### administrator：管理员信息表

id :（int）主键，自增

top_id: 上级管理员

username ：（varchar）管理员账号

password：（varchar）管理员密码

moible:（varchar）管理员手机号（考虑以后做通过手机验证码修改密码）

permission:（varchar）权限

level: 管理员级别

note：（varchar）备注


##### index_page 首页数据表（banner）

id

name

type

url


##### pickman：取货员信息表

id :（int）主键，自增

openid: 取货员openid

username ：（varchar）取货员账号

realname：（varchar）取货员真实姓名

password：（varchar）取货员密码

phone:（varchar）取货员手机号

sex：（varchar）取货员性别

age：（int）取货员年龄

idnumber：（varchar）取货员身份证号

note：（varchar）备注

status: 状态 待审核已通过等

create_time: 

update_time

##### pickman_area 多对多中间表

id

pickman_id

area_id

##### user：用户信息表

id：

username：

password:

openid：（varchar）用户的微信id

score：（int）用户的积分

create_time：（datetime）用户创建时间

update_time: (datetime) 用户信息修改时间


##### waste：废品信息表

id:

name:

price:（decimal（10,2））废品的单位价格

unit：（varchar）废品的单位：斤，个

note：（varchar）备注



##### order_master：订单信息表

id:

order_no：用于展示的18位订单号

user_id:用户id

waste_id:废品id

pickman_id:取货员id

pick_time: 取货时间

pick_fast: 尽快上门 此时不需要取货时间

username: 联系人姓名

phone: 联系方式

area: 区域

area_id: 区域id

address_detail: 地址详细信息

waste_number：废品的数量（）

waste_price：废品的单价

discounts_money：优惠价格

deal_money:最终成交价格

note: 订单备注

status: 状态 待处理、服务中、已完成、已取消等

create_time：订单创建时间

update_time：订单修改时间


##### feedback: 反馈信息

id: 

user_id: 用户id null表示游客提交（如果有游客）

phone: 联系方式

message：反馈信息

status：状态 （标记已处理、忽略等）

create_time: 创建时间 
 
update_time: 修改时间

##### 用户地址表

id

user_id

name: 联系人名字

phone： 联系人手机号

area： 地址区域 省-市-区-街道

area_id: 区域id

detail： 地址详细信息 街道 路 门牌等

status： 地址状态 默认地址、禁用等

create_time: 地址创建时间

update_time: 地址修改时间

#### area 区域表 
id

name

level 区域级别 1为最小 

top_id 上级区域 如世回尧街道->芝罘区

city_id 关联城市表

administrator_id 辖区代理 

create_time

update_time

#### city 城市表

id 

administrator_id 关联管理员

name 城市名字

note 城市介绍

status 状态

create_time 创建时间

update_time 修改时间


##### config：系统配置

id：

key:

value:

note:备注

