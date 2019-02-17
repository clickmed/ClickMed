jQuery(document).ready(function () {
    var jssor_1_SlideshowTransitions = [];
    if(parseInt($( window ).width()) > 767){
      var jssor_1_SlideshowTransitions = [
        {$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Easing:$Jease$.$OutQuad},
        {$Duration:1200,y:0.3,$Cols:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Column:12},$Easing:{$Top:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
        {$Duration:1000,x:-1,y:2,$Rows:2,$Zoom:11,$Rotate:1,$SlideOut:true,$Assembly:2049,$ChessMode:{$Row:15},$Easing:{$Left:$Jease$.$InExpo,$Top:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InExpo},$Opacity:2,$Round:{$Rotate:0.85}},
        {$Duration:1200,x:4,$Cols:2,$Zoom:11,$SlideOut:true,$Assembly:2049,$ChessMode:{$Column:15},$Easing:{$Left:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear},$Opacity:2},
        {$Duration:1000,x:4,y:-4,$Zoom:11,$Rotate:1,$SlideOut:true,$Easing:{$Left:$Jease$.$InExpo,$Top:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InExpo},$Opacity:2,$Round:{$Rotate:0.8}},
        {$Duration:1500,x:0.3,y:-0.3,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$Easing:{$Left:$Jease$.$InJump,$Top:$Jease$.$InJump,$Clip:$Jease$.$OutQuad},$Round:{$Left:0.8,$Top:2.5}},
        {$Duration:1000,x:-3,y:1,$Rows:2,$Zoom:11,$Rotate:1,$SlideOut:true,$Assembly:2049,$ChessMode:{$Row:28},$Easing:{$Left:$Jease$.$InExpo,$Top:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InExpo},$Opacity:2,$Round:{$Rotate:0.7}},
        {$Duration:1200,y:-1,$Cols:8,$Rows:4,$Clip:15,$During:{$Top:[0.5,0.5],$Clip:[0,0.5]},$Formation:$JssorSlideshowFormations$.$FormationStraight,$ChessMode:{$Column:12},$ScaleClip:0.5},
        {$Duration:1000,x:0.5,y:0.5,$Zoom:1,$Rotate:1,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Top:$Jease$.$InCubic,$Zoom:$Jease$.$InCubic,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InCubic},$Opacity:2,$Round:{$Rotate:0.5}},
        {$Duration:1200,x:-0.6,y:-0.6,$Zoom:1,$Rotate:1,$During:{$Left:[0.2,0.8],$Top:[0.2,0.8],$Zoom:[0.2,0.8],$Rotate:[0.2,0.8]},$Easing:{$Zoom:$Jease$.$Swing,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$Swing},$Opacity:2,$Round:{$Rotate:0.5}},
        {$Duration:1500,y:-0.5,$Delay:60,$Cols:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationCircle,$Easing:$Jease$.$InWave,$Round:{$Top:1.5}},
        {$Duration:1000,$Delay:30,$Cols:8,$Rows:4,$Clip:15,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2050,$Easing:$Jease$.$InQuad},
        {$Duration:1200,$Delay:20,$Clip:3,$SlideOut:true,$Assembly:260,$Easing:{$Clip:$Jease$.$OutCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
      ];
    }
    /*[{b:-1,d:1,o:-1},{b:0,d:1200,y:300,o:1,e:{y:24,o:6}},{b:5600,d:800,y:-200,o:-1,e:{y:5}}],
    [{b:-1,d:1,o:-1},{b:400,d:800,x:200,o:1,e:{x:27,o:6}},{b:5600,d:800,x:-200,o:-1,e:{x:5}}],
    [{b:-1,d:1,o:-1},{b:400,d:800,x:-200,o:1,e:{x:27,o:6}},{b:5600,d:800,x:200,o:-1,e:{x:5}}],*/
    var DorSlideoTransitions = [
      [{b:-1,d:1,o:-1},{b:800,d:1000,x:200,y:300,o:1,e:{y:24,o:6}}],
      [{b:-1,d:1,o:-1},{b:1600,d:800,x:200,o:1,e:{x:27,o:6}}],
      [{b:-1,d:1,o:-1},{b:0,d:800,x:-200,o:1,e:{x:27,o:6}}],
      [{b:-1,d:1,o:-1},{b:3000,d:600,x:200,y:-100,o:1,e:{x:3,y:3}}],
      [{b:4600,d:960,x:-204}],
      [{b:-1,d:1,sX:-1,sY:-1},{b:3400,d:400,sX:1,sY:1},{b:3800,d:300,o:-1,sX:0.1,sY:0.1}],
      [{b:-1,d:1,sX:-1,sY:-1},{b:3520,d:400,sX:1,sY:1},{b:3920,d:300,o:-1,sX:0.1,sY:0.1}],
      [{b:-1,d:1,o:-1},{b:2200,d:1200,x:-135,y:-24,o:1,e:{x:7,y:7}},{b:4600,d:640,x:-130}],
      [{b:-1,d:1,o:-1},{b:4600,d:240,x:-75,o:1,e:{x:1}},{b:4840,d:480,x:-150,e:{x:1}},{b:5320,d:240,x:-75,o:-1,e:{x:1}}],
      [{b:2800,d:600,y:70,sX:-0.5,sY:-0.5,e:{y:5}},{b:6000,d:600,y:50,r:-10},{b:7000,d:400,o:-1,rX:10,rY:-10}],
      [{b:0,d:600,x:-742,sX:4,sY:4,e:{x:6}},{b:900,d:600,sX:-4,sY:-4}],
      [{b:-1,d:1,o:-1},{b:400,d:500,o:1,e:{o:5}}],
      [{b:-1,d:1,o:-1,r:-180},{b:1500,d:500,o:1,r:180,e:{r:27}}],
      [{b:-1,d:1,o:-1,r:180},{b:2000,d:500,o:1,r:-180,e:{r:27}}],
      [{b:2800,d:600,y:-270,e:{y:6}}],
      [{b:6000,d:600,y:-100,r:-10,e:{y:6}},{b:7000,d:400,o:-1,rX:-10,rY:10}],
      [{b:-1,d:1,sX:-1,sY:-1},{b:3400,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:3800,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
      [{b:-1,d:1,o:-1},{b:3400,d:600,o:1},{b:4000,d:1000,r:360,e:{r:1}}],
      [{b:-1,d:1,o:-1},{b:3400,d:600,y:-70,o:1,e:{y:27}}],
      [{b:-1,d:1,sX:-1,sY:-1},{b:3700,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:4100,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
      [{b:-1,d:1,o:-1},{b:3700,d:600,o:1},{b:4300,d:1000,r:360}],
      [{b:-1,d:1,o:-1},{b:3700,d:600,x:-150,o:1,e:{x:27}}],
      [{b:-1,d:1,sX:-1,sY:-1},{b:4000,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:4400,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
      [{b:-1,d:1,o:-1},{b:4000,d:600,o:1},{b:4600,d:1000,r:360}],
      [{b:-1,d:1,o:-1},{b:4000,d:600,x:150,o:1,e:{x:27}}],
      [{b:9300,d:600,o:-1,r:540,sX:-0.5,sY:-0.5,e:{r:5}}],
      [{b:-1,d:1,o:-1,sX:2,sY:2},{b:6880,d:20,o:1},{b:6900,d:300,sX:-2.08,sY:-2.08,e:{sX:27,sY:27}},{b:7200,d:240,sX:0.08,sY:0.08}],
      [{b:-1,d:1,o:-1,sX:5,sY:5},{b:200,d:600,o:1,sX:-5,sY:-5}],
      [{b:-1,d:1,o:-1},{b:7200,d:440,o:1}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7420,d:20,o:1},{b:7440,d:200,r:180,sX:0.4,sY:0.4},{b:7640,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:7620,d:20,o:1},{b:7640,d:300,r:60,sX:1.1,sY:1.1},{b:7940,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7920,d:20,o:1},{b:7940,d:300,sX:1.4,sY:1.4},{b:8240,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7620,d:20,o:1},{b:7640,d:200,r:180,sX:0.4,sY:0.4},{b:7840,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:7820,d:20,o:1},{b:7840,d:300,r:60,sX:1.1,sY:1.1},{b:8140,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8120,d:20,o:1},{b:8140,d:300,sX:1.4,sY:1.4},{b:8440,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7820,d:20,o:1},{b:7840,d:200,r:180,sX:0.4,sY:0.4},{b:8040,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8020,d:20,o:1},{b:8040,d:300,r:60,sX:1.1,sY:1.1},{b:8340,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8320,d:20,o:1},{b:8340,d:300,sX:1.4,sY:1.4},{b:8640,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8020,d:20,o:1},{b:8040,d:200,r:180,sX:0.4,sY:0.4},{b:8240,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:300,r:60,sX:1.1,sY:1.1},{b:8540,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8520,d:20,o:1},{b:8540,d:300,sX:1.4,sY:1.4},{b:8840,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:200,r:180,sX:0.4,sY:0.4},{b:8440,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8420,d:20,o:1},{b:8440,d:300,r:60,sX:1.1,sY:1.1},{b:8740,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8720,d:20,o:1},{b:8740,d:300,sX:1.4,sY:1.4},{b:9040,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:200,r:180,sX:0.4,sY:0.4},{b:8440,d:200,r:180,sX:0.5,sY:0.5}],
      [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8420,d:20,o:1},{b:8440,d:300,r:60,sX:1.1,sY:1.1},{b:8740,d:160,sX:-0.2,sY:-0.2}],
      [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8720,d:20,o:1},{b:8740,d:300,sX:1.4,sY:1.4},{b:9040,d:160,sX:-0.5,sY:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:0,d:400,y:330},{b:900,d:400,y:50,rX:80},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:-1,d:1,o:-0.5},{b:900,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:1700,d:400,y:310},{b:2600,d:400,y:50,rX:80},{b:20000,d:1000,y:20},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:20000,d:1000,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:2600,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:3400,d:400,y:290},{b:5100,d:400,y:50,rX:80},{b:20000,d:1000,y:40},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:20000,d:1000,o:-1}],
      [{b:-1,d:1,c:{t:-280}},{b:3880,d:20,c:{t:50.40}},{b:3980,d:20,c:{t:33.60}},{b:4080,d:20,c:{t:30.80}},{b:4180,d:20,c:{t:30.80}},{b:4280,d:20,c:{t:33.60}},{b:4380,d:20,c:{t:22.40}},{b:4480,d:20,c:{t:28.00}},{b:4580,d:20,c:{t:50.40}}],
      [{b:-1,d:1,o:-0.5},{b:5100,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:6000,d:400,y:270},{b:6900,d:400,y:50,rX:40},{b:15000,d:500,rX:40},{b:20000,d:1000,y:60},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:20000,d:1000,o:-1}],
      [{b:6900,d:400,o:-0.2},{b:15000,d:500,o:-0.8}],
      [{b:-1,d:1,o:-0.5},{b:15000,d:500,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:7800,d:400,y:270},{b:8700,d:400,y:50,rX:40},{b:15000,d:500,rX:40},{b:20000,d:1000,y:60},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:8700,d:400,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:8700,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:9600,d:400,y:270},{b:10500,d:400,y:50,rX:40},{b:15000,d:500,rX:40},{b:20000,d:1000,y:60},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:10500,d:400,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:10500,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:11400,d:400,y:270},{b:12300,d:400,y:50,rX:40},{b:15000,d:500,rX:40},{b:20000,d:1000,y:60},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:12300,d:400,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:12300,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:13200,d:400,y:270},{b:14100,d:400,y:50,rX:40},{b:15000,d:500,rX:40},{b:20000,d:1000,y:60},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:14100,d:400,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:14100,d:400,o:-0.5}],
      [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:16000,d:400,y:270},{b:19100,d:400,y:30,rX:80},{b:20000,d:1000,y:80},{b:21000,d:1000,y:-95,rX:-80,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:23000,d:900,y:25,o:-1,rX:60}],
      [{b:20000,d:1000,o:-1}],
      [{b:-1,d:1,o:-0.5},{b:19100,d:400,o:-0.5}],
      [{b:-1,d:1,o:-1},{b:16400,d:300,o:1},{b:16700,d:500,x:-238}],
      [{b:-1,d:1,o:-1},{b:16400,d:300,o:1},{b:16700,d:500,x:238}],
      [{b:-1,d:1,o:-1},{b:17000,d:400,y:200,o:1,e:{y:2,o:6}},{b:17400,d:300,y:-28,e:{y:3}},{b:17700,d:300,y:28,e:{y:2}}],
      [{b:-1,d:1,o:-1},{b:17200,d:400,y:200,o:1,e:{y:2,o:6}},{b:17600,d:300,y:-28,e:{y:3}},{b:17900,d:300,y:28,e:{y:2}}],
      [{b:-1,d:1,o:-1},{b:17400,d:400,y:200,o:1,e:{y:2,o:6}},{b:17800,d:300,y:-28,e:{y:3}},{b:18100,d:300,y:28,e:{y:2}}],
      [{b:-1,d:1,o:-1},{b:17600,d:400,y:200,o:1,e:{y:2,o:6}},{b:18000,d:300,y:-28,e:{y:3}},{b:18300,d:300,y:28,e:{y:2}}],
      [{b:-1,d:1,sX:0,sY:0},{b:800,d:400,x:0,y:290,o:1}], //81
      [{b:-1,d:1,sX:-1,sY:-1},{b:2200,d:400,x:200,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:2600,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],//82
      [{b:-1,d:1,sX:-1,sY:-1},{b:1500,d:400,x:200,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:2600,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],//83
      [{b:-1,d:1,sX:-1,sY:-1},{b:200,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:600,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],//84
      [{b:-1,d:1,sX:-1,sY:-1},{b:700,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:1100,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],//85
      [{b:-1,d:1,sX:-1,sY:-1},{b:1200,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:1600,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],//86
      [{b:-1,d:1,o:-1},{b:1000,d:800,x:0,o:1,e:{x:27,o:6}}], //87
      [{b:-1,d:1,o:-1},{b:0,d:800,x:-200,o:1,e:{x:27,o:6}}], //88
      [{b:-1,d:1,o:-1},{b:2400,d:600,sX:0,y:-100,o:1,e:{x:3,y:3}}] //89
    ];
    var _SlideshowTransitions = [
        { $Duration: 1200, $Opacity: 2 }
    ];
    var dorSliderOptions = {
      $AutoPlay: true,
      $SlideDuration: 800,
      $SlideEasing: $Jease$.$OutQuint,
      $CaptionSliderOptions: {
        $Class: $JssorCaptionSlideo$,
        $Transitions: DorSlideoTransitions
      },
      $ArrowNavigatorOptions: {
        $Class: $JssorArrowNavigator$,
        $ChanceToShow : parseInt($('.homeslider').data('arrow'))
      },
      $BulletNavigatorOptions: {
        $Class: $JssorBulletNavigator$,
        $ChanceToShow:parseInt($('.homeslider').data('nav'))
      },
      $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
          $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
          $Transitions: jssor_1_SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
          $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
          $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
     }
    };
    
    var dor_slider = new $JssorSlider$("Dor_Full_Slider", dorSliderOptions);
    
    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    function ScaleSlider() {
        var refSize = dor_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            refSize = Math.min(refSize, 1920);
            dor_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
});