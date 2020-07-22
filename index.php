<？php
error_reporting（0）;
类 API {

    // 一般
    保护 $ _MINI_MODE = 假 ;
    保护 $ _MODULUS = '00e0b509f6259df8642dbc35662901477df22677ec152b5ff68ace615bb7b725152b3ab17a876aea8a5aa76d2e417629ec4ee341f56135fccf695280104e0312ecbda92557c93870114af6c9d05c4f7f0c3685b7a46bee255932575cce10b424d813cfe4875d3e82047b97ddef52741d546b8e289dc6935b3ece0462db0a22b8e7' ;
    受保护的 $ _NONCE = '0CoJUm6Qyw8W8jud' ;
    保护 $ _PUBKEY = '010001' ;
    受保护的 $ _VI = '0102030405060708' ;
    protected  $ _USERAGENT = 'Mozilla / 5.0（Windows NT 10.0; WOW64）AppleWebKit / 537.36（KHTML，如Gecko）Chrome / 35.0.1916.157 Safari / 537.36' ;
    保护 $ _COOKIE = 'os = pc; osver = Microsoft-Windows-10-Professional-build-10586-64bit; appver = 2.0.3.131777; 渠道=网易; __remember_me = true;' ;
    保护 $ _REFERER = 'http: //music.163.com/ ' ;
    //使用静态secretKey，不使用RSA算法
    保护 $ _secretKey = 'TA3YiYCfY2dDJQgg' ;
    保护 $ _encSecKey = '84ca47bca10bad09a6b04c5c927ef077d9b9f1e37098aa3eac6ea70eb59df0aa28b691b7e75e4f1f9831754919ea784c8f74fbfadf2898b0be17849fd656060162857830e241aba44991601f137624094c114ea8d17bce815b0cd4e5b8e2fbaba978c6d1d14dc3d1faf852bdd28818031ccdaaa13a6018e1024e2aae98844210' ;

    //加密mod
    受保护的 功能 prepare（$ raw）{
        $数据 [ 'PARAMS' ] = $ 这 - > aes_encode（ json_encode（ $原料）， $ 这 - > _NONCE）;
        $数据 [ 'PARAMS' ] = $ 这 - > aes_encode（ $数据 [ 'PARAMS' ]， $ 这 - > _secretKey）;
        $数据 [ 'encSecKey' ] = $ 这 - > _encSecKey ;
        返回 $数据 ;
    }
    受保护的 函数 aes_encode（$ secretData，$ secret）{
        返回 openssl_encrypt（$ secretData，'AES-128-CBC' ，$秘密，虚假，$ 此 - > _VI）;
    }

    // CURL
    受保护的 函数 curl（$ url，$ data = null，$ cookie = false）{
        $ curl = curl_init（）;
        curl_setopt（$ curl，CURLOPT_URL，$ url）;
        如果（$数据）{
            if（is_array（$ data））$ data = http_build_query（$ data）;
            curl_setopt（$ curl，CURLOPT_POSTFIELDS，$ data）;
            curl_setopt（$ curl，CURLOPT_POST，1）;
        }
        curl_setopt（$ curl，CURLOPT_SSL_VERIFYPEER，false）;
        curl_setopt（$ curl，CURLOPT_RETURNTRANSFER，1）;
        curl_setopt（$ curl，CURLOPT_CONNECTTIMEOUT，10）;
        curl_setopt（$卷曲，CURLOPT_REFERER，$ 此 - > _REFERER）;
        curl_setopt（$卷曲，CURLOPT_COOKIE，$ 这 - > _COOKIE。“__csrf =” 。$ _COOKIE [ “__csrf” ]。“; MUSIC_U =” 。$ _COOKIE [ “MUSIC_U” ]）;
        curl_setopt（$卷曲，CURLOPT_USERAGENT，$ 此 - > _USERAGENT）;
        如果（$ cookie == true）{
        curl_setopt（$ curl，CURLOPT_HEADER，1）;
        $结果 = curl_exec（ $ curl）;
        preg_match_all（'/\{(.*)\}/' ，$结果，$ JSON）;
        if（json_decode（$ json [ 0 ] [ 0 ]，1）[ “ code” ] == 200）{
            preg_match_all（'/ Set-Cookie：MUSIC_U =（。*？）\; /'，$ result，$ musicu）;
            preg_match_all（'/ Set-Cookie：__csrf =（。*？）\; /'，$ result，$ csrf）;
            setcookie（“ MUSIC_U”，$ musicu [ 1 ] [ 0 ]）;
            setcookie（“ __csrf”，$ csrf [ 1 ] [ 0 ]）;
        }
        $ result = $ json [ 0 ] [ 0 ];
        } 其他 {
        $ result = curl_exec（ $ curl）;}
        curl_close（$ curl）;
        返回 $结果 ;
    }

    // 主功能
    公共 职能 索引（）{
        $ string = file_get_contents（ “ help.html”）;
        // echo $ string;
        返回 $字符串 ;
    }
    //通过电话登录
    公共 功能 登录名（$ cell，$ pwd）{
        $ url = “ https://music.163.com/weapi/login/cellphone” ;
        $ data =数组（
        “ phone” => $ cell，
        “ countrycode” => “ 86”，
        “密码” => $ pwd，
        “ rememberLogin” => “ true”）;
        返回 $ 此 - > 卷曲（$网址，$ 此 - > 准备（$数据），真）;
    }
    //通过电子邮件登录
    公共 功能 loginByEmail（$ cell，$ pwd）{
        $ url = “ https://music.163.com/weapi/login” ;
        $ data =数组（
        “用户名” => $单元格，
        “密码” => $ pwd，
        “ rememberLogin” => “ true”）;
        返回 $ 此 - > 卷曲（$网址，$ 此 - > 准备（$数据），真）;
    }
    //获取用户详细信息
    公共 功能 详细信息（$ uid）{
        $ url = “ https://music.163.com/weapi/v1/user/detail/${uid}”；
        返回 $ 此 - > 卷曲（$网址，$ 此 - > 准备（$数据），真）;
    }
    公共 职能 关注（）{
        $ url = “ https://music.163.com/weapi/user/follow/0” ;
        返回 '{“ code”：'。json_decode（$ 这 - > 卷曲（$网址，$ 这 - > 制备（阵列（'csrf_token' => $ _COOKIE [ “__csrf” ]））），1）[ “代码”。'}' ;
    }
    公共 功能 推荐（）{
        $ url = “ https://music.163.com/weapi/v1/discovery/recommend/resource”；
        $ JSON = json_decode（ $ 这 - >卷曲（ $网址， $ 这 - >制备（阵列（ 'csrf_token' => $ _COOKIE [ “__csrf” ]）））， 1）;
		foreach（$ json [ “ recommend” ] as  $ i => $ k）{
			$ id [ $ i ] = $ k [ “ id” ];
		}
		返回 $ id ;
        
    }
    公共 功能 daka_new（）{
        $播放列表 = $ 此 ->推荐（）;
        $ ids = array（）;
        $ count = 0 ;
        对于（$ i = 0 ; sizeof（$ ids）< 310 ; $ i ++）{
        	$ songid = $ 这 - > getsongid（ $播放列表 [兰特（ 0，的sizeof（ $播放列表） - 1）]）;
        	for（$ k = 0 ; sizeof（$ ids）< 310 && $ k < sizeof（$ songid）; $ k ++）{
        	
        	$ ids [ $ count ] [ “ action” ] = “ play” ;
        	$ ids [ $ count ] [ “ json” ] [ “ download” ] = 0 ;
        	$ ids [ $ count ] [ “ json” ] [ “ end” ] = “ playend” ;
     		$ ids [ $ count ] [ “ json” ] [ “ id” ] = $ songid [ $ k ] [ “ id” ];
     		$ ids [ $ count ] [ “ json” ] [ “ sourceId” ] = “” ;
     		$ ids [ $ count ] [ “ json” ] [ “ time” ] = 240 ;
     		$ ids [ $ count ] [ “ json” ] [ “ type” ] = “ song” ;
     		$ ids [ $ count ] [ “ json” ] [ “ wifi” ] = 0 ;
     		$ count ++;
    	    }
        }
        $ data = json_encode（ $ ids）;
        $ url = “ http://music.163.com/weapi/feedback/weblog” ;
        $ 这 - >卷曲（ $网址， $ 这 - >制备（阵列（ “日志” => $数据）））;
        返回 '{“ code”：200，“ count”：'。$计数。'}' ;
    }
    公共 功能 监听（$ id，$ time）{
        $ ids = array（）;
        $ count = 0 ;
        $ t = 1 ;
    	$ songid = $ 这 - > getsongid（ $ ID）;
    	而（$ t <= $时间）{
    	    foreach（$ songid  as  $ index => $ trackId）{
    			$ ids [ $ count ] [ “ action” ] = “ play” ;
            	$ ids [ $ count ] [ “ json” ] [ “ download” ] = 0 ;
            	$ ids [ $ count ] [ “ json” ] [ “ end” ] = “ playend” ;
         		$ ids [ $ count ] [ “ json” ] [ “ id” ] = $ trackId [ “ id” ];
         		$ ids [ $ count ] [ “ json” ] [ “ sourceId” ] = “” ;
         		$ ids [ $ count ] [ “ json” ] [ “ time” ] = 240 ;
         		$ ids [ $ count ] [ “ json” ] [ “ type” ] = “ song” ;
         		$ ids [ $ count ] [ “ json” ] [ “ wifi” ] = 0 ;
         		$ count ++;
    		}
    		$ t ++;
    	}
        $ data = json_encode（ $ ids）;
        $ url = “ http://music.163.com/weapi/feedback/weblog” ;
        $ 这 - >卷曲（ $网址， $ 这 - >制备（阵列（ “日志” => $数据）））;
        返回 '{“ code”：200，“ count”：'。$计数。'}' ;
    }
    公共 功能 getsongid（$ playlist_id）{
        $ url = 'https://music.163.com/weapi/v3/playlist/detail? csrf_token = ' ;
        $ data =数组（
            'id' => $ playlist_id，
            'n' => 1000，
            'csrf_token' => ''，
        ）;
        $原始 = $ 这 - >卷曲（ $网址， $ 这 - >制备（ $数据））;
        $ json = json_decode（ $ raw， 1）;
      	
		返回 $ json [ “播放列表” ] [ “ trackIds” ];
        //返回json_decode（$ raw，1）[];
    }
    
    
    
    
    公共 功能 daka（）{
        $播放列表 = $ 此 ->推荐（）;
        $ ids = array（）;
        对于（$ i = 0 ; sizeof（$ ids）< 300 ; $ i ++）{
        	$ songid = $ 这 - >播放列表（ $播放列表 [兰特（ 0，的sizeof（ $播放列表））]）;
        	foreach（$ songid  as  $ id）{
     		$ ids [] = $ id ;
    	    }
        }
        返回 '{“ code”：200，“ count”：'。$ 本 - > scrobble（$ IDS）。'}' ;
    }
    公共 功能 标志（）{
        $ url = “ https://music.163.com/weapi/point/dailyTask” ;
        $ data = array（ “ type” => 0）;
        返回 $ 此 - > 卷曲（$网址，$ 此 - > 准备（$数据），真）;
    }
    公共 功能 scrobble（$ songid）{
        $ url = “ http://music.163.com/weapi/feedback/weblog” ;
        $ res =数组（）;
        $ count = 0 ;
        $饼干 = $ 这个 - > _COOKIE。“ __csrf =”。$ _COOKIE [ “ __csrf” ]。“; MUSIC_U =”。$ _COOKIE [ “ MUSIC_U” ];
		$ mh = curl_multi_init（）;
		foreach（$ songid  as  $ k => $ id）{
   	        $数据 [ $ ķ ] = $ 这 - >制备（阵列（ “日志” => “[{ “行动”： “播放”， “JSON”：{ “下载”：0， “结束”： “playend”， “ id”：“'。 $ id。 '”，“ sourceId”：“”，“ time”：240，“ type”：“ song”，“ wifi”：0}}]''）））;
            $ data [ $ k ] = http_build_query（ $ data [ $ k ]）;
			$ conn [ $ k ] = curl_init（ $ k）;
			curl_setopt（$ conn [ $ k ]，CURLOPT_URL，$ url）;
			curl_setopt（$ conn [ $ k ]，CURLOPT_POST，1）;
			curl_setopt（$ conn [ $ k ]，CURLOPT_POSTFIELDS，$ data [ $ k ]）;
			// curl_setopt（$ conn [$ k]，CURLOPT_TIMEOUT，$ timeout）;
			curl_setopt（$ conn [ $ k ]，CURLOPT_COOKIE，$ cookie）;
			curl_setopt（$ conn [ $ k ]，CURLOPT_RETURNTRANSFER，1）;
			curl_multi_add_handle（$ mh，$ conn [ $ k ]）;
			$ count ++;
		}
		做 {
			$ mrc = curl_multi_exec（ $ mh， $ active）;
		}
		而（$ mrc == CURLM_CALL_MULTI_PERFORM）;
		而（$ active和$ mrc == CURLM_OK）{
			如果（curl_multi_select（$ mh）！=- 1）{
				做 {
					$ mrc = curl_multi_exec（ $ mh， $ active）;
				}
				而（$ mrc == CURLM_CALL_MULTI_PERFORM）;
			}
		}
		foreach（$ array  as  $ k => $ value）{
			curl_error（$ conn [ $ k ]）;
			$ res [ $ k ] = curl_multi_getcontent（ $ conn [ $ k ]）;
			// $ header [$ k] = curl_getinfo（$ conn [$ k]）;
			curl_close（$ conn [ $ k ]）;
			curl_multi_remove_handle（$ mh，$ conn [ $ k ]）;
		}
		curl_multi_close（$ mh）;
		//返回数组（'return'=> $ res，'header'=> $ header）;
        //返回“ 233”；
        返回 $ count ;
    }

    公共 功能 播放列表（$ playlist_id）{
        $ url = 'https://music.163.com/weapi/v3/playlist/detail? csrf_token = ' ;
        $ data =数组（
            'id' => $ playlist_id，
            'n' => 1000，
            'csrf_token' => ''，
        ）;
        $原始 = $ 这 - >卷曲（ $网址， $ 这 - >制备（ $数据））;
        $ json = json_decode（ $ raw， 1）;
      	foreach（$ json [ “播放列表” ] [ “ trackIds” ] 为 $ i => $ k）{
			$ ids [ $ i ] = $ k [ “ id” ];
		}
		返回 $ ids ;
        //返回json_decode（$ raw，1）[];
    }
}
$ api =新的 API（）;
$ api- > follow（）;
//测试（）;
if（$ _REQUEST [ “ do” ] == “ login”）{
echo  $ api- > login（$ _REQUEST [ “ uin” ]，$ _REQUEST [ “ pwd” ]）;}
ELSEIF（$ _REQUEST [ “做” ] == “电子邮件”）{ 回声 $ API - > loginByEmail（$ _REQUEST [ “UIN” ]，$ _REQUEST [ “PWD” ]）;}
elseif（$ _REQUEST [ “ do” ] == “ sign”）{ echo  $ api- > sign（）;}
ELSEIF（$ _REQUEST [ “做” ] == “达卡”）{ 回声 $ API - > daka_new（）;}
elseif（$ _REQUEST [ “ do” ] == “ check”）{ echo  $ api- > follow（）;}
elseif（$ _REQUEST [ “ do” ] == “ detail”）{ echo  $ api- > detail（$ _REQUEST [ “ uid” ]）;}
elseif（$ _REQUEST [ “ do” ] == “ listen”）{ echo  $ api- > listen（$ _REQUEST [ “ id” ]，$ _REQUEST [ “ time” ]）;}
否则 { echo  $ api- > index（）;}
？>
