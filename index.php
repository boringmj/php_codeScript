<?php

/**
* 该函数用于将一串字符串转换为随机的javascript程序
* 您可以用此方法来保护您的链接,这个方法可以有效的识别部分脚本访问您的链接
* 该函数由无聊的莫稽(wuliaodemoji@wuliaomj.com)编写,效率和算法有待改进,您可以在任意地方随意的引用,修改本函数
* 如果可以我希望可以在引用时留下版权,以及为我的算法点一颗星星
* 这类算法都是我平时学习和生活中遇到的一些小问题需要解决而编写的,并没有花太多时间去简化和设计,可能存在bug,毕竟我就这么点本事
*
* string randScript(string $string)
* @param string $string 传入需要处理的字符串
* @return string 返回一串javascript代码
*/
function randScript($string)
{
    //这里省略了分割为组
    $string_array_string=array(array(0,"a",0),array(1,"b",1),array(1,"c",1),array(0,"d",0),array(2,"e",2),array(0,"f",0),array(2,"g",2));
    $string_variable=randString(6);  //为javascript定义一个随机的存储变量
    $string_count=0;  //用来计次,主要目的是防止函数或变量名重复
    $string_return="";  //为最终的javascript程序提供一个变量进行存储
    $string_array_return=array();  //用来存储遗留
    foreach($string_array_string as $value_array)
    {
        ++$string_count;  //我>>听说<< ++i比i++效率高一些
        $temp_rand=mt_rand(0,1);  //mt_rand()效率远高于rand()
        $variable_name=randString(6).$string_count;  //因为下面会用到,直接放这里了
        if($temp_rand)
        {
            //处理之前遗留和这的次字符组
            foreach($string_array_return as $value_temp_array)
            {
                $temp_string="";
                if($value_temp_array[0]==1)
                    $temp_string.="+{$value_temp_array[1]}";
                else if($value_temp_array[0]==2)
                    $temp_string.="+{$value_temp_array[1]}()";
                else
                    $temp_string.="+\"{$value_temp_array[1]}\"";
                if(!empty($temp_string))
                {
                    $string_return.="{$string_variable}={$string_variable}{$temp_string};";
                    $string_array_return=array();
                }
            }
            if($value_array[0]==1)
                $string_return.="var {$variable_name}=\"{$value_array[1]}\";{$string_variable}+={$variable_name};";
            else if($value_array[0]==2)
                $string_return.="var {$variable_name}=function(){return \"{$value_array[1]}\";};{$string_variable}+={$variable_name}();";
            else
                $string_return.="{$string_variable}+=\"{$value_array[1]}\";";
        }
        else
        {
            //遗留至以后处理
            if($value_array[0]==1)
                $string_return.="var {$variable_name}=\"{$value_array[1]}\";";
            else if($value_array[0]==2)
                $string_return.="var {$variable_name}=function(){return \"{$value_array[1]}\";};";
            else
                $variable_name=$value_array[1];
            //上面您可以自定义多种函数定义,变量甚至是常量的方法
            $string_array_return[]=array($value_array[0],$variable_name);
        }
    }
    //因为最后还可能存在遗留,所以也需要处理遗留
    foreach($string_array_return as $value_temp_array)
    {
        $temp_string="";
        if($value_temp_array[0]==1)
            $temp_string.="+{$value_temp_array[1]}";
        else if($value_temp_array[0]==2)
            $temp_string.="+{$value_temp_array[1]}()";
        else
            $temp_string.="+\"{$value_temp_array[1]}\"";
        if(!empty($temp_string))
        {
            $string_return.="{$string_variable}={$string_variable}{$temp_string};";
            $string_array_return=array();
        }
     }
    $string_return="var {$string_variable}=\"\";{$string_return}document.write($string_variable);";
    return $string_return;
}

function randString($length)
{
    //需要自写随机字符串算法
    return "abcdefg";
}

?>

<html lang="zh-CN">
<head>
<meat charset="utf-8">
<title>test</title>
</head>
<body>
<script>
<?php echo randScript("abcdefg");?>
</script>
</body>
</html>
