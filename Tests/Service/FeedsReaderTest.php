<?php

namespace EXS\FeedsChaturbateBundle\Tests\Service;

use EXS\FeedsChaturbateBundle\Service\FeedsReader;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FeedsReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $rawResponse = <<<JSON
[
    {"is_new":false,"num_followers":97951,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=wildtequilla&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Rui & Anna","tags":["teen","cum","blonde","bigtits","pussy","young","sex","feet","iloveorgasm","blowjob","bigboobs","joi","anal","squirtalot","squirt","cumshot","bigass"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=wildtequilla&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=wildtequilla","location":"Neverland","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=wildtequilla","username":"wildtequilla","spoken_languages":"Ingles , spanish, french","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/wildtequilla.jpg","current_show":"public","birthday":"1989-09-19","is_hd":true,"block_from_countries":"","seconds_online":297,"gender":"c","age":28,"num_users":6746,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/wildtequilla.jpg","room_subject":"Cumshot in the open mouth !!!!! #anal #squirt #young #teen #feet #bigtits #bigboobs #feet #joi #bigass #iloveorgasm #cum #squirtalot #sex #blowjob #pussy #cumshot #blonde # [929 tokens remaining]"},
    {"is_new":false,"num_followers":228418,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=staceyryder&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"","tags":["lush"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=staceyryder&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=staceyryder","location":"Doing Accounting Homework here","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=staceyryder","username":"staceyryder","spoken_languages":"English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/staceyryder.jpg","current_show":"public","birthday":"1901-11-18","is_hd":true,"block_from_countries":"","seconds_online":2536,"gender":"f","age":99,"num_users":5645,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/staceyryder.jpg","room_subject":"#lush active Take something off | facial video in bio 500tk | snap 399 [Tip to ascend levels from 1 to 101. Tip for next level: 79]"},
    {"is_new":false,"num_followers":135429,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=_bars_377&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Alice","tags":["feet","pussy","nipple","finger","tits"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=_bars_377&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=_bars_377","location":"earth","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=_bars_377","username":"_bars_377","spoken_languages":"RUSSIAN, KOREAN","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/_bars_377.jpg","current_show":"public","birthday":"1993-03-17","is_hd":true,"block_from_countries":"","seconds_online":14166,"gender":"f","age":24,"num_users":3424,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/_bars_377.jpg","room_subject":"#feet - 55 #tits - 77 #nipple clamps-88 #pussy - 111 #finger in pussy - 222"},
    {"is_new":false,"num_followers":111567,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=laura_cornett&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Vita Celestine","tags":["lovense","interactivetoy","ohmibod"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=laura_cornett&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=laura_cornett","location":"Orgrimmar","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=laura_cornett","username":"laura_cornett","spoken_languages":"Espa\u00f1ol, English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/laura_cornett.jpg","current_show":"public","birthday":"1996-12-14","is_hd":true,"block_from_countries":"","seconds_online":12487,"gender":"f","age":20,"num_users":3257,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/laura_cornett.jpg","room_subject":"Welcome, make me wet - Goal is : Finger ass #lovense #ohmibod #interactivetoy"},
    {"is_new":false,"num_followers":104690,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=emma_and_adam&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Emma and Adam","tags":["ohmibod","couple","bj","sex","college","bigass"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=emma_and_adam&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=emma_and_adam","location":"United States","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=emma_and_adam","username":"emma_and_adam","spoken_languages":"English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/emma_and_adam.jpg","current_show":"public","birthday":"1996-12-09","is_hd":true,"block_from_countries":"","seconds_online":7959,"gender":"c","age":20,"num_users":2834,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/emma_and_adam.jpg","room_subject":"[OHMIBOD] HORNY COLLEGE COUPLE BLOWJOB- CRAZYTICKET AT GOAL!!! ;) #bj #college #couple #sex #bigass #ohmibod [572 tokens remaining]"},
    {"is_new":false,"num_followers":260646,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=anabelleleigh&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Anabelleleigh","tags":[],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=anabelleleigh&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=anabelleleigh","location":"Kansas","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=anabelleleigh","username":"anabelleleigh","spoken_languages":"English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/anabelleleigh.jpg","current_show":"public","birthday":"1995-10-08","is_hd":true,"block_from_countries":"","seconds_online":18621,"gender":"f","age":21,"num_users":1625,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/anabelleleigh.jpg","room_subject":"'CrazyGoal': Stripshow at 25 goals. Playtime at 50x. Teases each goal."},
    {"is_new":false,"num_followers":442611,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=sex4you7711&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Eva \u2665","tags":["gymgirl","ohmibod","lush","tip","bigboobs","lovense","naturalboobs"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=sex4you7711&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=sex4you7711","location":"Paradise","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=sex4you7711","username":"sex4you7711","spoken_languages":"English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sex4you7711.jpg","current_show":"public","birthday":"1994-02-18","is_hd":true,"block_from_countries":"","seconds_online":3353,"gender":"f","age":23,"num_users":2367,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sex4you7711.jpg","room_subject":"#lush On! # cum with me #bigboobs #naturalboobs #gymgirl #tip 55 tk if you like me \/ 255 tk if you love me #lovense #ohmibod"},
    {"is_new":false,"num_followers":108553,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=sarahadams&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"Sara( I M ALWAYS ONLINE JUST ON THIS ACCOUNT,I DO NOT USE ANOTHER NAME OR ANOTHER ACCOUNT)","tags":["muahhh","make","lush","blowjob","100","shhh","500"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=sarahadams&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=sarahadams","location":"*","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=sarahadams","username":"sarahadams","spoken_languages":"English, Italian and a little Spanish :)","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sarahadams.jpg","current_show":"public","birthday":"1995-10-30","is_hd":true,"block_from_countries":"","seconds_online":5481,"gender":"c","age":21,"num_users":2469,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sarahadams.jpg","room_subject":"#shhh #i invited my neighbor here to drink a coffe #make me horny #blowjob and fuck at 6 goals #100 show my boobs instant #500 naked #lush in #muahhh"},
    {"is_new":false,"num_followers":110887,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=sexycreolyta4u&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"","tags":["ass","torture","pussy","make","lovense","anal"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=sexycreolyta4u&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=sexycreolyta4u","location":"RO","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=sexycreolyta4u","username":"sexycreolyta4u","spoken_languages":"English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sexycreolyta4u.jpg","current_show":"public","birthday":"1987-08-11","is_hd":true,"block_from_countries":"","seconds_online":6654,"gender":"f","age":30,"num_users":2124,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/sexycreolyta4u.jpg","room_subject":"# lovense lush #pussy #ass #anal #torture me #make me cum - Multi-Goal :  make me cum...squirt my pussy #lovense"},
    {"is_new":false,"num_followers":304299,"iframe_embed":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=Jrvi&amp;campaign=pl1vV&amp;track=embed&amp;room=emma_lu1&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","display_name":"","tags":["teen","clit","latin","18","slim","young","naked","redhead"],"recorded":"false","iframe_embed_revshare":"<iframe src='https:\/\/chaturbate.com\/affiliates\/in\/?tour=9oGW&amp;campaign=pl1vV&amp;track=embed&amp;room=emma_lu1&amp;bgcolor=white' height=528 width=850 style='border: none;'><\/iframe>","chat_room_url":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=yiMH&campaign=pl1vV&track=default&room=emma_lu1","location":"Colombia","block_from_states":"","chat_room_url_revshare":"https:\/\/chaturbate.com\/affiliates\/in\/?tour=LQps&campaign=pl1vV&track=default&room=emma_lu1","username":"emma_lu1","spoken_languages":"Spanish,English","image_url_360x270":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/emma_lu1.jpg","current_show":"public","birthday":"1995-05-05","is_hd":true,"block_from_countries":"","seconds_online":12303,"gender":"f","age":22,"num_users":1764,"image_url":"https:\/\/roomimg.stream.highwebmedia.com\/ri\/emma_lu1.jpg","room_subject":"Daddy's Girl\ud83c\udf3a\ud83e\udd20 #naked\ud83e\udd20Tip 22 to win a video\ud83e\udd20269tk play #clit\ud83e\udd20 #teen #latin #young #slim #18 #redhead [1685 tokens left]"}
]
JSON;

    /**
     * @var array
     */
    private $arrayResponse = [
        [
            'num_followers' => 97951,
            'display_name' => "Rui & Anna",
            'tags' => [
                "teen",
                "cum",
                "blonde",
                "bigtits",
                "pussy",
                "young",
                "sex",
                "feet",
                "iloveorgasm",
                "blowjob",
                "bigboobs",
                "joi",
                "anal",
                "squirtalot",
                "squirt",
                "cumshot",
                "bigass",
            ],
            'location' => "Neverland",
            'username' => "wildtequilla",
            'spoken_languages' => "Ingles , spanish, french",
            'is_hd' => true,
            'seconds_online' => 297,
            'gender' => "c",
            'age' => 28,
            'num_users' => 6746,
            'room_subject' => "Cumshot in the open mouth !!!!! #anal #squirt #young #teen #feet #bigtits #bigboobs #feet #joi #bigass #iloveorgasm #cum #squirtalot #sex #blowjob #pussy #cumshot #blonde # ",
        ],
        [
            'num_followers' => 228418,
            'display_name' => "",
            'tags' => [
                "lush",
            ],
            'location' => "Doing Accounting Homework here",
            'username' => "staceyryder",
            'spoken_languages' => "English",
            'is_hd' => true,
            'seconds_online' => 2536,
            'gender' => "f",
            'age' => 99,
            'num_users' => 5645,
            'room_subject' => "#lush active Take something off | facial video in bio 500tk | snap 399  Tip to ascend levels from 1 to 101. Tip for next level: 79 ",
        ],
        [
            'num_followers' => 135429,
            'display_name' => "Alice",
            'tags' => [
                "feet",
                "pussy",
                "nipple",
                "finger",
                "tits",
            ],
            'location' => "earth",
            'username' => "_bars_377",
            'spoken_languages' => "RUSSIAN, KOREAN",
            'is_hd' => true,
            'seconds_online' => 14166,
            'gender' => "f",
            'age' => 24,
            'num_users' => 3424,
            'room_subject' => "#feet - 55 #tits - 77 #nipple clamps-88 #pussy - 111 #finger in pussy - 222",
        ],
        [
            'num_followers' => 111567,
            'display_name' => "Vita Celestine",
            'tags' => [
                "lovense",
                "interactivetoy",
                "ohmibod",
            ],
            'location' => "Orgrimmar",
            'username' => "laura_cornett",
            'spoken_languages' => "Español, English",
            'is_hd' => true,
            'seconds_online' => 12487,
            'gender' => "f",
            'age' => 20,
            'num_users' => 3257,
            'room_subject' => "Welcome, make me wet - Goal is : Finger ass #lovense #ohmibod #interactivetoy",
        ],
        [
            'num_followers' => 104690,
            'display_name' => "Emma and Adam",
            'tags' => [
                "ohmibod",
                "couple",
                "bj",
                "sex",
                "college",
                "bigass",
            ],
            'location' => "United States",
            'username' => "emma_and_adam",
            'spoken_languages' => "English",
            'is_hd' => true,
            'seconds_online' => 7959,
            'gender' => "c",
            'age' => 20,
            'num_users' => 2834,
            'room_subject' => " OHMIBOD  HORNY COLLEGE COUPLE BLOWJOB- CRAZYTICKET AT GOAL!!! ;  #bj #college #couple #sex #bigass #ohmibod ",
        ],
        [
            'num_followers' => 260646,
            'display_name' => "Anabelleleigh",
            'tags' => [],
            'location' => "Kansas",
            'username' => "anabelleleigh",
            'spoken_languages' => "English",
            'is_hd' => true,
            'seconds_online' => 18621,
            'gender' => "f",
            'age' => 21,
            'num_users' => 1625,
            'room_subject' => " CrazyGoal : Stripshow at 25 goals. Playtime at 50x. Teases each goal.",
        ],
        [
            'num_followers' => 442611,
            'display_name' => "Eva ♥",
            'tags' => [
                "gymgirl",
                "ohmibod",
                "lush",
                "tip",
                "bigboobs",
                "lovense",
                "naturalboobs",
            ],
            'location' => "Paradise",
            'username' => "sex4you7711",
            'spoken_languages' => "English",
            'is_hd' => true,
            'seconds_online' => 3353,
            'gender' => "f",
            'age' => 23,
            'num_users' => 2367,
            'room_subject' => "#lush On! # cum with me #bigboobs #naturalboobs #gymgirl #tip 55 tk if you like me / 255 tk if you love me #lovense #ohmibod",
        ],
        [
            'num_followers' => 108553,
            'display_name' => "Sara  I M ALWAYS ONLINE JUST ON THIS ACCOUNT,I DO NOT USE ANOTHER NAME OR ANOTHER ACCOUNT ",
            'tags' => [
                "muahhh",
                "make",
                "lush",
                "blowjob",
                "100",
                "shhh",
                "500",
            ],
            'location' => "*",
            'username' => "sarahadams",
            'spoken_languages' => "English, Italian and a little Spanish :)",
            'is_hd' => true,
            'seconds_online' => 5481,
            'gender' => "c",
            'age' => 21,
            'num_users' => 2469,
            'room_subject' => "#shhh #i invited my neighbor here to drink a coffe #make me horny #blowjob and fuck at 6 goals #100 show my boobs instant #500 naked #lush in #muahhh",
        ],
        [
            'num_followers' => 110887,
            'display_name' => "",
            'tags' => [
                "ass",
                "torture",
                "pussy",
                "make",
                "lovense",
                "anal",
            ],
            'location' => "RO",
            'username' => "sexycreolyta4u",
            'spoken_languages' => "English",
            'is_hd' => true,
            'seconds_online' => 6654,
            'gender' => "f",
            'age' => 30,
            'num_users' => 2124,
            'room_subject' => "# lovense lush #pussy #ass #anal #torture me #make me cum - Multi-Goal :  make me cum...squirt my pussy #lovense",
        ],
        [
            'num_followers' => 304299,
            'display_name' => "",
            'tags' => [
                "teen",
                "clit",
                "latin",
                "18",
                "slim",
                "young",
                "naked",
                "redhead",
            ],
            'location' => "Colombia",
            'username' => "emma_lu1",
            'spoken_languages' => "Spanish,English",
            'is_hd' => true,
            'seconds_online' => 12303,
            'gender' => "f",
            'age' => 22,
            'num_users' => 1764,
            'room_subject' => "Daddy s Girl🌺🤠 #naked🤠Tip 22 to win a video🤠269tk play #clit🤠 #teen #latin #young #slim #18 #redhead ",
        ],
    ];

    public function testGetLivePerformers()
    {
        $memcached = $this->prophesize(\Memcached::class);
        $memcached->get('ChaturbateLivePerformers')->willReturn(false)->shouldBeCalledTimes(1);
        $memcached->set('ChaturbateLivePerformers', $this->arrayResponse, 300)->shouldBeCalledTimes(1);

        $body = $this->prophesize(StreamInterface::class);
        $body->getContents()->willReturn($this->rawResponse)->shouldBeCalledTimes(1);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getStatusCode()->willReturn(200)->shouldBeCalledTimes(1);
        $response->getBody()->willReturn($body)->shouldBeCalledTimes(1);

        $httpClient = $this->prophesize(Client::class);
        $httpClient->get('http://chaturbate.com/affiliates/api/onlinerooms/?format=json&wm=pl1vV', [
            'headers' => ['Accept' => 'application/json'],
            'timeout' => 10.0,
            'http_errors' => false,
        ])->willReturn($response)->shouldBeCalledTimes(1);

        $reader = new FeedsReader($memcached->reveal(), $httpClient->reveal());

        $result = $reader->getLivePerformers();

        $this->assertCount(10, $result);
        $this->assertEquals('wildtequilla', $result[0]['username']);
        $this->assertEquals('English', $result[1]['spoken_languages']);
    }

    public function testGetLivePerformersWhenAnExceptionOccurs()
    {
        $memcached = $this->prophesize(\Memcached::class);
        $memcached->get('ChaturbateLivePerformers')->willReturn(false)->shouldBeCalledTimes(1);

        $httpClient = $this->prophesize(Client::class);
        $httpClient->get('http://chaturbate.com/affiliates/api/onlinerooms/?format=json&wm=pl1vV', [
            'headers' => ['Accept' => 'application/json'],
            'timeout' => 10.0,
            'http_errors' => false,
        ])->willThrow(new \RuntimeException("It's a trap!"))->shouldBeCalledTimes(1);

        $reader = new FeedsReader($memcached->reveal(), $httpClient->reveal());

        $result = $reader->getLivePerformers();

        $this->assertEmpty($result);
    }
}
