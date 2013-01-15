增强版扩展是针对 redis-straoge 做的

redis-stroage
==================

简价：给redis配上leveldb持久引擎，麻麻再也不用担心我的内存了。

地址：[https://github.com/shenzhe/redis-storage](https://github.com/shenzhe/redis-storage)

注意
==================
此扩展与:https://github.com/shenzhe/redis-storage  保持一致

新增命令：
==============

	$redis->dsGet($key): 从leveldb取出数据
	$redis->dsMGet(array $keys) : 批量从leveldb取出数据,注：反回的是一个string：key1=val1&key2=val2, 需要用 parse_str 获取数组
	$redis->dsSet($key, $value): 把数据存到leveldb
	$redis->dsMSet(array $keys) :批量把数据存到leveldb; keys结构 array("key1"=>"val1", "key2"=>"val2")
	$redis->dsDel($key):  从leveldb删除数据， $key可以是字符串，也可是key的数组集合（相当于批量删除）
	$redis->rlGet($key):  先尝试从redis取数据，再尝试从leveldb取数据
	$redis->rlSet($key, $value): 先把数据存到leveldb，再把数据存到redis
	$redis->rlDel($key):  先从leveldb删除数据，再从redis删除数据
	$redis->dsAppend($key, $appendVal); 追加数据 
	$redis->dsIncrBy($key, $step);  //按$step步长自增
	$redis->dsHSet($key, $hashKey, $val);
	$redis->dsHGet($key, $hashKey,);
	$redis->dsHDel($key, $hashKey);
	$redis->dsHMget($key, array $hashKeys);
	$redis->dsHMset($key, array $hashKeyVals);
	$redis->dsHIncrBy($key, $hashKey, $step);
	$redis->dsHGetAll($key);
	$redis->rlHSet($key, $hashKey, $val); //同时设置到redis和leveldb
	$redis->rlHGet($key, $hashKey,);      //先从redis取，再从leveldb取
	$redis->rlHDel($key, $hashKey);       /同时删除redis和leveldb中的数据
	

phpredis 详细wiki
=========================

地址：https://github.com/nicolasff/phpredis/blob/master/README.markdown
